<?php

namespace App\Http\Controllers;

use App\Cases;
use App\Client;
use App\ClientProduct;
use App\ClientTypes;
use App\ContactTypes;
use App\Country;
use App\DeliveryBoys;
use App\DeliveryboyInvestigations;
use App\InvestigationDocuments;
use App\InvestigationEmail;
use App\InvestigationPhone;
use App\Investigations;
use App\InvestigatorInvestigations;
use App\Mail\EmailInvoice;
use App\Mail\EmailVerify;
use App\Mail\ExtremeDelayEmail;
use App\Mail\InvestigationInvestigator;
use App\DeliveryboyInvoice;
use App\Mail\UpdateInvestigatorInvestigation;
use App\Product;
use App\Specialization;
use App\SubjectAddress;
use App\Subjects;
use App\User;
use App\UserAddress;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use App\Investigators;
use App\AssignedInvestigator;
use App\DocumentTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\AppHelper;
use App\InvestigationTransition;
use App\PerformaInvoice;
use App\InvoiceItems;
use App\Mail\EmailInvestigationApprove;
use App\Mail\EmailInvestigatorInvoice;
use App\Setting;
use App\UserCategories;
use App\SubjectTypes;
use App\InvestigatorInvoice;
use Exception;

use Cookie;
use App\Invoice;
class InvestigationController extends Controller
{

