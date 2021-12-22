<?php

namespace App\Http\Controllers;

use App\Tickets;
use App\TicketMessages;
use App\Investigations;
use App\InvestigationTransition;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailTicketCreated;
use App\Mail\EmailTicketStatus;
use App\Mail\EmailSendMessage;
use Carbon\Carbon;
use DateTime;

class TicketsController extends Controller
{
    public function index()
    {
        return view('tickets.index');
    }

    public function ticketList()
    {
        $tickets = Tickets::with([
            'user' => function ($q) {
                $q->select(['id', 'name']);
            },
            'investigations' => function ($q) {
                $q->select(['id', 'user_inquiry', 'work_order_number']);
            },
        ])->when(isClient(), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        });

        return DataTables::of($tickets)
            ->addColumn('investigations', function ($ticket) {
                    return !is_null($ticket->investigation_id) ? $ticket->investigations->user_inquiry.' - '.$ticket->investigations->work_order_number : '';
            })
            ->editColumn('type', function($ticket) {
                if($ticket->type == "Thank You"){
                    return trans('form.ticket.thank_you');
                } else {
                    return trans('form.ticket.complaint');
                }
            })
            ->editColumn('created_at', function ($ticket) {
                return Carbon::parse($ticket->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($ticket) {
                if ($ticket->status) {
                    if ($ticket->status == 'Close') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($ticket->status)) . '</span>
                        </td>';
                    } else if ($ticket->status == 'Open') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($ticket->status)) . '</span>
                        </td>';
                    }
                }
                return "";
            })
            ->addColumn('action', function ($ticket) {
                $btns = "<span class='d-inline-flex'>";
                $ticketId = Crypt::encrypt($ticket->id);
                $btns .= '<a href="' . route('ticket.messages', ['ticketId' => $ticketId]) . '" class="dt-btn-action" title="' . trans('general.message') . '">
                        <i class="mdi mdi-chat mdi-18px"></i>
                    </a>';
                if(isAdmin() || isSM()) {
                    $btns .= '<a href="' . route('ticket.view', ['ticketId' => $ticketId]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                    <i class="fa fa-file-alt" style="font-size: 14px;margin: 5px 1px;"></i></a>';
                }
                $btns .= '</span>';
                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $investigations = Investigations::where('status', 'Closed')->when(isClient(), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->get();
        
        return view('tickets.create', compact('investigations'));
    }

    public function ticketValidator(array $data)
    {
        return Validator::make($data, [
            'type' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'file' => 'mimes:jpeg,bmp,png,gif,svg,pdf|nullable',
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->ticketValidator($request->all())->validate();
            DB::beginTransaction();
            $fileName = NULL;
            if ($request->hasfile('file')) {
                $filePath = public_path('ticket-documents');
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                $file = $request->file('file');

                $fileName = time() . '-'.trans('form.tickets').'-' . trim($file->getClientOriginalName());
                $originalName = $file->getClientOriginalName();
                try {
                    $file->move($filePath . '/', $fileName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->route('tickets.create')->with('error', trans('form.error') . ' ( ' . $e->getMessage() . ' )');
                }
            }
            
            $ticketData = Tickets::create([
                'investigation_id' => $request->investigation_id?$request->investigation_id:NULL,
                'user_id' => Auth::user()->id,
                'subject' => $request->subject,
                'type' => $request->type,
                'message' => $request->message,
                'file' => $fileName,
                'status' => 'Open'
            ]);

            $storeMessage = TicketMessages::create([
                'user_id' => Auth::user()->id,
                'ticket_id' => $ticketData->id,
                'message' => $request->message
            ]);

            $otherdata = [
                'success_msg' => trans('form.tickets.genrated'),
                'reason_msg' => $request->message,
                'subject' => $request->subject,
                'file' => $fileName
            ];

            $invtransdata = [
                'event' => 'ticket_create',
                'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'ticket_id' => $ticketData->id, 'type' => 'created_by'))),
            ];
            // if(!is_null($ticketData->investigation_id)){
            InvestigationTransition::addTransion($invtransdata, NULL);
            // }
            
            $data = Tickets::with(['user', 'investigations'])->where('id', $ticketData->id)->first()->toArray();
            $data['created_at'] = Carbon::parse($data['created_at'])->format('d M y');
            
            Mail::to(Auth::user()->email)->queue(new EmailTicketCreated(Auth::user(), $otherdata, $data));

            DB::commit();

            return redirect()->route('tickets.index')->with('success', trans('form.ticket.new_ticket_added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tickets.create')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function show(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $ticket = Tickets::with(['user', 'investigations'])->where('id', $id)->first();
        return view('tickets.show', compact('ticket'));
    }

    public function messages(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        
        $ticket = Tickets::where('id', $id)->first();

        $messages = TicketMessages::where('ticket_id', $id)->get()->toArray();
        
        return view('tickets.messages', compact('messages', 'ticket'));
    }

    public function sendMessage(Request $request)
    {
        $user = User::with('user_type')->where('id', Auth::user()->id)->first();
        $admin = User::where('type_id', 1)->first();
        $ticket = Tickets::where('id', $request->ticket_id)->first();
        $message = $request->message;
        $storeMessage = TicketMessages::create([
            'user_id' => Auth::user()->id,
            'ticket_id' => $request->ticket_id,
            'message' => $request->message
        ]);
        if($user->user_type->type_name == env('USER_TYPE_CLIENT')){
            $invtransdata = [
                'event' => 'send_message',
                'data' => json_encode(array('data' => array('id' => $ticket->id, 'message' => $message))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);
            
            $mailVerify = Mail::to($admin->email)->queue(new EmailSendMessage($user, $ticket, $storeMessage));
        } else if($user->user_type->type_name == env('USER_TYPE_ADMIN')) {
            $invtransdata = [
                'event' => 'send_message',
                'data' => json_encode(array('data' => array('id' => $ticket->id, 'message' => $storeMessage))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('form.ticket.message_send'),
        ]);
    }

    public function refreshChat(Request $request, $id)
    {
        $ticket = Tickets::where('id', $id)->first();

        $messages = TicketMessages::where('ticket_id', $id)->orderBy('created_at')->get()->toArray();
        foreach($messages as $key => $message) {
            if(Carbon::parse($message['created_at'])->format('Y-m-d') == Carbon::parse(Carbon::now())->format('Y-m-d')) {
                $messages[$key]['timeAgo'] = $this->time_elapsed_string($message['created_at']);
            } else if(Carbon::parse($message['created_at'])->format('Y') == Carbon::parse(Carbon::now())->format('Y')) {
                $messages[$key]['timeAgo'] = Carbon::parse($message['created_at'])->format('d M');
            } else {
                $messages[$key]['timeAgo'] = Carbon::parse($message['created_at'])->format('d M y');
            }
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $messages,
        ]);
    }

    public function changeStatus(Request $request)
    {
        try {
            $data = Tickets::where('id', $request->id)->update(['status'=>$request->status]);
            $user = Auth::user();
            $ticketData = Tickets::with(['user', 'investigations'])->where('id', $request->id)->first()->toArray();
            
            $ticketData['email_subject'] = trans('form.ticket.email.subject');
            if($ticketData['status'] == 'Open'){
                $status = trans('form.ticket.open');
            } else{
                $status = trans('form.ticket.close');
            }
            $invtransdata = [   
                'event' => 'ticket_status_change',
                'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'ticket_id' => $ticketData['id'], 'status' => $status))),
            ];
            
            InvestigationTransition::addTransion($invtransdata, NULL);
            
            Mail::to($ticketData['user']['email'])->queue(new EmailTicketStatus($user, $ticketData['user'], $ticketData));

            return response()->json([
                'status' => 'success',
                'msg' => trans('form.ticket.status_change'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'success',
                'msg' => $th->getMessage(),
            ]);
        }
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => trans('form.ticket.message.hr'),
            'i' => trans('form.ticket.message.min'),
            's' => trans('form.ticket.message.sec'),
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . " ".trans('form.ticket.message.ago') : trans('form.ticket.message.just_now');
    }
}
