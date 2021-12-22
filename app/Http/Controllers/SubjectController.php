<?php

namespace App\Http\Controllers;

use App\Subjects;
use App\SubjectTypes;
use App\Investigations;
use App\UserCategories;
use App\Client;
use App\ClientProduct;
use App\InvestigatorInvestigations;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    public function index()
    {
        return view('subjects.index');
    }

    public function investigationIndex($subjectId)
    {
        $id = Crypt::decrypt($subjectId);
        return view('subjects.investigations', compact('id'));
    }

    public function getSubjectsList()
    {
        if(isSM()){
            $usercats = UserCategories::getUserCategories();
            $subjects = Subjects::whereHas('investigation', function ($q) use ($usercats) {
                $q->whereHas('product', function ($q) use ($usercats) {
                    $q->whereIn('category_id',  $usercats);
                });
            })
            ->with('investigation')
            ->orderBy('id', 'DESC');
        } else {
            $subjects = Subjects::with('investigation')->orderBy('id', 'DESC');

            if(!isAdmin()) {
                $subjects = $subjects->whereHas('investigation', function ($query) {
                    $query->when((!isAdmin()), function ($q) {
                        return $q->where('user_id', auth()->user()->id);
                    });
                });
            }
        }

        return DataTables::of($subjects)
            ->editColumn('sub_type', function ( $subject) {
                $subjectType = "";

                if(in_array($subject->sub_type, array('Main', 'Spouse', 'Company'))){
                    if(config('app.locale') == 'hr'){
                        $subjectType = SubjectTypes::where('name', $subject->sub_type)->pluck('hr_name')->toArray();
                    } else {
                        $subjectType = SubjectTypes::where('name', $subject->sub_type)->pluck('name')->toArray();
                    }
                }else{
                    $subjectType = $subject->sub_type;
                }

                return $subjectType;
            })
            ->addColumn('investigation', function ($subject) {
                return !empty($subject->investigation) ? $subject->investigation->work_order_number.'('.$subject->investigation->user_inquiry.')' : '' ; 
            })
            ->addColumn('action', function ($subject) {
                $btns = '';
                $subjectId = Crypt::encrypt($subject->id);
                $btns .= "<span class='noVis d-inline-flex'>";
                if (check_perm('subject_show')) {
                    $btns .= '<a href="' . route('subject.detail', ['subjectId' => $subjectId]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                        <i class="mdi mdi-eye mdi-18px"></i>
                    </a>';
                }
                if (check_perm('investigation_show')) {
                    $btns .= '<a href="' . route('subject.investigations', ['subjectId' => $subjectId]) . '" class="dt-btn-action" title="' . trans('general.view') . ' '. trans('form.investigations') . '">
                    <i class="mdi mdi-cash-usd mdi-18px"></i>
                    </a>';
                }
                $btns .= "  </span>";
                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getSubInvList(Request $request, $subjectId)
    {   
        if (isSM()) {
            $usercats = UserCategories::getUserCategories();
            $investigations = Investigations::with([
                'subjects' => function ($q) use ($subjectId) {
                    $q->select(['id', 'investigation_id', 'family_name', 'first_name'])->where('id', $subjectId);
                },
                'product' => function ($q) {
                    $q->select(['id', 'name']);
                }
            ])->whereHas('product', function ($q) use ($usercats) {
                $q->select(['id', 'name', 'category_id'])->whereIn('category_id',  $usercats);
            })->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('id', $subjectId);
            });
        } else {
            $investigations = Investigations::with([
                'subjects' => function ($q) use ($subjectId) {
                    $q->select(['id', 'investigation_id', 'family_name', 'first_name'])->where('id', $subjectId);
                },
                'product' => function ($q) {
                    $q->select(['id', 'name']);
                }
            ])->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('id', $subjectId);
            });
        }

        if ($request->filled('investigator_id')) {
            $investigations = $investigations->whereHas('investigators', function ($q) use ($request) {
                $q->where('investigator_id', $request->get('investigator_id'));
            });
        }

        if (!isAdmin()) {
            if (isClient()) {
                $investigations = $investigations->where('user_id', Auth::id());
            }
        }

        return DataTables::of($investigations)
            ->addColumn('product_name', function ($investigations) {
                return !empty($investigations->product->name) ? $investigations->product->name : '';
            })
            ->addColumn('subject_name', function ($investigations) {
                return !$investigations->subjects->isEmpty() ? $investigations->subjects[0]->first_name.' '.$investigations->subjects[0]->family_name : '';
            })
            ->addColumn('first_name', function ($investigations) {
                return !$investigations->subjects->isEmpty() ? $investigations->subjects[0]->first_name : '';
            })
            ->addColumn('family_name', function ($investigations) {
                return !$investigations->subjects->isEmpty() ? $investigations->subjects[0]->family_name : '';
            })
            ->addColumn('paying_customer_name', function ($investigation) {
                return !empty($investigation->client_customer) ? $investigation->client_customer->name : '';
            })
            ->addColumn('inquiry', function ($investigation) {
                $clientId = Client::where('user_id', $investigation->user_id)->pluck('id')->first();
                $typeofInq =  ClientProduct::with('product:id,name')->whereHas('product')->where('client_id', $clientId)->where('product_id', $investigation->type_of_inquiry)->first();

                return !empty($typeofInq) ? $typeofInq->product->name  : '';
            })
            ->editColumn('created_at', function ($investigation) {
                return Carbon::parse($investigation->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($investigation) {
                if ($investigation->status) {
                    if ($investigation->status == 'New Request') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Pending Approval') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Open') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Assigned') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Approved') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Declined') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Modification Required') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Closed') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Cancelled') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Investigation Started') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Report Writing') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Delivered') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($investigation) {
                $btns = '<div class="action_btn">';
                if(!isAccountant() && !isSecretary()){
                    if ((isClient() && $investigation->status == 'Pending Approval') || !isClient()) {
                        $btns .= '<a href="' . route('investigation.edit', [Crypt::encrypt($investigation->id)]) . '" class="dt-btn-action" title="' . trans('general.edit') . '">
                                    <i class="fas fa-edit"></i>
                                </a>';
                        if((isAdmin()) || $investigation->created_by == Auth::id())
                        $btns .= '<a href="javascript:void(0)" id="deleteInvestigation" class="dt-btn-action" title="' . trans('general.delete') . '" data-id="' . $investigation->id . '">
                                    <i class="fas fa-trash"></i>
                                </a>';
                    }

                    $btns .= '<a href="javascript:void(0)" id="duplicateInvestigation" class="dt-btn-action" title="' . trans('general.duplicate') . '" data-id="' . Crypt::encrypt($investigation->id) . '">
                        <i class="fas fa-clone" ></i>
                    </a>';
                }

                $btns .= '<a href="' . route('investigation.show', [Crypt::encrypt($investigation->id)]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="fas fa-file-alt"></i>
                            </a>';
                $btns .= '</div>';

                if(!isClient() || !isInvestigator() || !isDeliveryboy()){
                    $assignment = InvestigatorInvestigations::where('investigation_id', $investigation->id)
                        ->where('status', 'Final Report Submitted')
                        ->orderBy('created_at', 'DESC')->first();

                    if (!empty($assignment)) {
                        $btns .= '<a href="' . route('investigation.showinvoice', [Crypt::encrypt($investigation->id)]) . '" class="dt-btn-action" title="' . trans('form.investigationinvoice.viewandgenerateinvoice') . '">
                                <i class="fas fa-money-bill-wave "></i>
                            </a>';
                    }
                }
                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the Subjects Data
     *
     * @param  int  $userid = Crypt::encrypt($userId)
     * @return \Illuminate\Http\Response
     */
    public function show($subjectId)
    {
        try {
            $subjectId = Crypt::decrypt($subjectId);
            $subjects = Subjects::find($subjectId);

            return view('subjects.show', compact('subjects'));
        } catch (Exception $exception) {
            return back()->with('warning', trans('form.registration.something_went_wrong'));
        }
    }
    public function updateData(Request $request)
    {
        try {


            $subjects = Subjects::find($request->subjectId);
            $subjects->address_confirmed = $request->acflag;
            $subjects->save();

            if ($subjects) {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.my_profile.updated_successfully')
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                ]);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $exception->getMessage(),
            ]);
        }
    }
}
