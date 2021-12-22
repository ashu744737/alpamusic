<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserContact;
use App\User;
use App\ContactTypes;
use App\UserTypes;
use DB;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function getContactList()
    {
        $contacts = UserContact::whereHas('user', function ($query) {
            $query->whereHas('user_type', function ($q) {
                $q->where('type', 'External');
            });
        })
            ->with(['userType', 'user', 'contactType'])
            ->when(auth()->user()->type_id != 1, function ($query) {
                return $query->where('user_id', auth()->id());
            });

        return DataTables::of($contacts)
            ->addColumn('name', function ($contact) {
                $contactArr = $contact->toArray();
                return ($contactArr['first_name'] != '' || $contactArr['last_name'] != '') ? $contactArr['first_name'] . ' ' . $contactArr['last_name'] : $contactArr['user']['name'];
            })->addColumn('user_type', function ($contact) {
                $contactArr = $contact->toArray();
                if(config('app.locale') == 'hr'){
                    return $contactArr['user_type']['hr_type_name'];
                } else {
                    return $contactArr['user_type']['type_name'];
                }
            })->addColumn('contact_type', function ($contact) {
                $contactArr = $contact->toArray();
                if(config('app.locale') == 'hr'){
                    return $contactArr['contact_type']['hr_type_name'];
                } else {
                    return $contactArr['contact_type']['type_name'];
                }
                
            })->addColumn('status', function ($client) {
                if ($client->is_default) {
                    return '<span class="badge dt-badge badge-success">'.trans('general.default').'</span>';
                }
                return "";
            })->addColumn('action', function ($client) {
                $btns = '';
                $contactId = \Crypt::encrypt($client->id);
                $btns .= "<span class='noVis d-inline-flex'>";
                if (check_perm('contact_edit')) {
                    $editUrl = $editUrl = route('contacts.edit', ['contact_id' => \Crypt::encrypt($client->id)]);
                    $editText = trans('general.edit');
                    $btns .= "<a href='{$editUrl}' class='dt-btn-action' title='{$editText}'>
                        <i class='mdi mdi-account-edit mdi-18px'></i>
                    </a>";
                }
                if (check_perm('contact_delete')) {
                    $deleteText = trans('general.delete');
                    $btns .= " <a class='dt-btn-action text-danger delete-record' data-id='{$contactId}' title='{$deleteText}'>
                        <i class='mdi mdi-account-remove mdi-18px'></i>
                    </a>
              ";
                }
                $btns .= "  </span>";
                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $users = User::whereHas('user_type', function ($q) {
            $q->where('type', 'External');
        })->where('type_id', '!=', 1)->get();
        $userType = UserTypes::select('id', 'type_name', 'hr_type_name', 'type')->where('type', 'External')->get();
        $contactType = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->get();
        return view('contacts.create', compact('users', 'contactType', 'userType'));
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'first_name' => ['required', 'regex:/^[a-zA-Z-,\.\s]+$/x'],
                'last_name' => ['required', 'regex:/^[a-zA-Z-,\.\s]+$/x'],
            ], [
                'first_name.regex' => trans('form.contact.field.validation.error.first_name'),
                'last_name.regex' => trans('form.contact.field.validation.error.last_name'),
            ]);
            DB::beginTransaction();
            if (auth()->user()->type_id == 1) {
                //$userId = $request->user;
                $user['id'] = $request->user;
                $user['type_id'] = $request->user_type;
            } else {
                $user = auth()->user();
            }
            if (isset($request->is_default)) {
                $isExsist = UserContact::where('user_id', $user['id'])->where('is_default', 1)->first();
                if ($isExsist != null) {
                    UserContact::where('id', $isExsist->id)->update(['is_default' => 0]);
                }
            }
            $contypeid = ContactTypes::where('type_name', $request->contact_type_id)->pluck('id');

            $newContact = [
                'user_type_id' => $user['type_id'],
                'user_id' => $user['id'],
                'contact_type_id' => $contypeid[0],
                'is_default' => isset($request->is_default) ? 1 : 0,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'family' => !empty($request->family_name) ? $request->family_name : NULL,
                'workplace' => !empty($request->workplace) ? $request->workplace : NULL,
                'phone' => !empty($request->phone) ? $request->phone : NULL,
                'mobile' => !empty($request->mobile) ? $request->mobile : NULL,
                'fax' => !empty($request->fax) ? $request->fax : NULL,
                'email' => !empty($request->email) ? $request->email : NULL,
                'contact_type' => (($request->contact_type_id == 'Other' || $request->contact_type_id == 'Contact') && $request->other_text != '') ? $request->other_text : $request->contact_type_id
            ];

            $user = UserContact::create($newContact);
            DB::commit();
            return redirect()->route('contacts')->with('success', trans('form.contact.added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('contactsCreate')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function edit($id)
    {
        $contact = UserContact::find(\Crypt::decrypt($id));
        $users = User::whereHas('user_type', function ($q) {
            $q->where('type', 'External');
        })->where('type_id', '!=', 1)->get();
        $contactType = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->get();
        $userType = UserTypes::select('id', 'type_name', 'type', 'hr_type_name')->where('type', 'External')->get();

        return view('contacts.edit', compact('contact', 'users', 'contactType', 'userType'));
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'first_name' => ['required', 'regex:/^[a-zA-Z-,\.\s]+$/x'],
                'last_name' => ['required', 'regex:/^[a-zA-Z-,\.\s]+$/x'],
            ], [
                'first_name.regex' => trans('form.contact.field.validation.error.first_name'),
                'last_name.regex' => trans('form.contact.field.validation.error.last_name'),
            ]);
            DB::beginTransaction();

            $contactId = $request->contact_id;
            /*if($request->user){
                $user = json_decode($request->user, true);
            }else{
                $user = auth()->user();
            }*/
            if (auth()->user()->type_id == 1) {
                $user['id'] = $request->user;
                $user['type_id'] = $request->user_type;
            } else {
                $user = auth()->user();
            }
            if (isset($request->is_default)) {
                $isExsist = UserContact::where('user_id', $user['id'])->where('is_default', 1)->first();
                if ($isExsist != null) {
                    UserContact::where('id', $isExsist->id)->update(['is_default' => 0]);
                }
            }
            $contypeid = ContactTypes::where('type_name', $request->contact_type_id)->pluck('id');
            $contactData = [
                'user_type_id' => $user['type_id'],
                'user_id' => $user['id'],
                'contact_type_id' => $contypeid[0],
                'is_default' => isset($request->is_default) ? 1 : 0,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'family' => !empty($request->family_name) ? $request->family_name : NULL,
                'workplace' => !empty($request->workplace) ? $request->workplace : NULL,
                'phone' => !empty($request->phone) ? $request->phone : NULL,
                'mobile' => !empty($request->mobile) ? $request->mobile : NULL,
                'fax' => !empty($request->fax) ? $request->fax : NULL,
                'email' => !empty($request->email) ? $request->email : NULL,
                'contact_type' => (($request->contact_type_id == 'Other' || $request->contact_type_id == 'Contact') && $request->other_text != '') ? $request->other_text : $request->contact_type_id
            ];
            UserContact::where('id', $contactId)->update($contactData);
            DB::commit();
            return redirect()->route('contacts')->with('success', trans('form.contact.updated'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('contacts')->with('error', trans("form.registration.something_went_wrong") ."( " . $th->getMessage() . " )");
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $contact = UserContact::where('id', \Crypt::decrypt($id))->first();
            $contact->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.contact.deleted'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans("form.registration.something_went_wrong"),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function deleteMultiple(Request $request)
    {
        if (!empty($request->ids)) {
            UserContact::whereIn('id', $request->ids)->delete();
        }
        return response()->json(['success' => true], 200);
    }
}