    //For admin /SM
    public function investigationList($status = '', Request $request)
    {
        $data = $request->all();
        // print_r($data['order']);die;
        if (isSM()) {
            $usercats = UserCategories::getUserCategories();
            $investigations = Investigations::with([
                'subjects' => function ($q) {
                    $q->select(['id', 'investigation_id', 'family_name', 'first_name']);
                },
                'product' => function ($q) {
                    $q->select(['id', 'name']);
                }
            ])->whereHas('product', function ($q) use ($usercats) {
                $q->select(['id', 'name', 'category_id'])
                    ->whereIn('category_id',  $usercats);
            });
            
            //->orderBy('created_at', 'desc');
        } else {
            $investigations = Investigations::with([
                'subjects' => function ($q) {
                    $q->select(['id', 'investigation_id', 'family_name', 'first_name']);
                },
                'product' => function ($q) {
                    $q->select(['id', 'name']);
                }
            ]);
            
            //->orderBy('created_at', 'desc');
        }


        if ($status != '') {
            if ($status == 'In Report') {
                $investigations = $investigations->whereIn('status', ['Writing Report', 'Finalizing Report']);
            } else if ($status == 'Printing') {
                $investigations = $investigations->whereIn('status', ['Printing', 'Waiting For Final Approval']);
            } else if ($status == 'Late') {
                $finalInvn = [];
                $openInvns = Investigations::where('status', 'Open')->pluck('id')->toArray();

                if(!empty($openInvns)){
                    $invTrans = InvestigationTransition::whereIn('investigation_id', $openInvns)->whereRaw("created_at >= ( CURDATE() - INTERVAL 4 DAY )")->pluck('investigation_id')->toArray();

                    if(!empty($invTrans)){
                        $finalInvn = array_diff($openInvns, $invTrans);
                    }
                }

                $investigations = $investigations->whereIn('id', $finalInvn);
            } else {
                $checkStatus = [$status];
                if ($status == 'Open') {
                    $checkStatus[] = 'Approved';
                    $checkStatus[] = 'Assigned';
                    $checkStatus[] = 'Opens';
                } else if($status == 'Closed') {
                    $checkStatus[] = 'Closed';
                    $checkStatus[] = 'Declined';
                }
                $investigations = $investigations->whereIn('status', $checkStatus);
            }
        }

        if ($request->filled('investigator_id')) {
            $investigations = $investigations->whereHas('investigators', function ($q) use ($request, $status) {
                $q->where('investigator_id', $request->get('investigator_id'))
                    ->where('status', $status);
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
                if($investigation->product){
                    $productDoc = $investigation->product->documents()->firstWhere('doc_type', 'Investigator Document');
                    if($productDoc && !empty($productDoc->file_name)){

                        $docurl = '/product-documents/'.$productDoc->file_name;

                        $btns .= '<a href="'. URL::asset($docurl) .'" target="_blank" class="dt-btn-action" title="' . trans('form.products_form.Investigator Document') . '">
                                <i class="fas fa-file-download"></i>
                            </a>';
                    }
                    $productDoc = $investigation->product->documents()->firstWhere('doc_type', 'Deliveryboy Document');
                    if($productDoc && !empty($productDoc->file_name)){
                        
                        $docurl = '/product-documents/'.$productDoc->file_name;

                        $btns .= '<a href="'. URL::asset($docurl) .'" target="_blank" class="dt-btn-action" title="' . trans('form.products_form.Deliveryboy Document') . '">
                                <i class="fas fa-file-download"></i>
                            </a>';
                    }
                }

                $btns .= '</div>';

                if(!isClient() || !isInvestigator() || !isDeliveryboy()){
                    $assignment = InvestigatorInvestigations::where('investigation_id', $investigation->id)
                        ->where('status', 'Final Report Submitted')
                        ->orderBy('created_at', 'DESC')->first();
                    $isPerforma = PerformaInvoice::where('investigation_id', $investigation->id)->first();
                    $title = "";
                    if(!is_null($isPerforma)){
                        $title = trans('form.email_tem.ticket_update.view_invoice');
                    } else {
                        $title = trans('form.investigationinvoice.viewandgenerateinvoice');
                    }
                    if (!empty($assignment)) {
                        $btns .= '<a href="' . route('investigation.showinvoice', [Crypt::encrypt($investigation->id)]) . '" class="dt-btn-action" title="' . $title . '">
                                <i class="fas fa-money-bill-wave "></i>
                            </a>';
                    }
                }
                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function investigatorInvestigationList($status = '', Request $request)
    {
        $investigations = InvestigatorInvestigations::with(['investigator', 'investigation', 'investigation.subjects', 'investigation.product'])
            ->whereHas('investigator', function ($q) {
                $q->where('user_id', Auth::id());
            });

        if ($status != '') {
            if ($status == 'Assigned') {
                $investigations = $investigations->whereIn('status', ['Assigned']);
            } else if ($status == 'In Progress') {
                $investigations = $investigations->whereIn('status', ['Investigation Started', 'Returned To Investigator']);
            } else if ($status == 'Report Submitted') {
                $investigations = $investigations->whereIn('status', ['Completed With Findings', 'Completed Without Findings']);
            } else if ($status == 'Report Accepted') {
                $investigations = $investigations->whereIn('status', ['Report Accepted', 'Final Report Submitted']);
            }else if ($status == 'Investigation Declined') {
                $investigations = $investigations->whereIn('status', ['Investigation Declined', 'Report Declined']);
            } else {
                $investigations = $investigations->where('status', $status);
            }
        }

        if ($request->filled('investigator_id')) {
            $investigations = $investigations->whereHas('investigator', function ($q) use ($request, $status) {
                $q->where('id', $request->get('investigator_id'))
                    ->where('status', $status);
            });
        }
        $investigations = $investigations->get();
        return DataTables::of($investigations)
            ->addColumn('user_inquiry', function ($investigations) {
                return !empty($investigations->investigation->user_inquiry) ? $investigations->investigation->user_inquiry : '';
            })
            ->addColumn('ex_file_claim_no', function ($investigations) {
                return !empty($investigations->investigation->ex_file_claim_no) ? $investigations->investigation->ex_file_claim_no : '';
            })
            ->addColumn('claim_number', function ($investigations) {
                return !empty($investigations->investigation->claim_number) ? $investigations->investigation->claim_number : '';
            })
            ->addColumn('work_order_number', function ($investigations) {
                return !empty($investigations->investigation->work_order_number) ? $investigations->investigation->work_order_number : '';
            })
            ->addColumn('product_name', function ($investigations) {
                return !empty($investigations->investigation->product->name) ? $investigations->investigation->product->name : '';
            })
            ->addColumn('first_name', function ($investigations) {
                return !empty($investigations->investigation->subjects[0]) ? $investigations->investigation->subjects[0]->first_name : '';
            })
            ->addColumn('family_name', function ($investigations) {
                return !empty($investigations->investigation->subjects[0]) ? $investigations->investigation->subjects[0]->family_name : '';
            })
            ->addColumn('inquiry', function ($investigation) {
                $clientId = Client::where('user_id', $investigation->investigation['user_id'])->pluck('id')->first();
                $typeofInq =  ClientProduct::with('product:id,name')->whereHas('product')->where('client_id', $clientId)->where('product_id', $investigation->investigation['type_of_inquiry'])->first();

                return !empty($typeofInq) ? $typeofInq->product->name  : '';
            })
            ->editColumn('created_at', function ($investigation) {
                return Carbon::parse($investigation->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($investigation) {
                if ($investigation->status) {
                    if ($investigation->status == 'Return To Center') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Assigned') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Completed') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Not Completed') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Declined') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Report Writing') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Report Submitted') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Completed With Findings') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Completed Without Findings') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Investigation Declined') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else {
                        return '<td>
                            <span class="badge dt-badge badge-primary">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($investigations) {
                $btns = '<div class="action_btn">';
                if ($investigations->investigation->status != 'Investigation Declined') {
                    $btns .= '<a href="' . route('investigation.show', ['id' => Crypt::encrypt($investigations->investigation['id']),'iiid' => Crypt::encrypt($investigations->id)]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="fas fa-file-alt"></i>
                            </a>';
                }

                $productDoc = $investigations->investigation->product->documents()->firstWhere('doc_type', 'Investigator Document');

                if($productDoc && !empty($productDoc->file_name)){
                    $docurl = '/product-documents/'.$productDoc->file_name;

                    $btns .= '<a href="'. URL::asset($docurl) .'" target="_blank" class="dt-btn-action" title="' . trans('general.download_doc') . '">
                                <i class="fas fa-file-download"></i>
                            </a>';
                }

                $btns .= '</div>';

                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function deliveryboyInvestigationList($status = '', Request $request)
    {
        $investigations = DeliveryboyInvestigations::with(['deliveryboy', 'investigation', 'investigation.subjects', 'investigation.product'])
            ->whereHas('deliveryboy', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('deliveryboy_investigations.created_at', 'desc');

        if ($status != '') {
            if ($status == 'In Progress') {
                $investigations = $investigations->whereIn('status', ['Assigned', 'Accepted', 'Return To Center']);
            } else if ($status == 'Done And Not Delivered') {
                $investigations = $investigations->whereIn('status', ['Done And Not Delivered', 'Rejected']);
            } else {
                $investigations = $investigations->where('status', $status);
            }
        }

        if ($request->filled('deliveryboy_id')) {
            $investigations = $investigations->whereHas('deliveryboy', function ($q) use ($request, $status) {
                $q->where('id', $request->get('deliveryboy_id'))
                    ->where('status', $status);
            });
        }

        return DataTables::of($investigations)
            ->addColumn('user_inquiry', function ($investigations) {
                return !empty($investigations->investigation->user_inquiry) ? $investigations->investigation->user_inquiry : '';
            })
            ->addColumn('ex_file_claim_no', function ($investigations) {
                return !empty($investigations->investigation->ex_file_claim_no) ? $investigations->investigation->ex_file_claim_no : '';
            })
            ->addColumn('claim_number', function ($investigations) {
                return !empty($investigations->investigation->claim_number) ? $investigations->investigation->claim_number : '';
            })
            ->addColumn('work_order_number', function ($investigations) {
                return !empty($investigations->investigation->work_order_number) ? $investigations->investigation->work_order_number : '';
            })
            ->addColumn('product_name', function ($investigations) {
                return !empty($investigations->investigation->product->name) ? $investigations->investigation->product->name : '';
            })
            ->addColumn('first_name', function ($investigations) {
                return !empty($investigations->investigation['subjects']) ? $investigations->investigation->subjects[0]->first_name : '';
            })
            ->addColumn('family_name', function ($investigations) {
                return !empty($investigations->investigation['subjects']) ? $investigations->investigation->subjects[0]->family_name : '';
            })
            ->addColumn('inquiry', function ($investigation) {
                $clientId = Client::where('user_id', $investigation->investigation['user_id'])->pluck('id')->first();
                $typeofInq =  ClientProduct::with('product:id,name')->whereHas('product')->where('client_id', $clientId)->where('product_id', $investigation->investigation['type_of_inquiry'])->first();

                return !empty($typeofInq) ? $typeofInq->product->name : '';
            })
            ->editColumn('created_at', function ($investigation) {
                return Carbon::parse($investigation->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($investigation) {
                if ($investigation->status) {
                    if ($investigation->status == 'Return To Center') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Assigned') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Done And Delivered') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Done And Not Delivered') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Report Writing') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Report Submitted') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else {
                        return '<td>
                            <span class="badge dt-badge badge-primary">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($investigation) {
                $btns = '<div class="action_btn">';

                $btns .= '<a href="' . route('investigation.show', [Crypt::encrypt($investigation->investigation['id'])]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="fas fa-file-alt"></i>
                            </a>';

                $productDoc = $investigation->investigation->product->documents()->firstWhere('doc_type', 'Deliveryboy Document');

                if($productDoc && !empty($productDoc->file_name)){
                    $docurl = '/product-documents/'.$productDoc->file_name;

                    $btns .= '<a href="'. URL::asset($docurl) .'" target="_blank" class="dt-btn-action" title="' . trans('general.download_doc') . '">
                                <i class="fas fa-file-download"></i>
                            </a>';
                }

                $btns .= '</div>';

                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    //Investigation list for client
    public function clientInvestigationList($status = '', Request $request)
    {
        $investigations = Investigations::with([
            'subjects' => function ($q) {
                $q->select(['id', 'investigation_id', 'family_name', 'first_name']);
            },
            'product' => function ($q) {
                $q->select(['id', 'name']);
            },
        ])->where('user_id', Auth::id());

        if ($status != '') {
            if ($status == 'Waiting') {
                $investigations = $investigations->whereIn('status', ['Pending Approval']);
            }

            if ($status == 'In Progress') {
                $investigations = $investigations->whereIn('status', ['Approved', 'Open', 'In Progress', 'Writing Report', 'Finalizing Report', 'Printing', 'Waiting For Final Approval', 'Send To Client']);
            }

            if ($status == 'Closed') {
                $investigations = $investigations->whereIn('status', ['Closed', 'Declined']);
            }
        }

        return DataTables::of($investigations)
            ->addColumn('product_name', function ($investigations) {
                return !empty($investigations->product->name) ? $investigations->product->name : '';
            })
            ->addColumn('first_name', function ($investigations) {
                return !$investigations->subjects->isEmpty() ? $investigations->subjects[0]->first_name : '';
            })
            ->addColumn('family_name', function ($investigations) {
                return !$investigations->subjects->isEmpty() ? $investigations->subjects[0]->family_name : '';
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
                    if ($investigation->status == 'Pending Approval') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Declined') {
                        return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if ($investigation->status == 'Investigation Started') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    } else if($investigation->status == 'Closed') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($investigation->status)) . '</span>
                        </td>';
                    }else {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.In Progress') . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($investigation) {
                $btns = '<div class="action_btn">';
                if (($investigation->status == 'Pending Approval')) {
                    $btns .= '<a href="' . route('investigation.edit', [Crypt::encrypt($investigation->id)]) . '" class="dt-btn-action" title="' . trans('general.edit') . '">
                                <i class="fas fa-edit"></i>
                            </a>';
                    if($investigation->created_by == Auth::id()){
                        $btns .= '<a href="javascript:void(0)" id="deleteInvestigation" class="dt-btn-action" title="' . trans('general.delete') . '" data-id="' . $investigation->id . '">
                                <i class="fas fa-trash"></i>
                            </a>';
                    }
                }
                $btns .= '<a href="javascript:void(0)" id="duplicateInvestigation" class="dt-btn-action" title="' . trans('general.duplicate') . '" data-id="' . Crypt::encrypt($investigation->id) . '">
                <i class="fas fa-clone" ></i>
            </a>';
                $btns .= '<a href="' . route('investigation.show', [Crypt::encrypt($investigation->id)]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="fas fa-file-alt"></i>
                            </a>';

                if (count($investigation->clientinvoice) > 0) {
                    $btns .= '<a href="' . route('invoice.show', [Crypt::encrypt($investigation->clientinvoice->first()->id), 'pinvoice']) . '" class="dt-btn-action" title="' . trans('form.investigationinvoice.view_invoice') . '">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>';
                }
                $btns .= '</div>';

                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countsRaw = $this->getStatusWiseCounts()->getContent();
        $counts = json_decode($countsRaw, true);

        $invCounts = isset($counts['data']) && !empty($counts['data']) ? $counts['data'] : [];

        if (isInvestigator()) {
            return view('investigation.investigator.index', compact('invCounts'));
        } else if (isDeliveryboy()) {
            return view('investigation.deliveryboy.index', compact('invCounts'));
        } else if (isClient()) {
            return view('investigation.client.index', compact('invCounts'));
        } else {
            return view('investigation.index', compact('invCounts'));
        }
    }

    public function create()
    {
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $clienttypes = ClientTypes::select('type_name', 'id', 'hr_type_name')->orderBy('id', 'ASC')->get();
        // $subtypes = ['Main', 'Spouse', 'Company', 'Other'];
        $subtypes = SubjectTypes::select('name','hr_name')->get()->toArray();
        
        $won = 'WON' . AppHelper::genrateInvestigationNumber("\App\Investigations");
        //date('isymHd');
        $clients = null;
        $products = null;
        $customers = null;

        if (isAdmin() || isSM()) {

            $clients = User::where('type_id', 2)->where('status', 'approved')->pluck('name', 'id');
        }

        if (isClient()) {
            $clientId = Client::where('user_id', Auth::user()->id)->pluck('id')->first();

            $customers = DB::table('client_customers')
                ->select('users.name', 'client_customers.customer_id as id')
                ->leftjoin('users', 'users.id', '=', 'client_customers.customer_id')

                ->where('client_customers.client_id', $clientId)
                ->where('client_customers.deleted_at', NULL)
                ->get();

            $firstcustomer = $customers->first();

            if (!empty($firstcustomer)) {
                $firstclientId = Client::where('user_id', $firstcustomer->id)->pluck('id')->first();
                $products = ClientProduct::with('product:id,name,is_delivery,delivery_cost,spouse_cost')->whereHas('product')->where('client_id', $firstclientId)->where('price', '>', 0)->get();
            }
        }


        return view('investigation.create', compact('countries', 'won', 'contacttypes', 'clienttypes', 'clients', 'products', 'subtypes', 'customers'));
    }


    public function show(Request $request,$id,$iiid = '')
    {
        $invId = Crypt::decrypt($id);
        
        // $IIId = (!empty($request->q) ? Crypt::decrypt(str_replace('/investigations/'.$id.'?iiid=', '',$request->q) ) : '');
        $IIId = (!empty($iiid) ? Crypt::decrypt($iiid) : '');

        $countries = Country::select('id', 'en_name')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $clienttypes = ClientTypes::select('type_name', 'id', 'hr_type_name')->orderBy('id', 'ASC')->get();
        $doctypes = DocumentTypes::select('id', 'name', 'hr_name')->orderBy('id', 'ASC')->get();
        $invoice = PerformaInvoice::where('investigation_id', $invId)->where('status', 'paid')->first();
        $clients = null;
        $products = null;

        if (Auth::user()->type_id == 1) {
            $clients = User::where('type_id', 2)->where('status', 'approved')->pluck('name', 'id');
        }

        if (Auth::user()->type_id == 2) {
            $clientId = AppHelper::getClientIdFromUserId(Auth::user()->id);
            $products = ClientProduct::with('product:id,name,is_delivery,delivery_cost')->whereHas('product')->where('client_id', $clientId)->where('price', '>', 0)->get();
        }

        $invn = Investigations::find($invId);

        $clientId = AppHelper::getClientIdFromUserId($invn->user_id);
        $typeofInq = ClientProduct::with('product:id,name,is_delivery,delivery_cost')->where('client_id', $clientId)->where('product_id', $invn->type_of_inquiry)->first();

        $phones = $mobiles = $faxes = $specs = [];

        foreach ($invn->phones as $phone) {
            if ($phone->type == 'phone') {
                $phones[] = $phone;
            } else if ($phone->type == 'mobile') {
                $mobiles[] = $phone;
            } else {
                $faxes[] = $phone;
            }
        }
        $transitions = [];

        foreach ($invn->transitions as $transition) {
            $transitions[] = InvestigationTransition::getTransionData($transition->id);
        }

        $assigned = InvestigatorInvestigations::with(['investigation', 'investigator'])
            ->where('investigation_id', $invId)
            ->when((!blank($IIId)), function ($q) use($IIId) {
                return $q->where('id', $IIId);
            })
            ->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined'])
            ->orderBy('created_at', 'ASC')->get();

        $assignedDel = DeliveryboyInvestigations::with(['investigation', 'deliveryboy'])
            ->where('investigation_id', $invId)
            ->whereNotIn('status', ['Rejected', 'Done And Not Delivered'])
            ->orderBy('created_at', 'ASC')->get();
        
        return view('investigation.show', compact('countries', 'contacttypes', 'clienttypes', 'clients', 'products', 'invn', 'phones', 'mobiles', 'faxes', 'typeofInq', 'assigned', 'transitions', 'assignedDel', 'doctypes', 'invoice','IIId','request'));
    }

    public function investigationValidator(array $data)
    {
        return Validator::make($data, [
            'user_inquiry' => ['required', 'string', 'max:255'],
            'paying_customer' => ['required', 'string'],
            'type_of_inquiry' => ['required', 'string'],
        ]);
    }

    public function store(Request $request)
    {
        $this->investigationValidator($request->all())->validate();

        $finalInvCost = AppHelper::calculateInvestigationCost($request->all());
        $userId = isAdmin() || isSM() && !empty($request->user_inquiry) ? explode('-', $request->user_inquiry)[0] : $request->user_id;
        $avalCredit = AppHelper::getUsersAvailableCredit($userId);

        if ($avalCredit < $finalInvCost) {
            return response()->json([
                'status' => 'error',
                'data' => ["avalCredit" => $avalCredit, "finalInvCost" => $finalInvCost, "diffrence" => $finalInvCost - $avalCredit],
                //                'message' => trans('form.investigation.your_current_credit') .' '. $avalCredit .'. '.
                //                    trans('form.investigation.you_need_credit').' '. $finalInvCost .'. '.
                //                    trans('form.investigation.contact_admin').'.',
                'message' => trans('general.credit_limit_message'),
            ]);
        }

        try {
            DB::beginTransaction();

            $invData = Investigations::create([
                'user_id' => isAdmin() || isSM() && !empty($request->user_inquiry) ? explode('-', $request->user_inquiry)[0] : $request->user_id,
                'work_order_number' => $request->work_order_number,
                'user_inquiry' => isAdmin() || isSM() && !empty($request->user_inquiry) ? explode('-', $request->user_inquiry)[1] : $request->user_inquiry,
                'paying_customerid' => $request->paying_customer,
                'ex_file_claim_no' => $request->ex_file_claim_no,
                'claim_number' => $request->claim_number,
                'type_of_inquiry' => $request->type_of_inquiry,
                'make_paste' => $request->product_isdel == 'yes' && $request->company_del == '1' && $request->make_paste == '1' ? 1 : 0,
                'deliver_by_manager' => $request->product_isdel == 'yes' && $request->company_del == '1' && $request->deliver_by_manager == '1' ? 1 : 0,
                'company_del' => $request->product_isdel == 'yes' && $request->company_del == '1' ? 1 : 0,
                'personal_del' => $request->product_isdel == 'yes' && $request->personal_del == '1' ? 1 : 0,
                'status' => 'Pending Approval',
                'status_hr' => trans('form.timeline_status.Pending Approval', [], 'hr'),
                'created_by' => Auth::id(),
                'inv_cost' => $finalInvCost,
            ]);
            $investigationId = $invData->id;
            if ($request->filled('subjects')) {
                foreach ($request->input('subjects') as $subject) {
                    $subjectAddresses = isset($subject['address']) ? $subject['address'] : [];
                    unset($subject['address']);
                    $subject['sub_type'] = (($subject['sub_type'] == 'Other' || $subject['sub_type'] == 'Contact') && $subject['other_text'] != '') ? $subject['other_text'] : $subject['sub_type'];
                    unset($subject['other_text']);
                    $subject['investigation_id'] = $invData->id;
                    $subject['main_phone'] = preg_replace('/\D/', '', $subject['main_phone']);
                    $subject['secondary_phone'] = preg_replace('/\D/', '', $subject['secondary_phone']);
                    $subject['main_mobile'] = preg_replace('/\D/', '', $subject['main_mobile']);
                    $subject['secondary_mobile'] = preg_replace('/\D/', '', $subject['secondary_mobile']);
                    $subject['fax'] = preg_replace('/\D/', '', $subject['fax']);
                    $subject['address_confirmed'] = isset($subject['address_confirmed']) ? 1 : 0;

                    $subData = Subjects::create($subject);

                    $arr_address = [];
                    if (count($subjectAddresses) > 0) {
                        foreach ($subjectAddresses as $address) {
                            $address['subject_id'] = $subData->id;
                            $address['address_type'] = (($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
                            unset($address['other_text']);
                            $arr_address[] = $address;
                        }
                        SubjectAddress::insert($arr_address);
                    }
                }
            }

            $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];

            if ($request->filled('otheremail')) {
                foreach ($request->input('otheremail') as $value) {
                    $value['investigation_id'] = $invData->id;
                    $value['email_type'] = (($value['email_type'] == 'Other' || $value['email_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['email_type'];
                    $value['value'] = $value['email'];
                    unset($value['other_text']);
                    unset($value['email']);
                    $arr_email[] = $value;
                }
                InvestigationEmail::insert($arr_email);
            }

            if ($request->filled('otherphone')) {
                foreach ($request->input('otherphone') as $value) {
                    $value['investigation_id'] = $invData->id;
                    $value['phone_type'] = (($value['phone_type'] == 'Other' || $value['phone_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['phone_type'];
                    $value['type'] = 'phone';
                    $value['value'] = $value['phone'];
                    unset($value['other_text']);
                    unset($value['phone']);
                    $arr_phones[] = $value;
                }
                InvestigationPhone::insert($arr_phones);
            }

            if ($request->filled('othermobile')) {
                foreach ($request->input('othermobile') as $value) {
                    $value['investigation_id'] = $invData->id;
                    $value['phone_type'] = (($value['mobile_type'] == 'Other' || $value['mobile_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['mobile_type'];
                    $value['type'] = 'mobile';
                    $value['value'] = $value['mobile'];
                    unset($value['other_text']);
                    unset($value['mobile_type']);
                    unset($value['mobile']);
                    $arr_mobile[] = $value;
                }
                InvestigationPhone::insert($arr_mobile);
            }

            if ($request->filled('otherfax')) {
                foreach ($request->input('otherfax') as $value) {
                    $value['investigation_id'] = $invData->id;
                    $value['phone_type'] = (($value['fax_type'] == 'Other' || $value['fax_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['fax_type'];
                    $value['type'] = 'fax';
                    $value['value'] = $value['fax'];
                    unset($value['other_text']);
                    unset($value['fax_type']);
                    unset($value['fax']);
                    $arr_fax[] = $value;
                }
                InvestigationPhone::insert($arr_fax);
            }
            $invtransdata = [
                'event' => 'investigation_create',
                'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'created_by'))),
            ];
            InvestigationTransition::addTransion($invtransdata, $investigationId);

            DB::commit();

            //            return redirect('/investigations')->with('success', trans('form.registration.investigation.new_investigation_added'));
            return response()->json([
                'status' => 'success',
                'data' => ['id' => Crypt::encrypt($investigationId)],
                'message' => trans('form.registration.investigation.new_investigation_added'),
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();

            //            return redirect('/investigations')->with('error', trans('general.something_wrong') . ' ' . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => trans('general.something_wrong') . ' ' . $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $invId = Crypt::decrypt($id);
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $clienttypes = ClientTypes::select('type_name', 'id', 'hr_type_name')->orderBy('id', 'ASC')->get();
        // $subtypes = ['Main', 'Spouse', 'Company', 'Other'];
        $subtypes = SubjectTypes::select('name','hr_name')->get()->toArray();

        $clients = null;
        $products = null;
        $customers = null;

        if (isAdmin() || isSM()) {
            $clients = User::where('type_id', 2)->where('status', 'approved')->pluck('name', 'id');
        }

        if (isClient()) {
            $clientId = Client::where('user_id', Auth::user()->id)->pluck('id')->first();
            $products = ClientProduct::with('product:id,name,price,is_delivery,delivery_cost,spouse_cost')->whereHas('product')->where('client_id', $clientId)->where('price', '>', 0)->get();

            $customers = DB::table('client_customers')
                ->select('users.name', 'client_customers.customer_id as id')
                ->leftjoin('users', 'users.id', '=', 'client_customers.customer_id')

                ->where('client_customers.client_id', $clientId)
                ->where('client_customers.deleted_at', NULL)
                ->get();
        }

        $invn = Investigations::find($invId);

        $phones = $mobiles = $faxes = $specs = [];

        foreach ($invn->phones as $phone) {
            if ($phone->type == 'phone') {
                $phones[] = $phone;
            } else if ($phone->type == 'mobile') {
                $mobiles[] = $phone;
            } else {
                $faxes[] = $phone;
            }
        }

        return view('investigation.edit', compact('countries', 'contacttypes', 'clienttypes', 'clients', 'products', 'invn', 'phones', 'mobiles', 'faxes', 'subtypes', 'customers'));
    }

    // this function is for duplicate investigation
    public function duplicate($id)
    {
        $invId = Crypt::decrypt($id);
        $won = 'WON' . AppHelper::genrateInvestigationNumber("\App\Investigations");
        //date('isymHd');
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $clienttypes = ClientTypes::select('type_name', 'id', 'hr_type_name')->orderBy('id', 'ASC')->get();
        // $subtypes = ['Main', 'Spouse', 'Company', 'Other'];
        $subtypes = SubjectTypes::select('name','hr_name')->get()->toArray();

        $clients = null;
        $products = null;
        $customers = null;

        if (isAdmin() || isSM()) {
            $clients = User::where('type_id', 2)->where('status', 'approved')->pluck('name', 'id');
        }

        if (isClient()) {
            $clientId = Client::where('user_id', Auth::user()->id)->pluck('id')->first();
            $products = ClientProduct::with('product:id,name,price,is_delivery,delivery_cost')->whereHas('product')->where('client_id', $clientId)->where('price', '>', 0)->get();

            $customers = DB::table('client_customers')
                ->select('users.name', 'client_customers.customer_id as id')
                ->leftjoin('users', 'users.id', '=', 'client_customers.customer_id')

                ->where('client_customers.client_id', $clientId)
                ->where('client_customers.deleted_at', NULL)
                ->get();
        }

        $invn = Investigations::find($invId);

        $phones = $mobiles = $faxes = $specs = [];

        foreach ($invn->phones as $phone) {
            if ($phone->type == 'phone') {
                $phones[] = $phone;
            } else if ($phone->type == 'mobile') {
                $mobiles[] = $phone;
            } else {
                $faxes[] = $phone;
            }
        }

        return view('investigation.duplicate', compact('countries', 'contacttypes', 'clienttypes', 'clients', 'products', 'invn', 'phones', 'mobiles', 'faxes', 'subtypes', 'customers', 'won'));
    }

    public function update(Request $request, $id)
    {
        $this->investigationValidator($request->all())->validate();
        $invId = $id;

        $finalInvCost = AppHelper::calculateInvestigationCost($request->all());
        $userId = isAdmin() || isSM() && !empty($request->user_inquiry) ? explode('-', $request->user_inquiry)[0] : $request->user_id;
        $avalCredit = AppHelper::getUsersAvailableCredit($userId, $invId);

        if ($avalCredit < $finalInvCost) {
            return response()->json([
                'status' => 'error',
                'data' => ["avalCredit" => $avalCredit, "finalInvCost" => $finalInvCost, "diffrence" => $finalInvCost - $avalCredit],
                //                'message' => trans('form.investigation.your_current_credit') .' '. $avalCredit .'. '.
                //                    trans('form.investigation.you_need_credit').' '. $finalInvCost .'. '.
                //                    trans('form.investigation.contact_admin').'.',
                'message' => trans('general.credit_limit_message'),
            ]);
        }

        try {
            DB::beginTransaction();

            $investigation = Investigations::with('subjects')->find($invId);

            $invData = $investigation->update([
                'user_id' => isAdmin() || isSM() && !empty($request->user_inquiry) ? explode('-', $request->user_inquiry)[0] : $request->user_id,
                'user_inquiry' => isAdmin() || isSM() && !empty($request->user_inquiry) ? explode('-', $request->user_inquiry)[1] : $request->user_inquiry,
                'paying_customerid' => $request->paying_customer,
                'ex_file_claim_no' => $request->ex_file_claim_no,
                'claim_number' => $request->claim_number,
                'type_of_inquiry' => $request->type_of_inquiry,
                'make_paste' => $request->product_isdel == 'yes' && $request->company_del == '1' && $request->make_paste == '1' ? 1 : 0,
                'deliver_by_manager' => $request->product_isdel == 'yes' && $request->company_del == '1' && $request->deliver_by_manager == '1' ? 1 : 0,
                'company_del' => $request->product_isdel == 'yes' && $request->company_del == '1' ? 1 : 0,
                'personal_del' => $request->product_isdel == 'yes' && $request->personal_del == '1' ? 1 : 0,
                'inv_cost' => $finalInvCost,
            ]);

            if ($request->filled('subjects')) {

                $subjectIds = Subjects::where('investigation_id', $invId)->pluck('id')->toArray();
                SubjectAddress::whereIn('subject_id', $subjectIds)->delete();

                Subjects::where('investigation_id', $invId)->delete();

                foreach ($request->input('subjects') as $subject) {

                    $subjectAddresses = isset($subject['address']) ? $subject['address'] : [];
                    unset($subject['address']);
                    $subject['sub_type'] = (($subject['sub_type'] == 'Other' || $subject['sub_type'] == 'Contact') && $subject['other_text'] != '') ? $subject['other_text'] : $subject['sub_type'];
                    unset($subject['other_text']);
                    $subject['investigation_id'] = $invId;
                    $subject['main_phone'] = preg_replace('/\D/', '', $subject['main_phone']);
                    $subject['secondary_phone'] = preg_replace('/\D/', '', $subject['secondary_phone']);
                    $subject['main_mobile'] = preg_replace('/\D/', '', $subject['main_mobile']);
                    $subject['secondary_mobile'] = preg_replace('/\D/', '', $subject['secondary_mobile']);
                    $subject['fax'] = preg_replace('/\D/', '', $subject['fax']);
                    $subject['address_confirmed'] = isset($subject['address_confirmed']) ? 1 : 0;
                    $subData = Subjects::create($subject);

                    $arr_address = [];
                    if (count($subjectAddresses) > 0) {
                        foreach ($subjectAddresses as $address) {
                            $address['subject_id'] = $subData->id;
                            $address['address_type'] = (($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
                            unset($address['other_text']);
                            $arr_address[] = $address;
                        }
                        SubjectAddress::insert($arr_address);
                    }
                }
            }

            $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];

            if ($request->filled('otheremail')) {
                InvestigationEmail::where('investigation_id', $invId)->delete();

                foreach ($request->input('otheremail') as $value) {
                    $value['investigation_id'] = $invId;
                    $value['email_type'] = (($value['email_type'] == 'Other' || $value['email_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['email_type'];
                    $value['value'] = $value['email'];
                    unset($value['other_text']);
                    unset($value['email']);
                    $arr_email[] = $value;
                }
                InvestigationEmail::insert($arr_email);
            }

            if ($request->filled('otherphone')) {
                InvestigationPhone::where('investigation_id', $invId)->where('type', 'phone')->delete();

                foreach ($request->input('otherphone') as $value) {
                    $value['investigation_id'] = $invId;
                    $value['phone_type'] = (($value['phone_type'] == 'Other' || $value['phone_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['phone_type'];
                    $value['type'] = 'phone';
                    $value['value'] = $value['phone'];
                    unset($value['other_text']);
                    unset($value['phone']);
                    $arr_phones[] = $value;
                }
                InvestigationPhone::insert($arr_phones);
            }

            if ($request->filled('othermobile')) {
                InvestigationPhone::where('investigation_id', $invId)->where('type', 'mobile')->delete();

                foreach ($request->input('othermobile') as $value) {
                    $value['investigation_id'] = $invId;
                    $value['phone_type'] = (($value['mobile_type'] == 'Other' || $value['mobile_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['mobile_type'];
                    $value['type'] = 'mobile';
                    $value['value'] = $value['mobile'];
                    unset($value['other_text']);
                    unset($value['mobile_type']);
                    unset($value['mobile']);
                    $arr_mobile[] = $value;
                }
                InvestigationPhone::insert($arr_mobile);
            }

            if ($request->filled('otherfax')) {
                InvestigationPhone::where('investigation_id', $invId)->where('type', 'fax')->delete();

                foreach ($request->input('otherfax') as $value) {
                    $value['investigation_id'] = $invId;
                    $value['phone_type'] = (($value['fax_type'] == 'Other' || $value['fax_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['fax_type'];
                    $value['type'] = 'fax';
                    $value['value'] = $value['fax'];
                    unset($value['other_text']);
                    unset($value['fax_type']);
                    unset($value['fax']);
                    $arr_fax[] = $value;
                }
                InvestigationPhone::insert($arr_fax);
            }

            $invtransdata = [
                'event' => 'investigation_update',
                'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'updated_by'))),
            ];
            InvestigationTransition::addTransion($invtransdata, $invId);


            DB::commit();

            //            return redirect('/investigations')->with('success', trans('form.registration.investigation.investigation_updated'));
            return response()->json([
                'status' => 'success',
                'data' => [],
                'message' => trans('form.registration.investigation.investigation_updated'),
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();

            //            return redirect('/investigations')->with('error', trans('general.something_wrong') . ' ' . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => trans('general.something_wrong') . ' ' . $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $invtransdata = [
                'event' => 'investigation_delete',
                'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'deleted_by'))),
            ];
            InvestigationTransition::addTransion($invtransdata, $id);

            //Remove all relational entries
            $subjectIds = Subjects::where('investigation_id', $id)->pluck('id')->toArray();
            SubjectAddress::whereIn('subject_id', $subjectIds)->delete();
            Subjects::where('investigation_id', $id)->delete();
            InvestigatorInvestigations::where('investigation_id', $id)->delete();
            InvestigationDocuments::where('investigation_id', $id)->delete();
            //Remove all relational entries
            Investigations::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.registration.investigation.investigation_deleted'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function deleteMultiple(Request $request)
    {

        try {
            if (!empty($request->ids)) {
                foreach ($request->ids as $id) {
                    $invtransdata = [
                        'event' => 'investigation_delete',
                        'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'deleted_by'))),
                    ];
                    InvestigationTransition::addTransion($invtransdata, $id);

                    //Remove all relational entries
                    $subjectIds = Subjects::where('investigation_id', $id)->pluck('id')->toArray();
                    SubjectAddress::whereIn('subject_id', $subjectIds)->delete();
                    Subjects::where('investigation_id', $id)->delete();
                    InvestigatorInvestigations::where('investigation_id', $id)->delete();
                    InvestigationDocuments::where('investigation_id', $id)->delete();
                    //Remove all relational entries
                }
                Investigations::whereIn('id', $request->ids)->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.investigation_deleted'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            if (!empty($request->id) && !empty($request->action)) {
                $user = User::find($request->id);

                $user->status = $request->action;
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function bulkChangeStatus(Request $request)
    {
        try {
            if (!empty($request->ids)) {
                Investigations::whereIn('id', $request->ids)->where('status', '!=', 'Closed')->update(['status' => $request->status, 'status_hr' => trans('form.timeline_status.'.$request->status, [], 'hr')]);
                if($request->status == "Approved"){
                    Investigations::whereIn('id', $request->ids)->where('status', '!=', 'Closed')->update(['approve_date' => now(), 'approval_at' => now(), 'approved_by' => Auth::id()]);
                }
                foreach ($request->ids as $id) {
                    $invtransdata = [
                        'event' => 'investigation_changestatus',
                        'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'changed_by'))),
                    ];
                    InvestigationTransition::addTransion($invtransdata, $id);
                }
                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function getStatusWiseCounts()
    {
        if (isInvestigator()) {
            $allCountsQuery = DB::table('investigator_investigations')->selectRaw('count(investigator_investigations.id) as total')->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id());
            $countsQuery = DB::table('investigator_investigations')->selectRaw('count(investigator_investigations.id) as total, status')->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->groupBy('status');

            $allCounts = $allCountsQuery->pluck('total')->first();
            $counts = $countsQuery->get()->toArray();
            $invCounts = !empty($counts) ? array_column($counts, 'total', 'status') : [];

            $invCounts['All'] = $allCounts;
            $invCounts['Assigned'] = isset($invCounts['Assigned']) ? $invCounts['Assigned'] : 0;
            $invCounts['In Progress'] = (isset($invCounts['Returned To Investigator']) ? $invCounts['Returned To Investigator'] : 0) + (isset($invCounts['Investigation Started']) ? $invCounts['Investigation Started'] : 0);
            $invCounts['Report Submitted'] = (isset($invCounts['Completed With Findings']) ? $invCounts['Completed With Findings'] : 0) + (isset($invCounts['Completed Without Findings']) ? $invCounts['Completed Without Findings'] : 0);
            $invCounts['Report Accepted'] = (isset($invCounts['Report Accepted']) ? $invCounts['Report Accepted'] : 0) + (isset($invCounts['Final Report Submitted']) ? $invCounts['Final Report Submitted'] : 0);
            $invCounts['Investigation Declined'] = (isset($invCounts['Investigation Declined']) ? $invCounts['Investigation Declined'] : 0) + (isset($invCounts['Report Declined']) ? $invCounts['Report Declined'] : 0);

        } else if (isDeliveryboy()) {
            $allCountsQuery = DB::table('deliveryboy_investigations')->selectRaw('count(deliveryboy_investigations.id) as total')->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id());
            $countsQuery = DB::table('deliveryboy_investigations')->selectRaw('count(deliveryboy_investigations.id) as total, status')->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id())->groupBy('status');

            $allCounts = $allCountsQuery->pluck('total')->first();
            $counts = $countsQuery->get()->toArray();
            $invCounts = !empty($counts) ? array_column($counts, 'total', 'status') : [];

            $invCounts['All'] = $allCounts;
            $invCounts['In Progress'] = (isset($invCounts['Assigned']) ? $invCounts['Assigned'] : 0) + (isset($invCounts['Accepted']) ? $invCounts['Accepted'] : 0) + (isset($invCounts['Return To Center']) ? $invCounts['Return To Center'] : 0);
            $invCounts['Delivered'] = isset($invCounts['Done And Delivered']) ? $invCounts['Done And Delivered'] : 0;
            $invCounts['Not Delivered'] = (isset($invCounts['Rejected']) ? $invCounts['Rejected'] : 0) + (isset($invCounts['Done And Not Delivered']) ? $invCounts['Done And Not Delivered'] : 0);

        } else if (isClient()) {
            $allCountsQuery = DB::table('investigations')->selectRaw('count(id) as total')->where('user_id', Auth::id())->whereNull('deleted_at');
            $countsQuery = DB::table('investigations')->selectRaw('count(id) as total, status')->where('user_id', Auth::id())->whereNull('deleted_at')->groupBy('status');

            $allCounts = $allCountsQuery->pluck('total')->first();
            $counts = $countsQuery->get()->toArray();
            $invCounts = !empty($counts) ? array_column($counts, 'total', 'status') : [];

            $invCounts['All'] = $allCounts;
            $invCounts['Waiting'] = (isset($invCounts['Pending Approval']) ? $invCounts['Pending Approval'] : 0);
            $invCounts['InProgress'] = (isset($invCounts['Approved']) ? $invCounts['Approved'] : 0)
                + (isset($invCounts['Open']) ? $invCounts['Open'] : 0)
                + (isset($invCounts['In Progress']) ? $invCounts['In Progress'] : 0)
                + (isset($invCounts['Writing Report']) ? $invCounts['Writing Report'] : 0)
                + (isset($invCounts['Finalizing Report']) ? $invCounts['Finalizing Report'] : 0)
                + (isset($invCounts['Printing']) ? $invCounts['Printing'] : 0)
                + (isset($invCounts['Waiting For Final Approval']) ? $invCounts['Waiting For Final Approval'] : 0)
                + (isset($invCounts['Send To Client']) ? $invCounts['Send To Client'] : 0)
                + (isset($invCounts['Delivered']) ? $invCounts['Delivered'] : 0);
            $invCounts['Closed'] = (isset($invCounts['Closed']) ? $invCounts['Closed'] : 0) + (isset($invCounts['Declined']) ? $invCounts['Declined'] : 0);
        } else {
            if (isSM()) {
                $usercats = UserCategories::getUserCategories();
                $allCountsQuery = DB::table('investigations')->selectRaw('count(investigations.id) as total')->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->whereNull('investigations.deleted_at');
                $countsQuery = DB::table('investigations')->selectRaw('count(investigations.id) as total, investigations.status')->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->whereNull('investigations.deleted_at')->groupBy('investigations.status');
                $assignmentCountQuery = DB::table('investigator_investigations')->selectRaw('count(investigator_investigations.id) as total, investigator_investigations.status')->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->whereNull('investigations.deleted_at')->groupBy('investigator_investigations.status');
            } else {
                $allCountsQuery = DB::table('investigations')->selectRaw('count(id) as total')->whereNull('deleted_at');
                $countsQuery = DB::table('investigations')->selectRaw('count(id) as total, status')->whereNull('deleted_at')->groupBy('status');
                $assignmentCountQuery = DB::table('investigator_investigations')->selectRaw('count(id) as total, status')->groupBy('status');
            }

            if (!isAdmin() && !isSM()) {
                if (isClient()) {
                    $allCountsQuery = $allCountsQuery->where('user_id', Auth::id());
                    $countsQuery = $countsQuery->where('user_id', Auth::id());
                }
            }

            $allCounts = $allCountsQuery->pluck('total')->first();
            $counts = $countsQuery->get()->toArray();
            $assignCounts = $assignmentCountQuery->get()->toArray();

            if (!empty($counts) && !empty($assignCounts)) {
                $finalCounts = array_merge($counts, $assignCounts);
            } else if (!empty($counts)) {
                $finalCounts = $counts;
            } else if (!empty($assignCounts)) {
                $finalCounts = $assignCounts;
            }

            $invCounts = !empty($finalCounts) ? array_column($finalCounts, 'total', 'status') : [];
            $invCounts['All'] = $allCounts;

            $invCounts['Open'] = (isset($invCounts['Approved']) ? $invCounts['Approved'] : 0) + (isset($invCounts['Open']) ? $invCounts['Open'] : 0);
            $invCounts['In Progress'] = isset($invCounts['In Progress']) ? $invCounts['In Progress'] : 0;
            $invCounts['In Report'] = (isset($invCounts['Writing Report']) ? $invCounts['Writing Report'] : 0) + (isset($invCounts['Finalizing Report']) ? $invCounts['Finalizing Report'] : 0);
            $invCounts['Printing'] = (isset($invCounts['Printing']) ? $invCounts['Printing'] : 0) + (isset($invCounts['Waiting For Final Approval']) ? $invCounts['Waiting For Final Approval'] : 0);
            $invCounts['Closed'] = (isset($invCounts['Closed']) ? $invCounts['Closed'] : 0) + (isset($invCounts['Declined']) ? $invCounts['Declined'] : 0);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $invCounts
        ]);
    }

    public function uploadDocument(Request $request)
    {
        //        $request->validate([
        //            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        //        ]);

        $fileModel = new InvestigationDocuments();
        $result = [];
        $fileId = 0;

        if ($request->hasfile('file')) {
            $filePath = public_path('investigation-documents');

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $file = $request->file('file');

            $ext = $file->extension();
            $size = $file->getSize();
            $fileName = time() . '-' . trim($file->getClientOriginalName());
            $originalName = $file->getClientOriginalName();

            try {

                $file->move($filePath . '/', $fileName);

                $fileModel->investigation_id = $request->investigation_id;
                $fileModel->doc_name = $originalName;
                $fileModel->file_name = $fileName;
                $fileModel->file_path = $filePath . '/' . $fileName;
                $fileModel->file_extension = $ext;
                $fileModel->file_size = $size;
                $fileModel->uploaded_by = $request->uploaded_by;

                $fileModel->save();
                $fileModel->load('uploadedby.user_type');
                $result = $fileModel->toArray();
                $result['file_url'] = URL::asset('/investigation-documents/' . $result['file_name']);
                $fileId = $fileModel->id;

                $invtransdata = [
                    'event' => 'investigation_documentupload',
                    'data' => json_encode(array('data' => array('id' =>  $fileId, 'type' => 'documentupload'))),
                ];
                InvestigationTransition::addTransion($invtransdata, $request->investigation_id);

            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => array_merge(["fileId" => $fileId], $result)]);
    }

    public function updateDocumentType(Request $request)
    {
        if($request->id){
            $doctype = DocumentTypes::find($request->doctype);
            $invdoc = InvestigationDocuments::find($request->id);
            $invdoc->document_typeid = $request->doctype;
            $invdoc->price = $doctype->price;
            $invdoc->doc_name = $request->docname;
            $invdoc->save();
            return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => $request->id]]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
            ]);
        }

    }
    // change the IS Payment Document for the investigator documents
    public function updateIsPaymentDocument(Request $request)
    {
        try {
            $invdoc = InvestigationDocuments::find($request->docId);
            $invdoc->is_payment_doc = $request->acflag;
            $invdoc->save();
            if ($invdoc) {
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

    public function deleteDocument(Request $request)
    {
        try {
            $invdocdata = InvestigationDocuments::select('investigation_id')->where('id', $request->id)->first();
            $invtransdata = [
                'event' => 'investigation_documentdelete',
                'data' => json_encode(array('data' => array('id' =>  $request->id, 'type' => 'documentdelete'))),
            ];
            InvestigationTransition::addTransion($invtransdata, $invdocdata->investigation_id);
            InvestigationDocuments::find($request->id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.registration.investigation.document_deleted'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function toggleDocumentShare(Request $request)
    {
        try {
            $document = InvestigationDocuments::where('id', $request->docId)->first();
            $field = $request->type;
            $document->{$field} = $request->value;
            $document->save();
            $document->fresh();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.registration.investigation.document_sharing'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function showSearchInvestigators($id)
    {
        $invId = Crypt::decrypt($id);
        $userId = Investigations::where('id', $invId)->select('user_id')->pluck('user_id')->first();
        $clientId = Client::where('user_id', $userId)->select('id')->pluck('id')->first();
        $products = ClientProduct::with('product:id,name')->whereHas('product')->where('client_id', $clientId)->where('price', '>', 0)->get();

        $topInvestigators = InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email'])
            ->select(
                'investigator_id',
                DB::raw("SUM(CASE WHEN status = 'Completed With Findings' OR status = 'Report Accepted' OR status = 'Final Report Submitted' THEN 1 else 0 end) AS total_completed"),
                DB::raw("SUM(CASE WHEN status NOT IN ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') THEN 1 else 0 end) AS total_open")
            )
            ->groupBy('investigator_id')
            ->orderBy('total_completed', 'DESC')
            ->orderBy('total_open', 'DESC')
            ->limit(4)
            ->get();

        $activeInvestigators = DB::select("SELECT i.id, u.name, u.email,
        SUM(case when ii.status not in ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') then 1 else 0 end) AS total_open,
        SUM(case when ii.status = 'Completed With Findings' or ii.status = 'Report Accepted' or ii.status = 'Final Report Submitted' then 1 else 0 end) AS total_completed,
        count(ii.id) AS all_assigned,
        round(SUM(case when ii.status = 'Completed With Findings' or ii.status = 'Report Accepted' or ii.status = 'Final Report Submitted' then 1 else 0 end) * 100 / count(ii.id), 2) as percentage
        from investigators i
        left join investigator_investigations ii on ii.investigator_id = i.id
        left join users u on u.id = i.user_id
        group by i.id  
        having total_open = 0  and all_assigned > 0
        order by percentage desc LIMIT 4");


        return view('investigation.search-investigator', compact('invId', 'products', 'topInvestigators', 'activeInvestigators'));
    }

    public function bulkAssign(Request $request)
    {
        // $invId = $_COOKIE['ids'];
        //print_r($ids);die;
        $invId = json_decode($_COOKIE['invIds'], true);
        $userId = Investigations::whereIn('id', $invId)->select('user_id')->pluck('user_id')->first();
        $clientId = Client::where('user_id', $userId)->select('id')->pluck('id')->first();

        $products = ClientProduct::with('product:id,name')->whereHas('product')->where('client_id', $clientId)->where('price', '>', 0)->get();
        $topInvestigators = InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email'])
            ->whereIn('status', ['Completed With Findings', 'Assigned'])
            ->select(
                'investigator_id',
                DB::raw("SUM(case when status = 'Completed With Findings' then 1 else 0 end) AS total_completed"),
                DB::raw("SUM(case when status = 'Assigned' then 1 else 0 end) AS total_open")
            )
            ->groupBy('investigator_id')
            ->orderBy('total_completed', 'DESC')
            ->orderBy('total_open', 'DESC')
            ->limit(4)
            ->get();

        $activeInvestigators = InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email'])
            ->whereIn('status', ['Completed With Findings', 'Assigned'])
            ->select(
                'investigator_id',
                DB::raw("SUM(case when status = 'Completed With Findings' then 1 else 0 end) AS total_completed"),
                DB::raw("SUM(case when status = 'Assigned' then 1 else 0 end) AS total_open")
            )
            ->groupBy('investigator_id')
            ->orderBy('total_open', 'DESC')
            ->having('total_open', '>', '0')
            ->limit(4)
            ->get();

        return view('investigation.search-investigator', compact('products','topInvestigators', 'activeInvestigators'));
    }

    public function searchInvestigators($id)
    {
        if ($id == 'bulk') {
            $ids = json_decode($_COOKIE['invIds'], true);
            $investigation = Investigations::whereIn('id', $ids)->select(['user_id', 'type_of_inquiry'])->get()->first();
            $assignedInvestigator = null;
            $investigators = User::whereHas(
                'user_type',
                function ($query) {
                    return $query->where('type_name', env('USER_TYPE_INVESTIGATOR'));
                }
            )
                ->with([
                    'investigator' => function ($q) {
                        $q->select(['id as inv_id', 'user_id', 'family', 'idnumber', 'website', 'dob', 'company']);
                    },
                    'userAddresses' => function ($q) {
                        $q->select(['id as addr_id', 'user_id', 'address1', 'address2', 'street', 'city', 'state', 'zipcode']);
                    },
                    'userEmails' => function ($q) {
                        $q->select(['id as mails_id', 'user_id', 'value']);
                    },
                    'userPhones' => function ($q) {
                        $q->select(['id as phn_id', 'user_id', 'value', 'type']);
                    },
                    'specializations' => function ($q) {
                        $q->select(['name', 'hr_name']);
                    },
                ]);

            return DataTables::of($investigators)
                ->addColumn('name-email', function ($investigator) use ($assignedInvestigator) {
                    $class = !empty($assignedInvestigator) && $assignedInvestigator->investigator_id == $investigator->investigator->inv_id ? "assignedInvestigation" : "assignInvestigation";
                    $html = '<div class="p-4">
                                <a href="javascript:void(0)" 
                                class="' . $class . '" data-typeofinq="" 
                                data-invrid="' . $investigator->investigator->inv_id . '" data-invrname="' . $investigator->name . '">
                                    <h6 class="mb-1 font-size-16 mt-2" id="invrname">' . $investigator->name . '</h6>
                                </a>
                                <p class="text-muted mb-0">' . $investigator->email . '</p>
                            </div>';

                    return $html;
                })
                ->addColumn('specializations', function ($investigator) {
                    $html = '<ul class="speclist-ul">';
                    if(config('app.locale') == 'hr'){
                        $specArr = !empty($investigator->specializations) ? array_column($investigator->specializations->toArray(), 'hr_name') : '';
                    }else{
                        $specArr = !empty($investigator->specializations) ? array_column($investigator->specializations->toArray(), 'name') : '';
                    }
                    if ($specArr) {
                        foreach ($specArr as $spec) {
                            $html .= '<li>' . ucwords($spec) . '</li>';
                        }
                    }
                    $html .= '</ul>';

                    return $html;
                })
                ->addColumn('investigations', function ($investigator) {

                    $invStatus = null;
                    $html = '<ul class="spec-ul">';

                    $invStatus = InvestigatorInvestigations::whereHas('investigation')
                        ->where('investigator_id', $investigator->investigator->inv_id)
                        ->select('status', DB::raw('COUNT(id) as total'))
                        ->groupBy('status')->orderBy('status')
                        ->pluck('total', 'status')->toArray();

                    $active = !empty($invStatus['Assigned']) ? $invStatus['Assigned'] : '0';
                    $completed = !empty($invStatus['Completed With Findings']) ? $invStatus['Completed With Findings'] : '0';
                    $notCompleted = !empty($invStatus['Completed Without Findings']) ? $invStatus['Completed Without Findings'] : '0';

                    $html .= "<li>" . trans('form.investigation.search.open_cases') . " ({$active})</li>";
                    $html .= "<li>" . trans('form.investigation_status.Completed') . " ({$completed})</li>";
                    $html .= "<li>" . trans('form.investigation_status.NotCompleted') . " ({$notCompleted})</li>";

                    $html .= '</ul>';

                    return $html;
                })
                ->addColumn('family', function ($investigator) {
                    return !empty($investigator->investigator->family) ? $investigator->investigator->family : '';
                })
                ->addColumn('idnumber', function ($investigator) {
                    return !empty($investigator->investigator->idnumber) ? $investigator->investigator->idnumber : '';
                })
                ->addColumn('website', function ($investigator) {
                    return !empty($investigator->investigator->website) ? $investigator->investigator->website : '';
                })
                ->addColumn('phone', function ($investigator) {
                    return !$investigator->userPhones->isEmpty() ? $investigator->userPhones[0]->value : '';
                })
                ->addColumn('address', function ($investigator) {
                    return !$investigator->userAddresses->isEmpty() ? $investigator->userAddresses[0]->address1 : '';
                })
                ->addColumn('action', function ($investigator) use ($assignedInvestigator, $id, $investigation) {
                    $assignedInvId = "";

                    if (!empty($assignedInvestigator) && !empty($assignedInvestigator->investigator_id)) {
                        $assignedInvId = $assignedInvestigator->investigator_id;
                    }

                    if (!empty($assignedInvestigator) && $assignedInvestigator->investigator_id == $investigator->investigator->inv_id) {
                        $class = "assignedInvestigation btn btn-success btn-sm waves-effect waves-light";
                        $text = trans('form.investigation.search.assigned');
                    } else {
                        $class = "assignInvestigation btn btn-primary btn-sm waves-effect waves-light";
                        $text = trans('form.investigation.assign');
                    }

                    //$btn = '<a href="javascript:void(0)" class="' . $class . '" title="' . trans('form.investigation.assign_investigation') . '" data-typeofinq=""  data-invrid="' . $investigator->investigator->inv_id . '" data-invrname="' . $investigator->name . '" data-isassigned="' . $assignedInvId . '" data-invId="' . $id . '">' . $text . '</a>';

                    $btn = '<a href="javascript:void(0)" class="' . $class . '" title="' . trans('form.investigation.assign_investigation') . '" data-typeofinq="' . $investigation->type_of_inquiry . '" 
                                data-invrid="' . $investigator->investigator->inv_id . '" data-invrname="' . $investigator->name . '" data-isassigned="' . $assignedInvId . '" data-invId="' . $id . '">' . $text . '</a>';

                    return $btn;
                })
                ->rawColumns(['specializations', 'investigations', 'family-idnumber', 'name-email', 'action'])
                ->make(true);
        } else {
            $investigation = Investigations::where('id', $id)->select(['user_id', 'type_of_inquiry'])->get()->first();

            $assignedInvestigator = InvestigatorInvestigations::where('investigation_id', $id)->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined','Cancel'])->select(['investigator_id'])->get()->toArray();
            $assignedInvestigator = !empty($assignedInvestigator) ? array_column($assignedInvestigator, 'investigator_id') : [];

            $investigators = User::whereHas(
                'user_type',
                function ($query) {
                    return $query->where('type_name', env('USER_TYPE_INVESTIGATOR'));
                }
            )
                ->with([
                    'investigator' => function ($q) {
                        $q->select(['id as inv_id', 'user_id', 'family', 'idnumber', 'website', 'dob', 'company']);
                    },
                    'userAddresses' => function ($q) {
                        $q->select(['id as addr_id', 'user_id', 'address1', 'address2', 'street', 'city', 'state', 'zipcode']);
                    },
                    'userEmails' => function ($q) {
                        $q->select(['id as mails_id', 'user_id', 'value']);
                    },
                    'userPhones' => function ($q) {
                        $q->select(['id as phn_id', 'user_id', 'value', 'type']);
                    },
                    'specializations' => function ($q) {
                        $q->select(['name', 'hr_name']);
                    },
                ])
                ->whereIn('status', ['approved', 'active']);

            return DataTables::of($investigators)
                ->addColumn('name-email', function ($investigator) use ($investigation, $assignedInvestigator) {
                    $class = array_search($investigator->investigator->inv_id, $assignedInvestigator) !== FALSE ? "assignedInvestigation" : "assignInvestigation";

                    $html = '<div class="p-4">
                                <a href="javascript:void(0)" 
                                class="' . $class . '" data-typeofinq="' . $investigation->type_of_inquiry . '" 
                                data-invrid="' . $investigator->investigator->inv_id . '" data-invrname="' . $investigator->name . '">
                                    <h6 class="mb-1 font-size-16 mt-2" id="invrname">' . $investigator->name . '</h6>
                                </a>
                                <p class="text-muted mb-0">' . $investigator->email . '</p>
                            </div>';

                    return $html;
                })
                ->addColumn('specializations', function ($investigator) {
                    $html = '<ul class="speclist-ul">';
                    if(config('app.locale') == 'hr'){
                        $specArr = !empty($investigator->specializations) ? array_column($investigator->specializations->toArray(), 'hr_name') : '';
                    } else {
                        $specArr = !empty($investigator->specializations) ? array_column($investigator->specializations->toArray(), 'name') : '';
                    }
                    if ($specArr) {
                        foreach ($specArr as $spec) {
                            $html .= '<li>' . ucwords($spec) . '</li>';
                        }
                    }
                    $html .= '</ul>';

                    return $html;
                })
                ->addColumn('investigations', function ($investigator) {

                    $invStatus = null;
                    $html = '<ul class="spec-ul">';

                    $invStatus = InvestigatorInvestigations::whereHas('investigation')
                        ->select(
                            'investigator_id',
                            'investigation_id',
                            DB::raw("SUM(CASE WHEN status = 'Completed With Findings' OR status = 'Report Accepted' OR status = 'Final Report Submitted' THEN 1 else 0 end) AS total_completed"),
                            DB::raw("SUM(CASE WHEN status NOT IN ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') THEN 1 else 0 end) AS total_open")
                        )
                        ->where('investigator_id', $investigator->investigator->inv_id)
                        ->first();

                    $active = $invStatus->total_open ?? 0;
                    $completed = $invStatus->total_completed ?? 0;

                    $html .= "<li>".trans('form.investigation.search.open_cases')." ({$active})</li>";
                    $html .= "<li>".trans('form.investigation_status.Completed')." ({$completed})</li>";

                    $html .= '</ul>';

                    return $html;
                })
                ->addColumn('family', function ($investigator) {
                    return !empty($investigator->investigator->family) ? $investigator->investigator->family : '';
                })
                ->addColumn('idnumber', function ($investigator) {
                    return !empty($investigator->investigator->idnumber) ? $investigator->investigator->idnumber : '';
                })
                ->addColumn('website', function ($investigator) {
                    return !empty($investigator->investigator->website) ? $investigator->investigator->website : '';
                })
                ->addColumn('phone', function ($investigator) {
                    return !$investigator->userPhones->isEmpty() ? $investigator->userPhones[0]->value : '';
                })
                ->addColumn('address', function ($investigator) {
                    return !$investigator->userAddresses->isEmpty() ? $investigator->userAddresses[0]->address1 : '';
                })
                ->addColumn('action', function ($investigator) use ($investigation, $assignedInvestigator, $id) {
                    $assignedInvId = implode('-', $assignedInvestigator);

                    if (array_search($investigator->investigator->inv_id, $assignedInvestigator) !== FALSE) {
                        $class = "assignedInvestigation btn btn-success btn-sm waves-effect waves-light";
                        $text = trans('form.investigation.search.assigned');
                    } else {
                        $class = "assignInvestigation btn btn-primary btn-sm waves-effect waves-light";
                        $text = trans('form.investigation.assign');
                    }

                    $btn = '<a href="javascript:void(0)" class="' . $class . '" title="' . trans('form.investigation.assign_investigation') . '" data-typeofinq="' . $investigation->type_of_inquiry . '" 
                                data-invrid="' . $investigator->investigator->inv_id . '" data-invrname="' . $investigator->name . '" data-isassigned="' . $assignedInvId . '" data-invId="' . $id . '">' . $text . '</a>';

                    return $btn;
                })
                ->rawColumns(['specializations', 'investigations', 'family-idnumber', 'name-email', 'action'])
                ->make(true);
        }
    }

    public function investigatorAssign(Request $request)
    {
        try {
            $assigned = null;
            $date = $request->start_time ? new \DateTime($request->start_time) : '';
            $start_time = $date ? $date->format('h:i:s') : null;

            if ($request->investigationId == 'bulk') {
                $ids = json_decode($_COOKIE['invIds']);
                for ($i = 0; $i < count($ids); $i++) {

                    $exist = InvestigatorInvestigations::where('investigation_id', $ids[$i])->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined'])->first();

                    $assigned = InvestigatorInvestigations::create([
                        'investigation_id' => $ids[$i],
                        'investigator_id' => $request->investigatorId,
                        'investigator' => $request->investigator,
                        'type_of_inquiry' => $request->type_of_inquiry,
                        //'start_date' => !empty($request->start_date) ? date("Y-m-d", strtotime($request->start_date)) : null,
                        //'start_time' => $start_time,
                        'start_date' => date("Y-m-d"),
                        'start_time' => date("h:i:s"),
                        'completion_date' => !empty($request->completion_date) ? date("Y-m-d", strtotime($request->completion_date)) : null,
                        'completion_time' => $request->completion_time,
                        'note' => $request->note,
                        'status' => 'Assigned',
                        'status_hr' => trans('form.timeline_status.Assigned', [], 'hr'),
                        'assigned_by' => Auth::id(),
                        'inv_cost' => $request->inv_cost,
                    ]);

                    if (empty($exist)) {
                        $invtransdata = [
                            'event' =>  'investigator_assigneed',
                            'data' => json_encode(array('data' => array('id' => $request->investigatorId,'investigator_investigation_id' => $assigned->id, 'type' => 'investigator', 'status' => 'in progress'))),
                        ];
                        InvestigationTransition::addTransion($invtransdata, $ids[$i]);
                    } else {
                        $exist->status = 'Not Completed';
                        $exist->status_hr = trans('form.timeline_status.Not Completed', [], 'hr');
                        $exist->save();

                        $invtransdata = [
                            'event' =>  'investigator_changeassignee',
                            'data' => json_encode(array('data' => array('id' => $request->investigatorId, 'type' => 'investigator', 'status' => 'in progress', 'oldid' =>  $exist->investigator_id,'investigator_investigation_id' => $assigned->id))),
                        ];
                        InvestigationTransition::addTransion($invtransdata, $ids[$i]);
                    }
                }
                Cookie::queue(Cookie::forget('invIds'));
                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.investigation.inv_assign_success'),
                    'data' => $assigned
                ]);
            } else {
                $assigned = InvestigatorInvestigations::create([
                    'investigation_id' => $request->investigationId,
                    'investigator_id' => $request->investigatorId,
                    'investigator' => $request->investigator,
                    'type_of_inquiry' => $request->type_of_inquiry,
                    'start_date' => date("Y-m-d"),
                    'start_time' => date("h:i:s"),
                    'completion_date' => !empty($request->completion_date) ? date("Y-m-d", strtotime($request->completion_date)) : null,
                    'completion_time' => $request->completion_time,
                    'note' => $request->note,
                    'status' => 'Assigned',
                    'status_hr' => trans('form.timeline_status.Assigned', [], 'hr'),
                    'assigned_by' => Auth::id(),
                    'inv_cost' => $request->inv_cost,
                ]);

                $invtransdata = [
                    'event' =>  'investigator_assigneed',
                    'data' => json_encode(array('data' => array('id' => $request->investigatorId, 'type' => 'investigator', 'status' => 'Assigned','investigator_investigation_id' => $assigned->id))),
                ];
                
                InvestigationTransition::addTransion($invtransdata, $request->investigationId);

                $userData = Investigators::with('user')->where('id', $request->investigatorId)->first();
                $investigation = Investigations::where('id', $request->investigationId)->first();
                Mail::to($userData->user->email)->queue(new InvestigationInvestigator($userData, $assigned, $investigation, Auth::user()));
                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.investigation.inv_assign_success'),
                    'data' => $assigned
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    /**
     * this function is actiondata as per selected in detail page .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function actionData(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        if ($type == 'decline_data') {
            $decreasons = Setting::where('key', 'decline_reason')->get();
            $returnHTML = view('investigation.declinedata', compact('type', 'decreasons', 'id'))->render();

            return response()->json(
                array(
                    'status'        => 1,
                    'html'          => $returnHTML
                )
            );
        } else if ($type == 'decline_update') {
            try {
                $inv = Investigations::find($id);
                $inv->decline_reason = $request->decline_reason;
                $inv->decline_date = now();
                $inv->decline_by = auth()->user()->id;
                $inv->status = 'Declined';
                $inv->status_hr = trans('form.timeline_status.Declined', [], 'hr');
                $inv->save();
                $invtransdata = [
                    'event' => 'investigation_action',
                    'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'declined', 'reason' => $request->decline_reason))),
                ];
                InvestigationTransition::addTransion($invtransdata, $id);
                $otherdata = [
                    'success_msg' => trans('form.registration.investigation.declineapprove_email.decline_success_text'),
                    'reason_msg' => $request->decline_reason,
                    'subject' => 'Investigation Declined',
                ];
                Mail::to($inv->user->email)->queue(new EmailInvestigationApprove($inv->user, $otherdata, $inv));
                $invtransmaildata = [
                    'event' =>  'mail_send',
                    'data' => json_encode(array('data' => array('id' => $inv->user->id, 'type' => 'client', 'mailtag' => 'Investigation Declined', 'remark' => $request->decline_reason))),
                ];
                InvestigationTransition::addTransion($invtransmaildata, $id);
                return response()->json(
                    array(
                        'status'        => 1,
                        'msg'          => trans('form.registration.investigation.decline_confirmed'),
                    )
                );
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'msg' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        } else if ($type == 'approve_update') {
            // try {
                $inv = Investigations::find($id);

                $caseId = AppHelper::findExistingCase($inv);

                if ($caseId) {
                    $inv->case_id = $caseId;

                    $case = Cases::find($caseId);
                    $case->status = 'Open';
                    $case->save();

                } else {
                    //Create case
                    $caseData = Cases::create([
                        'status' => 'Open'
                    ]);

                    $inv->case_id = $caseData->id;
                }

                $inv->approve_date = now();
                $inv->approval_at = now();
                $inv->approved_by = auth()->user()->id;
                $inv->status = 'Approved';
                $inv->status_hr = trans('form.timeline_status.Approved', [], 'hr');
                $inv->save();
                $invtransdata = [
                    'event' => 'investigation_action',
                    'data' => json_encode(array('data' => array('id' => auth()->user()->id, 'type' => 'approved', 'reason' => ''))),
                ];
                InvestigationTransition::addTransion($invtransdata, $id);
                $otherdata = [
                    'success_msg' => trans('form.registration.investigation.declineapprove_email.approve_success_text'),
                    'reason_msg' => '',
                    'subject' => trans('form.registration.investigation.declineapprove_email.approve_subject'),
                ];
                Mail::to($inv->user->email)->queue(new EmailInvestigationApprove($inv->user, $otherdata, $inv));
                $invtransmaildata = [
                    'event' =>  'mail_send',
                    'data' => json_encode(array('data' => array('id' => $inv->user->id, 'type' => 'client', 'mailtag' => 'Investigation Approved', 'remark' => ''))),
                ];
                InvestigationTransition::addTransion($invtransmaildata, $id);
                return response()->json(
                    array(
                        'status'        => 1,
                        'msg'          => trans('form.registration.investigation.approve_confirmed'),
                    )
                );
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'status' => 'error',
            //         'msg' => trans('general.something_wrong'),
            //         'exception' => $th->getLine().' '.$th->getMessage(),
            //     ]);
            // }
        } else {

            return response()->json(
                array(
                    'status'        => 2,
                    'msg'          => trans('form.registration.something_went_wrong')
                )
            );
        }
    }

    public function showSearchDeliveryboys($id)
    {
        $invId = Crypt::decrypt($id);
        $userId = Investigations::where('id', $invId)->select('user_id')->pluck('user_id')->first();
        $clientId = Client::where('user_id', $userId)->select('id')->pluck('id')->first();
        $products = ClientProduct::with('product:id,name')->whereHas('product')->where('client_id', $clientId)->where('price', '>', 0)->get();

        $topDeliveryboys = DeliveryboyInvestigations::with(['deliveryboy:id,user_id', 'deliveryboy.user:id,name,email'])
            ->whereIn('status', ['Done And Delivered', 'Assigned'])
            ->select(
                'deliveryboy_id',
                DB::raw("SUM(case when status = 'Done And Delivered' then 1 else 0 end) AS total_completed"),
                DB::raw("SUM(case when status = 'Assigned' then 1 else 0 end) AS total_open")
            )
            ->groupBy('deliveryboy_id')
            ->orderBy('total_completed', 'DESC')
            ->orderBy('total_open', 'DESC')
            ->limit(4)
            ->get();

        $activeDeliveryboys = DB::select("SELECT i.id, u.name, u.email,
        SUM(case when ii.status = 'Assigned' then 1 else 0 end) AS total_open,
        SUM(case when ii.status = 'Done And Delivered' then 1 else 0 end) AS total_completed,
        count(ii.id) AS all_assigned,
        round(SUM(case when ii.status = 'Done And Delivered' then 1 else 0 end) * 100 / count(ii.id), 2) as percentage
        from deliveryboys i
        left join deliveryboy_investigations ii on ii.deliveryboy_id = i.id
        left join users u on u.id = i.user_id
        group by i.id  
        having total_open = 0  and all_assigned > 0
        order by percentage desc LIMIT 4");

        return view('investigation.search-deliveryboy', compact('invId', 'products', 'topDeliveryboys', 'activeDeliveryboys'));
    }

    public function searchDeliveryboys($id)
    {
        $investigation = Investigations::where('id', $id)->select(['user_id', 'type_of_inquiry'])->get()->first();

        $assignedDeliveryboy = DeliveryboyInvestigations::where('investigation_id', $id)->whereNotIn('status', ['Rejected', 'Done And Not Delivered', 'Return To Center'])->select(['deliveryboy_id'])->get()->toArray();
        $assignedDeliveryboy = !empty($assignedDeliveryboy) ? array_column($assignedDeliveryboy, 'deliveryboy_id') : [];

        $deliveryboys = User::whereHas(
            'user_type',
            function ($query) {
                return $query->where('type_name', env('USER_TYPE_DELIVERY_BOY'));
            }
        )
            ->with([
                'deliveryboy' => function ($q) {
                    $q->select(['id as del_id', 'user_id', 'family', 'idnumber', 'website', 'dob']);
                },
                'userAddresses' => function ($q) {
                    $q->select(['id as addr_id', 'user_id', 'address1', 'address2', 'street', 'city', 'state', 'zipcode']);
                },
                'userEmails' => function ($q) {
                    $q->select(['id as mails_id', 'user_id', 'value']);
                },
                'userPhones' => function ($q) {
                    $q->select(['id as phn_id', 'user_id', 'value', 'type']);
                },
                'delivery_areas' => function ($q) {
                    $q->select(['area_name']);
                },
            ])
            ->whereIn('status', ['approved', 'active']);

        return DataTables::of($deliveryboys)
            ->addColumn('name-email', function ($deliveryboy) use ($investigation, $assignedDeliveryboy) {
                $class = array_search($deliveryboy->deliveryboy->del_id, $assignedDeliveryboy) !== FALSE ? "assignedInvestigation" : "assignInvestigation";

                $html = '<div class="p-4">
                                <a href="javascript:void(0)" 
                                class="' . $class . '" data-typeofinq="' . $investigation->type_of_inquiry . '" 
                                data-invrid="' . $deliveryboy->deliveryboy->del_id . '" data-invrname="' . $deliveryboy->name . '">
                                    <h6 class="mb-1 font-size-16 mt-2" id="invrname">' . $deliveryboy->name . '</h6>
                                </a>
                                <p class="text-muted mb-0">' . $deliveryboy->email . '</p>
                            </div>';

                return $html;
            })
            ->addColumn('deliveryareas', function ($deliveryboy) {
                $html = '<ul class="speclist-ul">';

                $specArr =  !empty($deliveryboy->delivery_areas) ? array_column($deliveryboy->delivery_areas->toArray(), 'area_name') : '';

                if ($specArr) {
                    foreach ($specArr as $spec) {
                        $html .= '<li>' . ucwords($spec) . '</li>';
                    }
                }

                $html .= '</ul>';

                return $html;
            })
            ->addColumn('investigations', function ($deliveryboy) {

                $invStatus = null;
                $html = '<ul class="spec-ul">';

                $invStatus = DeliveryBoyInvestigations::whereHas('investigation')
                    ->where('deliveryboy_id', $deliveryboy->deliveryboy->del_id)
                    ->select('status', DB::raw('COUNT(id) as total'))
                    ->groupBy('status')->orderBy('status')
                    ->pluck('total', 'status')->toArray();

                $active = !empty($invStatus['Assigned']) ? $invStatus['Assigned'] : '0';
                $completed = !empty($invStatus['Done And Delivered']) ? $invStatus['Done And Delivered'] : '0';
                $notCompleted = !empty($invStatus['Done And Not Delivered']) ? $invStatus['Done And Not Delivered'] : '0';

                $html .= "<li>" . trans('form.investigation.search.open_cases') . " ({$active})</li>";
                $html .= "<li>" . trans('form.investigation.search.delivered') . " ({$completed})</li>";
                $html .= "<li>" . trans('form.investigation.search.not_delivered') . " ({$notCompleted})</li>";

                $html .= '</ul>';

                return $html;
            })
            ->addColumn('family', function ($deliveryboy) {
                return !empty($deliveryboy->deliveryboy->family) ? $deliveryboy->deliveryboy->family : '';
            })
            ->addColumn('idnumber', function ($deliveryboy) {
                return !empty($deliveryboy->deliveryboy->idnumber) ? $deliveryboy->deliveryboy->idnumber : '';
            })
            ->addColumn('website', function ($deliveryboy) {
                return !empty($deliveryboy->deliveryboy->website) ? $deliveryboy->deliveryboy->website : '';
            })
            ->addColumn('phone', function ($deliveryboy) {
                return !$deliveryboy->userPhones->isEmpty() ? $deliveryboy->userPhones[0]->value : '';
            })
            ->addColumn('address', function ($deliveryboy) {
                return !$deliveryboy->userAddresses->isEmpty() ? $deliveryboy->userAddresses[0]->address1 : '';
            })
            ->addColumn('action', function ($deliveryboy) use ($investigation, $assignedDeliveryboy, $id) {
                $assignedDelId = implode('-', $assignedDeliveryboy);

                if (array_search($deliveryboy->deliveryboy->del_id, $assignedDeliveryboy) !== FALSE) {
                    $class = "assignedInvestigation btn btn-success btn-sm waves-effect waves-light";
                    $text = trans('form.investigation.search.assigned');
                } else {
                    $class = "assignInvestigation btn btn-primary btn-sm waves-effect waves-light";
                    $text = trans('form.investigation.assign');
                }

                $btn = '<a href="javascript:void(0)" class="' . $class . '" title="' . trans('form.investigation.assign_investigation') . '" data-typeofinq="' . $investigation->type_of_inquiry . '" 
                            data-invrid="' . $deliveryboy->deliveryboy->del_id . '" data-invrname="' . $deliveryboy->name . '" data-isassigned="' . $assignedDelId . '" data-invId="' . $id . '">' . $text . '</a>';

                return $btn;
            })
            ->rawColumns(['deliveryareas', 'investigations', 'family-idnumber', 'name-email', 'action'])
            ->make(true);
    }

    public function deliveryboyAssign(Request $request)
    {
        try {
            $assigned = null;
            $date = $request->start_time ? new \DateTime($request->start_time) : '';
            $start_time = $date ? $date->format('h:i:s') : null;

            $assigned = DeliveryboyInvestigations::create([
                'investigation_id' => $request->investigationId,
                'deliveryboy_id' => $request->deliveryboyId,
                'type_of_inquiry' => $request->type_of_inquiry,
                'start_date' => date("Y-m-d"),
                'start_time' => date("h:i:s"),
                'completion_date' => !empty($request->completion_date) ? date("Y-m-d", strtotime($request->completion_date)) : null,
                'completion_time' => $request->completion_time,
                'note' => $request->note,
                'status' => 'Assigned',
                'status_hr' => trans('form.timeline_status.Assigned', [], 'hr'),
                'assigned_by' => Auth::id(),
                'inv_cost' => $request->inv_cost,
            ]);
            
            // Investigations::where('id', $request->investigationId)->update(['status' => 'Assigned', 'inv_cost' => $request->inv_cost]);
            // if($request->inv_cost != $request->old_inv_cost) {
            //     $logData = [
            //         'event' => 'investigation_price_change',
            //         'data' => json_encode(array('data' => array('id' => Auth::id(), 'type' => 'changed_by'))),
            //     ];

            //     InvestigationTransition::addTransion($logData, $ids[$i]);
            // }

            $invtransdata = [
                'event' =>  'deliveryboy_assigneed',
                'data' => json_encode(array('data' => array('id' => $request->deliveryboyId, 'type' => 'deliveryboy','deliveryboy_investigations_id' => $assigned->id, 'status' => 'Assigned'))),
            ];
            InvestigationTransition::addTransion($invtransdata, $request->investigationId);

            return response()->json([
                'status' => 'success',
                'message' => trans('form.investigation.del_assign_success'),
                'data' => $assigned
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function changeAssignmentStatus(Request $request)
    {
        try {
            if (!empty($request->investigationId) && !empty($request->userId) && !empty($request->status) && !empty($request->performer)) {

                $status = $request->status;
                $assignmentStatus = null;
                $otherAssignmentStatus = null;
                $investigationStatus = null;
                $assignment = null;
                $otherAssignments = null;
                $investigation = null;

                if ($request->performer == 'admin') {
                    $assignmentStatus = $status;
                    $entityId = !empty($request->entityId) ? $request->entityId : 0;

                    if ($status == 'Finalizing Report' || $status == 'Printing' || $status == 'Waiting For Final Approval' || $status == 'Send To Client' || $status == 'Closed') {
                        $assignmentStatus = null;
                        $investigationStatus = $status;
                    }

                    if ($status == 'Done And Delivered') {
                        $investigationStatus = 'Delivered';
                    }

                    if ($status == 'Done And Delivered' || $status == 'Done And Not Delivered') {
                        $assignment = DeliveryboyInvestigations::where('investigation_id', $request->investigationId)
                            ->where('deliveryboy_id', $entityId)
                            ->whereNotIn('status', ['Rejected', 'Done And Not Delivered'])
                            ->orderBy('created_at', 'DESC')->first();
                    } else {
                        $assignment = InvestigatorInvestigations::where('investigation_id', $request->investigationId)
                            ->where('investigator_id', $entityId)
                            ->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined'])
                            ->orderBy('created_at', 'DESC')->first();
                    }

                    if ($status == 'Report Accepted') {
                        $otherAssignmentStatus = 'Returned To Center';

                        $otherAssignments = InvestigatorInvestigations::where('investigation_id', $request->investigationId)
                            ->where('investigator_id', '<>', $entityId)
                            ->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined'])
                            ->orderBy('created_at', 'DESC')->get();
                    }
                }

                if ($request->performer == 'investigator') {
                    $assignmentStatus = $status;

                    if($status == 'Investigation Started'){
                        $investigationStatus = 'In Progress';
                    }

                    $investigatorId = AppHelper::getInvestigatorIdFromUserId($request->userId);
                    $assignment = InvestigatorInvestigations::where('investigation_id', $request->investigationId)->where('investigator_id', $investigatorId)->orderBy('created_at', 'DESC')->first();
                }

                if ($request->performer == 'deliveryboy') {
                    $assignmentStatus = $status;

                    $deliveryboyId = AppHelper::getDeliveryboyIdFromUserId($request->userId);
                    $assignment = DeliveryboyInvestigations::where('investigation_id', $request->investigationId)->where('deliveryboy_id', $deliveryboyId)->orderBy('created_at', 'DESC')->first();
                }

                if ($assignmentStatus != null && !empty($assignment)) {
                    $assignment->update(['status' => $assignmentStatus, 'status_hr' => trans('form.timeline_status.'.$assignmentStatus, [], 'hr')]);
                }

                if ($investigationStatus != null) {
                    Investigations::changeStatus($request->investigationId, $investigationStatus);
                }

                if ($otherAssignments) {
                    foreach ($otherAssignments as $otherAssignment) {
                        $otherAssignment->update(['status' => $otherAssignmentStatus, 'status_hr' => trans('form.timeline_status.'.$otherAssignmentStatus, [], 'hr')]);

                        $invtransdata = [
                            'event' => 'investigator_changestatus',
                            'data' => json_encode(array('data' => array('id' => $otherAssignment->investigator_id, 'type' => 'investigator', 'status' => $otherAssignmentStatus))),
                        ];

                        InvestigationTransition::addTransion($invtransdata, $request->investigationId);
                    }
                }

                if ($assignmentStatus != null && !empty($assignment)) {
                    if (get_class($assignment) == 'App\DeliveryboyInvestigations') {
                        $invtransdata = [
                            'event' => 'deliveryboy_changestatus',
                            'data' => json_encode(array('data' => array('id' => $assignment->deliveryboy_id, 'type' => 'deliveryboy', 'status' => $assignment->status))),
                        ];
                        InvestigationTransition::addTransion($invtransdata, $request->investigationId);
                    } else if (get_class($assignment) == 'App\InvestigatorInvestigations') {
                        if($assignment->status != "Investigation Declined") {
                            $invtransdata = [
                                'event' => 'investigator_changestatus',
                                'data' => json_encode(array('data' => array('id' => $assignment->investigator_id, 'type' => 'investigator', 'status' => $assignment->status))),
                            ];
                            InvestigationTransition::addTransion($invtransdata, $request->investigationId);
                        }
                    }
                }

                if ($investigationStatus != null) {
                    $logData = [
                        'event' => 'investigation_changestatus',
                        'data' => json_encode(array('data' => array('id' => Auth::id(), 'type' => 'changed_by'))),
                    ];

                    InvestigationTransition::addTransion($logData, $request->investigationId);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function investigatorCost($invnId, $invrId)
    {
        if($invnId == 'bulk') {
            return response()->json([
                'status' => 'success',
                'data' => AppHelper::calculateInvestigationCostForInvestigator('bulk', $invrId),
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => AppHelper::calculateInvestigationCostForInvestigator($invnId, $invrId),
            ]);
        }

    }

    public function deliveryboyCost($invnId, $delboyId)
    {
        return response()->json([
            'status' => 'success',
            'data' => AppHelper::calculateInvestigationCostForDeliveryboy($invnId, $delboyId),
        ]);

    }

    public function completeInvestigation(Request $request)
    {
        $data = $request->all();
        $assignment = InvestigatorInvestigations::where('investigation_id', $data['invnid'])
            ->where('investigator_id', AppHelper::getInvestigatorIdFromUserId($data['invrid']))
            ->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined'])
            ->orderBy('created_at', 'DESC')->first();

        if ($assignment) {
            try {
                $assignment->status = 'Completed Without Findings';
                $assignment->status_hr = trans('form.timeline_status.Completed Without Findings', [], 'hr');
                $assignment->completion_summary = $data['final_summary'];

                if (!isset($data['complete_type']) || empty($data['complete_type'])) {
                    $assignment->status = 'Completed With Findings';
                    $assignment->status_hr = trans('form.timeline_status.Completed With Findings', [], 'hr');
                    $assignment->completion_subject_summary = json_encode($data['summary']);
                }

                $assignment->completion_date = date('Y-m-d');
                $assignment->completion_time = date('H:i:s');

                $assignment->save();

                $invtransdata = [
                    'event' => 'investigator_changestatus',
                    'data' => json_encode(array('data' => array('id' => $assignment->investigator_id,'investigator_investigation_id' => $assignment->id,'investigator_compleated' => 1,'type' => 'investigator', 'status' => $assignment->status))),
                ];

                InvestigationTransition::addTransion($invtransdata, $assignment->investigation_id);

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                    'inv_status' => $assignment->status,
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }

    }

    public function actionOnReport(Request $request)
    {
        $data = $request->all();

        $assignment = InvestigatorInvestigations::find($data["assignId"]);
        
        if (isset($data['otherdoc'])) {
            $getInvestigationDoc = InvestigationDocuments::where('investigation_id',$assignment->investigation_id)->whereNotIn('id',$data['otherdoc'])->get();

            foreach ($getInvestigationDoc as $key => $value) {
                InvestigationDocuments::where('id',$value->id)->update(['price'=>0]
                );
            }
        }


        if ($assignment) {
            try {
                $assignment->status = $data['status'];
                $assignment->status_hr = trans('form.timeline_status.'.$data['status'], [], 'hr');
                $assignment->admin_report_final_summary = isset($data['admin_final_summary'])?$data['admin_final_summary']:$assignment->admin_report_final_summary;
                $assignment->admin_report_subject_summary = isset($data['admin_summary'])?json_encode($data['admin_summary']):$assignment->admin_report_subject_summary;
                $assignment->case_reports_accepted = (isset($data['casereport']) && !empty($data['casereport'])) ? json_encode($data['casereport']) : $assignment->case_reports_accepted;
                $assignment->docs_accepted = (isset($data['otherdoc']) && !empty($data['otherdoc'])) ? json_encode($data['otherdoc']) : $assignment->docs_accepted;
                $assignment->inv_cost = isset($data['inv_cost'])?$data['inv_cost']:$assignment->inv_cost;
                $assignment->doc_cost = isset($data['doc_cost'])?$data['doc_cost']:$assignment->doc_cost;

                $assignment->save();

                $invtransdata = [
                    'event' => 'investigator_changestatus',
                    'data' => json_encode(array('data' => array('id' => $assignment->investigator_id, 'type' => 'investigator', 'status' => $assignment->status))),
                ];

                InvestigationTransition::addTransion($invtransdata, $assignment->investigation_id);

                $userData = Investigators::with('user')->where('id', $assignment->investigator_id)->first();
                $investigation = Investigations::with('subjects')->where('id', $assignment->investigation_id)->first();
                
                Mail::to($userData->user->email)->queue(new UpdateInvestigatorInvestigation($userData, $data['status'], $investigation, Auth::user(), NULL));

                if($data['status'] == "Report Accepted"){
                    $invoiceData = [
                        'investigation_id' => $investigation->id,
                        'investigator_id' => $userData->id,
                        'invoice_no' => 'INV' . AppHelper::genrateInvoiceNumber("\App\InvestigatorInvoice"),
                        'amount' => ($data['inv_cost'] + $data['doc_cost'])
                    ];
                    $investigatorInvoice = InvestigatorInvoice::create($invoiceData);
                }

                if($data['status'] == "Cancel"){
                    // $invtransdata = [
                    //     'event' => 'investigation_cancel',
                    //     'data' => json_encode(array('data' => array('id' => $investigatorInvoice->id, 'type' => 'investigatorinvoice', 'inv_no' => $investigatorInvoice->invoice_no, 'status' => trans('form.timeline_status.Pending'), 'user_id' => $userData->user->id))),
                    // ];
                    // InvestigationTransition::addTransion($invtransdata, $investigation->id);

                    // Mail::to($userData->user->email)->queue(new EmailInvestigatorInvoice($userData, $investigation, $investigatorInvoice, Auth::user()));
                }

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }

    }

    public function actionOnReportDecline(Request $request){
        $data = $request->all();

        $assignment = InvestigatorInvestigations::find($data["assignId"]);

        if ($assignment) {
            try {
                $assignment->status = $data['status'];
                $assignment->status_hr = trans('form.timeline_status.'.$data['status'], [], 'hr');
                $assignment->decline_reason = $data['decline_reason'];
                $assignment->decline_date = Carbon::parse(Carbon::now())->format('Y-m-d');
                $assignment->decline_by = Auth::id();
                // $assignment->admin_report_final_summary = $data['admin_final_summary'];
                // $assignment->admin_report_subject_summary = json_encode($data['admin_summary']);
                // $assignment->case_reports_accepted = !empty($data['casereport']) ? json_encode($data['casereport']) : "";
                // $assignment->docs_accepted = !empty($data['otherdoc']) ? json_encode($data['otherdoc']) : "";
                // $assignment->inv_cost = $data['inv_cost'];
                // $assignment->doc_cost = $data['doc_cost'];

                $assignment->save();

                $invtransdata = [
                    'event' => 'investigator_changestatus',
                    'data' => json_encode(array('data' => array('id' => $assignment->investigator_id, 'type' => 'investigator', 'status' => $assignment->status))),
                ];
                if($data['status'] != 'Investigation Declined'){
                    InvestigationTransition::addTransion($invtransdata, $assignment->investigation_id);

                    $userData = Investigators::with('user')->where('id', $assignment->investigator_id)->first();
                    $investigation = Investigations::where('id', $assignment->investigation_id)->first();
                    
                    Mail::to($userData->user->email)->queue(new UpdateInvestigatorInvestigation($userData, $data['status'], $investigation, Auth::user(),$data['decline_reason']));
                }

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }
    }

    public function completeInvestigationDel(Request $request)
    {
        $data = $request->all();
 
        $assignment = DeliveryboyInvestigations::where('investigation_id', $data['invnid'])
        ->where('deliveryboy_id', AppHelper::getDeliveryboyIdFromUserId($data['invrid']))
        ->whereNotIn('status', ['Rejected', 'Done And Not Delivered'])
        ->orderBy('created_at', 'DESC')->first();

        if ($assignment) {
            try {
                $assignment->status = $data['status'];
                $assignment->status_hr = trans('form.timeline_status.'.$data['status'], [], 'hr');
                $assignment->completion_summary = $data['final_summary'];
                $assignment->completion_subject_summary = json_encode($data['summary']);

                $assignment->completion_date = date('Y-m-d');
                $assignment->completion_time = date('H:i:s');

                $assignment->save();

                $invtransdata = [
                    'event' => 'deliveryboy_changestatus',
                    'data' => json_encode(array('data' => array('id' => $assignment->deliveryboy_id,'deliveryboy_investigation_id' => $assignment->id ,'type' => 'deliveryboy', 'status' => $assignment->status))),
                ];

                InvestigationTransition::addTransion($invtransdata, $assignment->investigation_id);

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }
    }

    public function actionOnReportDel(Request $request)
    {
        $data = $request->all();
        $assignment = DeliveryboyInvestigations::find($data["assignId"]);

        if ($assignment) {
            try {

                $assignment->status = isset($data['status'])?$data['status']:'';
                $assignment->status_hr = isset($data['status'])?trans('form.timeline_status.'.$data['status'], [], 'hr'):'';
                $assignment->admin_report_final_summary = isset($data['admin_final_summary'])?$data['admin_final_summary']:'';
                $adminSummary = isset($data['admin_summary'])?$data['admin_summary']:'';
                $assignment->admin_report_subject_summary = json_encode($adminSummary);
                $assignment->case_reports_accepted = !empty($data['casereport']) ? json_encode($data['casereport']) : "";
                $assignment->docs_accepted = !empty($data['otherdoc']) ? json_encode($data['otherdoc']) : "";
                $assignment->inv_cost = isset($data['inv_cost'])?$data['inv_cost']:'';
                $assignment->doc_cost = isset($data['doc_cost'])?$data['doc_cost']:'';

                $assignment->save();

                $invtransdata = [
                    'event' => 'deliveryboy_changestatus',
                    'data' => json_encode(array('data' => array('id' => $assignment->deliveryboy_id, 'type' => 'deliveryboy', 'status' => $assignment->status))),
                ];

                InvestigationTransition::addTransion($invtransdata, $assignment->investigation_id);

                $userData = DeliveryBoys::with('user')->where('id', $assignment->deliveryboy_id)->first();
                $investigation = Investigations::with('subjects')->where('id', $assignment->investigation_id)->first();

                if($data['status'] == "Done And Delivered"){
                    $invoiceData = [
                        'investigation_id' => $investigation->id,
                        'deliveryboy_id' => $userData->id,
                        'invoice_no' => 'INV' . AppHelper::genrateInvoiceNumber("\App\DeliveryboyInvoice"),
                        'amount' => ($data['inv_cost'] + $data['doc_cost'])
                    ];
                    $deliveryboyInvoice = DeliveryboyInvoice::create($invoiceData);

                    // $invtransdata = [
                    //     'event' => 'deliveryboy_investigation_generateinvoice',
                    //     'data' => json_encode(array('data' => array('id' => $deliveryboyInvoice->id, 'type' => 'deliveryboyinvoice', 'inv_no' => $deliveryboyInvoice->invoice_no, 'status' => trans('form.timeline_status.Pending'), 'user_id' => $userData->user->id))),
                    // ];
                    // InvestigationTransition::addTransion($invtransdata, $investigation->id);

                    // Mail::to($userData->user->email)->queue(new EmailInvestigatorInvoice($userData, $investigation, $deliveryboyInvoice, Auth::user()));
                }

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }

    }

    public function submitSmReport(Request $request)
    {
        $data = $request->all();
        // print_r($data['invnid']);die;
        $assignment = InvestigatorInvestigations::where('investigation_id', $data['invnid'])
            ->orderBy('updated_at', 'DESC')->first();

        if ($assignment) {
            // try {
                $investigationStatus = "Writing Report";
                $assignment->status = 'Final Report Submitted';
                $assignment->status_hr = trans('form.timeline_status.Final Report Submitted', [], 'hr');
                $assignment->sm_final_summary = $data['final_summary'];

                $assignment->save();

                $invtransdata = [
                    'event' => 'investigator_changestatus',
                    'data' => json_encode(array('data' => array('id' => $assignment->investigator_id,'investigation_investigation_id' => $assignment->id, 'type' => 'investigator', 'status' => $assignment->status))),
                ];

                InvestigationTransition::addTransion($invtransdata, $assignment->investigation_id);

                Investigations::changeStatus($assignment->investigation_id, $investigationStatus);

                $logData = [
                    'event' => 'investigation_changestatus',
                    'data' => json_encode(array('data' => array('id' => Auth::id(), 'type' => 'changed_by'))),
                ];

                InvestigationTransition::addTransion($logData, $assignment->investigation_id);

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigation.confirm_statuschanged'),
                ]);

            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => trans('general.something_wrong'),
            //         'exception' => $th->getMessage(),
            //     ]);
            // }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }
    }

    // show investigation invoice parforma
    //1 client side non performa
    // Admin side non performa 
    // $id, it's Investigation id
    public function showInvoice($id)
    {
        $invId = Crypt::decrypt($id);

        $invno = 'PINV' . AppHelper::genrateInvoiceNumber("\App\PerformaInvoice");
        $invn = Investigations::find($invId);
        
        $clientId = AppHelper::getClientIdFromUserId($invn->user_id);
        $client = Client::find($clientId);
        $typeofInq = ClientProduct::with('product:id,name,is_delivery,delivery_cost')->where('client_id', $clientId)->where('product_id', $invn->type_of_inquiry)->first();

        $performaInvoiceStatus = "";
        if(count($invn->clientinvoice) > 0){
            $performaInvoice = $invn->clientinvoice->first();
            $performaInvoiceStatus = $performaInvoice->status;
        }

        $settings = Setting::all();

        return view('investigation.invoice', compact('invn', 'typeofInq', 'invno', 'client', 'settings', 'performaInvoiceStatus'));
    }

    // Bulk invoices pay
    public function showInvoices(Request $request)
    {
        $ids = explode(',', $_COOKIE['ids']);
        // $invIds = PerformaInvoice::whereIn('id', $ids)->get()->toArray();
        
        $invn = $typeofInq = $invno = $client = $settings = [];
        $key = 0;
        $performaInvoiceStatus = '';
        foreach($ids as $invId) {
            // $invno = 'INV' . date('isymHd');
            $invoiceNo = AppHelper::genrateInvoiceNumber("\App\Invoice");
            array_push($invno, 'INV' . ($invoiceNo + $key));

            $invnData = PerformaInvoice::with(['client' => function($q){
                $q->with(['user'=>function($qq){
                    $qq->with('userAddresses');
                },'client_type']);
            },'investigation' => function($q){
                $q->with(['documents' => function($qq){
                    $qq->with('documenttype');
                },'clientinvoice', 'product', 'subjects']);
            },'invoicedocs','invoiceitems', 'invoice'])->find($invId);

            array_push($invn, $invnData);
            
            $clientData = Client::find($invnData['client_id']);
            array_push($client, $clientData);

            $typeofInqData = ClientProduct::with('product:id,name,is_delivery,delivery_cost')->where('client_id', $clientData->id)->where('product_id', $invnData->investigation->type_of_inquiry)->first();
            array_push($typeofInq, $typeofInqData);

            $settings = Setting::all();
            $key++;

            $performaInvoiceStatus = $invnData->status;
        }
        return view('investigation.invoices', compact('invn', 'typeofInq', 'invno', 'client', 'settings','performaInvoiceStatus'));
    }
    
    // generate investigation invoice for client
    public function generateInvoice(Request $request)
    {
        try {
            DB::beginTransaction();
            if (!empty($request->investigationId)) {
                $invId = $request->investigationId;
                $subjects = !empty($request->subjects) ? json_decode($request->subjects, true) : [];

                if (count($subjects) > 0) {
                    $subtot = 0;
                    foreach ($subjects as $subject) {
                        $subtot += $subject['cost'];
                    }

                    $tax = 0;
                    $taxSetting = Setting::where('key', 'tax')->first();
                    if($taxSetting){
                        $tax = $taxSetting->value;
                    }
                    $subtot += $request->doccost?$request->doccost:0;
                    
                    $totalAmt = (($subtot * $tax) / 100) + $subtot;

                    if(isset($request->notdoccost)){
                        $totalAmt+=$request->notdoccost;
                    }

                    $invn = Investigations::find($invId);

                    // $invno = 'PINV' . date('isymHd');
                    $invno = 'PINV' . AppHelper::genrateInvoiceNumber("\App\PerformaInvoice");

                    $delivery_cost = $invn->inv_cost - $subtot;

                    $invdata = [
                        'investigation_id' =>  $invId,
                        'client_id' =>  $invn->user->client->id,
                        'invoice_no' => $invno,
                        'amount' => $totalAmt,
                        'tax' => $tax,
                        'delivery_cost' => ($delivery_cost>0)?$delivery_cost:0,
                        'status' => 'pending',
                        'created_by' => Auth::id(),
                    ];

                    $invData = PerformaInvoice::create($invdata);

                    $arr_items = [];
                    foreach ($subjects as $subject) {
                        $arr_items[] = [
                            'invoice_id' =>  $invData->id,
                            'subject_id' => $subject['id'],
                            'cost' => $subject['cost']
                        ];
                    }

                    InvoiceItems::insert($arr_items);

                    $invtransdata = [
                        'event' => 'investigation_generateinvoice',
                        'data' => json_encode(array('data' => array('id' => $invData->id, 'type' => 'clientinvoice'))),
                    ];
                    InvestigationTransition::addTransion($invtransdata, $invId);


                    //Make Final case report visible to client
                    $document = InvestigationDocuments::with('documenttype')->whereHas('documenttype', function($q){
                        $q->where('name','Final Case Report');
                    })
                    ->where('investigation_id', $invId)
                    ->first();

                    if(!empty($document)){
                        $document->share_to_client = 1;
                        $document->save();
                        $document->fresh();
                    }

                    DB::commit();

                    $invData = Investigations::with(['clientinvoice' => function($q) {
                        $q->with(['invoiceitems' => function($qq) {
                            $qq->with('subject');
                        }]);
                    }, 'client_type' => function($q) {
                        $q->with(['userAddresses' => function($qq) {
                            $qq->with('country');
                        }]);
                    }, 'documents' => function($q) {
                        $q->with('documenttype');
                    }, 'client'])->find($invId);
                    // print_r($invData['clientinvoice']->toArray()[0]['amount']);die;
                    //Send invoice email to client
                    Mail::to($invn->user->email)->queue(new EmailInvoice($invData));

                    return response()->json([
                        'status' => 'success',
                        'message' => trans('form.investigationinvoice.invoice_generated_msg'),
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => trans('general.something_wrong'),
                        'exception' => trans('general.something_wrong'),
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function rejectInvestigation(Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->type == env('USER_TYPE_INVESTIGATOR')){
                $investigatorId = AppHelper::getInvestigatorIdFromUserId($request->investigator_id);
                $status = InvestigatorInvestigations::where('investigator_id', $investigatorId)->where('investigation_id', $request->investigation_id)->update(['reject_reason' => $request->reject_reason]);
                DB::commit();
                $invtransdata = [
                    'event' => 'investigator_changestatus',
                    'data' => json_encode(array('data' => array('id' => $investigatorId, 'type' => 'investigator', 'status' => "Investigation Declined", 'reason' => $request->reject_reason))),
                ];

                InvestigationTransition::addTransion($invtransdata, $request->investigation_id);
                return response()->json([
                    'status' => 'success',
                    'result' => $status
                ]);    
            } else if($request->type == env('USER_TYPE_DELIVERY_BOY')){
                $deliveryboyId = AppHelper::getDeliveryboyIdFromUserId($request->investigator_id);
                $status = DeliveryboyInvestigations::where('deliveryboy_id', $deliveryboyId)->where('investigation_id', $request->investigation_id)->update(['reject_reason' => $request->reject_reason]);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => $status
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function extremeDelay()
    {
        try {
            $previous_week = strtotime("-3 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            
            $adminSM = User::select('id', 'name', 'email', 'type_id')->whereHas('user_type', function ($q) {
                $q->whereIn('type_name', [env('USER_TYPE_ADMIN'), env('USER_TYPE_STATION_MANAGER')]);
            })->get()->toArray();

            $investigator = Investigators::with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'email');
                }, 'investigations' => function ($q) use ($end_week) {
                    $q->with([
                        'investigation' => function ($q) {
                            $q->where('status', 'In Progress');
                        }
                    ])->where('status', 'Investigation Started')->whereDate('start_date', '<=', $end_week);
                }
            ])
            ->whereHas('investigations', function ($q) use ($end_week) {
                $q->where('status', 'Investigation Started')->whereDate('start_date', '<=', $end_week)->whereHas('investigation', function ($q) {
                    $q->where('status', 'In Progress');
                });
            })->get();

            $deliveryboy = DeliveryBoys::with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'email');
                }, 'investigations' => function ($q) use ($end_week) {
                    $q->with([
                        'investigation' => function ($q) {
                            $q->where('status', 'In Progress');
                        }
                    ])->where('status', 'Accepted')->whereDate('start_date', '<=', $end_week);
                }
            ])
            ->whereHas('investigations', function ($q) use ($end_week) {
                $q->where('status', 'Accepted')->whereDate('start_date', '<=', $end_week)->whereHas('investigation', function ($q) {
                    $q->where('status', 'In Progress');
                });
            })->get();
            
            $invoices = PerformaInvoice::select('id', 'invoice_no', 'status', 'investigation_id', 'invoice_id', 'client_id', 'created_at')->with(['client' => function($q){
                $q->select('id', 'user_id', 'payment_term_id')->with(['user' => function($qq){
                    $qq->select('id', 'name', 'email');
                }, 'paymentTerm' => function($qq){
                    $qq->select('id', DB::raw("(CASE WHEN term_name='Immediately' THEN 0 WHEN term_name='Immediately + 15' THEN 15 WHEN term_name='Immediately + 30' THEN 30 WHEN term_name='Immediately + 60' THEN 60 WHEN term_name='Immediately + 90' THEN 90 END) as term_name"));
                }]);
            }, 'investigation' => function($q){
                $q->select('id', 'work_order_number');
            }])->whereNull('invoice_id')->where('status', 'pending')->get();

            foreach($adminSM as $key => $value) {
                //send mail for investigator
                $investigator->map(function($inv) use ($value){
                    $invInvestigation = $inv->investigations->toArray();
                    if(!empty($invInvestigation)){
                        $days = (Carbon::now())->diffInDays((Carbon::parse($invInvestigation[0]['start_date'])));
                    } else {
                        $days = 15;
                    }
                    Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $inv->investigations, trans('form.Case'), (!empty($invInvestigation)?$invInvestigation[0]['investigation']['work_order_number']:[]), $days));
                });
    
                //send mail for deliveryboy
                $deliveryboy->map(function($del) use ($value){
                    $delInvestigation = $del->investigations->toArray();
                    if(!empty($delInvestigation)){
                        $days = (Carbon::now())->diffInDays((Carbon::parse($delInvestigation[0]['start_date'])));
                    } else {
                        $days = 15;
                    }
                    Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $del->investigations, trans('form.Case'), (!empty($delInvestigation)?$delInvestigation[0]['investigation']['work_order_number']:[]), $days));
                });

                //latePayment 
                $invoices->map(function($invoice) use ($value){
                    $invoiceNo = $invoice->toArray();
                    $invoiceDate = Carbon::parse($invoice->created_at);
                    $days = $invoiceDate->diffInDays(Carbon::now());
                    if(!is_null($invoice->client) && !is_null($invoice->client->paymentTerm)){
                        if($invoice->client->paymentTerm->term_name == 0 && ($days > 0)){
                            Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $invoice, trans('form.investigationinvoice.invoice'), (!empty($invoice)?$invoiceNo['invoice_no']:''), $days));
                        } else if($invoice->client->paymentTerm->term_name == 15 && ($days > 15)) {
                            Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $invoice, trans('form.investigationinvoice.invoice'), (!empty($invoice)?$invoiceNo['invoice_no']:''), $days));
                        } else if($invoice->client->paymentTerm->term_name == 30 && ($days > 30)) {
                            Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $invoice, trans('form.investigationinvoice.invoice'), (!empty($invoice)?$invoiceNo['invoice_no']:''), $days));
                        } else if($invoice->client->paymentTerm->term_name == 60 && ($days > 60)) {
                            Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $invoice, trans('form.investigationinvoice.invoice'), (!empty($invoice)?$invoiceNo['invoice_no']:''), $days));
                        } else if($invoice->client->paymentTerm->term_name == 90 && ($days > 90)) {
                            Mail::to($value['email'])->queue(new ExtremeDelayEmail($value, $invoice, trans('form.investigationinvoice.invoice'), (!empty($invoice)?$invoiceNo['invoice_no']:''), $days));
                        }
                    }
                });
            }
            return response()->json([
                'status' => 'success',
                'result' => trans('general.successfully_sent_mail', [], 'en')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong', [], 'en'),
                'exception' => $th->getMessage(),
            ]);
        } 
        
    }

    public function changeToUrgent(Request $request)
    {
        try {
            $investigation = Investigations::where('id', $request->id)->first();
            if($investigation->is_urgent){
                Investigations::where('id', $request->id)->update(['is_urgent' => 0]);
                return response()->json([
                    'status' => 'success',
                    'message' => trans('general.not_urgent')
                ]);
            } else {
                Investigations::where('id', $request->id)->update(['is_urgent' => 1]);
                return response()->json([
                    'status' => 'success',
                    'message' => trans('general.yes_urgent')
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function changeInvestigatorInvoiceAmount(Request $request)
    {
            if(!empty($request->value) && $request->value > 0 && (isAdmin() ||isSM() || isAccountant())){
                
                if (!is_array($request->pk)) {
                    $investigator_invoice =  InvestigatorInvoice::where('id',$request->pk)->first();
                    $investigator_invoice->amount = $request->value;
                    $investigator_invoice->save();
                }  else {
                    $investigator_invoice = (object)$request->pk;
                }

                InvestigatorInvestigations::where('investigator_id',$investigator_invoice->investigator_id)->where('investigation_id',$investigator_invoice->investigation_id)->update(['inv_cost'=>$request->value]);
                return response()->json(['success'=>true]);
            }
            return response()->json(['success'=>false]);
        try {
        } catch (Exception $e) {
            return response()->json(['success'=>false]);
        }
    }

    public function changeDelBoyInvoiceAmount(Request $request)
    {
        try {
            if(!empty($request->value) && $request->value > 0 && (isAdmin() ||isSM() || isAccountant())){
                if (!is_array($request->pk)) {

                    $delboy_invoice =  DeliveryboyInvoice::where('id',$request->pk)->first();
                    $docCost = DeliveryboyInvestigations::where('deliveryboy_id', $delboy_invoice->deliveryboy_id)->where('investigation_id', $delboy_invoice->investigation_id)->first();

                    $docCostAmount = 0;
                    if ($docCost)
                        $docCostAmount = $docCost->doc_cost;

                    $delboy_invoice->amount = $request->value + $docCostAmount;
                    $delboy_invoice->save();
                }  else {
                    $delboy_invoice = (object)$request->pk;
                }

                DeliveryboyInvestigations::where('deliveryboy_id',$delboy_invoice->deliveryboy_id)->where('investigation_id',$delboy_invoice->investigation_id)->update(['inv_cost'=>$request->value]);
                return response()->json(['success'=>true]);
            }
            return response()->json(['success'=>false]);
        } catch (Exception $e) {
            return response()->json(['success'=>false]);
        }
    }
}
