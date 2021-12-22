<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class InvestigationTransition extends Model
{
    public $table = 'investigation_transition';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investigation_id',
        'event',
        'investigation_status',
        'investigation_payment_status',
        'data',
        'perform_by',
        'updated_by',
        'created_at',
    ];

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }

    /**
     * Add Investigation Transitions.
     * @param $data array
     * format: ex. $data=['investigation_id'=>1,'event'=>'investigation_create','investigation_status'=>'New Request','investigation_payment_status'=>'pending,'data'=>'{"data": {"id": 1,"type": "created_by"}}','perform_by'=>1]
     * data field store the json_encode text as per above ex and below define format for particular event.
     * event field store the particular event we have defined
     * events like : investigation_create , investigation_delete,investigation_edit,investigation_changestatus,investigation_docupload,investigation_started,investigation_complete,investigation_assignee,investigation_assignedchangestatus,investigation_changeassignee
     * data field format we have set
     * 1) Create Investigation event=investigation_create, data=json('id','type') =>'id'=userid, 'type=created_by'
     * 2) Delete Investigation event=investigation_delete, data=json('id','type')  =>'id'=userid,'type=deleted_by'
     * 3) Update Investigation event=investigation_update, data=json('id','type')  =>'id'=userid,'type=edited_by'
     * 4) Investigation Document upload event=investigation_documentpload, data=json('id','type')  =>'id'=docid,'type=documentupload'
     * 5) Investigation Document delete event=investigation_documentdelete, data=json('id','type')  =>'id'=docid,'type=documentdelete'
     * 6) Investigator Assignee event=investigator_assigneed, data=json('id','type','status')  =>'id'=assigneeid,'type=investigator'
     * 7) Investigator Assignee status change event=investigator_changestatus, data=json('id','type','status')  =>'id'=assigneeid,'type=investigator' 'status=in progres/complete'
     * 8) Investigator Change Assignee event=investigator_changeassignee, data=json('id','type','status','oldid')  =>'id'=newassigneeid,'type=investigator' 'status=in progres/complete' 'oldid=oldassigneeid'
     * 9) Deliveryboy Assignee event=deliveryboy_assigneed, data=json('id','type','status')  =>'id'=assigneeid,'type=deliveryboy' 'status=in progres/complete'
     * 10) Deliveryboy Assignee status change event=deliveryboy_changestatus, data=json('id','type','status')  =>'id'=assigneeid,'type=deliveryboy' 'status=in progres/complete'
     * 11) Deliveryboy Change Assignee event=deliveryboy_changeassignee, data=json('id','type','status','oldid')  =>'id'=newassigneeid,'type=deliveryboy' 'status=in progres/complete' 'oldid=oldassigneeid'
     * 12) Mail Sent event=mail_send, data=json('id','type','mailtag','remark')  =>'id'=toid,'type=deliveryboy/client/investigator' 'mailtag=Assigned Investogaror/Payment Request' 'remark=remark'
     * 13) Investigation Action event=investigation_action, data=json('id','type','reason')  =>'id'=userid,'type=approved/declined','reason=reason'
     * 14) Investigation Change Status event=investigation_changestatus, data=json('id','type')  =>'id'=userid,'type=changed_by'
     * 15) Generate Invoice for Client event=investigation_generateinvoice, data=json('id','type')  =>'id'=invoiceid,'type=clientinvoice'
     * @return boolean
     */
    static function addTransion($data, $invid)
    {
        if(!is_null($invid)){
            $invdata = Investigations::select('status')->where('id', $invid)->first();
        }   
        $data['investigation_id'] = $invid;
        $data['investigation_status'] = !is_null($invid) ? $invdata->status : '';
        $data['investigation_payment_status'] = '';
        if($data['event'] == 'client_created' || $data['event'] == 'investigator_created' || $data['event'] == 'deliveryboy_created'){
            $newData = json_decode($data['data'], true);
            $userId = $newData['data']['id'];
        } else {
            $userId = auth()->user()->id;
        }
        $data['perform_by'] = $userId;
        if(!is_null($invid)){
            $inv_trans = InvestigationTransition::create($data);
        }

        //Add data to notifications table, so that user will get it
        InvestigationTransition::addNotifications($data, $invid);

        if (isset($inv_trans) && $inv_trans){
            return true;
        }else{
            if(is_null($invid)){
                return true;
            } else {
                return false;
            }
        }
    }

    static function getTransionData($eventid)
    {
        $invdata = InvestigationTransition::orderBY('created_at', 'ASC')->where('id', $eventid)->first();
        $evntuserdata = User::select('id', 'name')->where('id', $invdata->perform_by)->first();
        $eventjsondata = json_decode($invdata->data, true)['data'];
        $resdata = array();
        $isadminsm = 0;
        if (isAdmin() || isSM()) {
            $isadminsm = 1;
        }


        if ($invdata->event == 'investigation_create') {
            $resdata['event_title'] = trans('form.timeline.investigation_created');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.investigation_created') . $by;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if ($invdata->event == 'investigation_update') {
            $resdata['event_title'] = trans('form.timeline.investigation_updated');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.investigation_updated') . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if ($invdata->event == 'investigation_delete') {
            $resdata['event_title'] = trans('form.timeline.investigation_deleted');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.investigation_deleted') . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if ($invdata->event == 'investigation_documentupload') {
            $evntdocdata = InvestigationDocuments::withTrashed()->select('doc_name', 'file_name', 'file_extension', 'document_type', 'document_typeid')->where('id', $eventjsondata['id'])->first();
            $resdata['event_title'] = trans('form.timeline.document_uploaded');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.document_uploaded') . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['document_type'] = $evntdocdata->file_extension;
            if(config('app.locale') == 'hr'){
                $doctype = ($evntdocdata->document_typeid != 0) ?  '(' . (!empty($evntdocdata->documenttype) ? $evntdocdata->documenttype->hr_name : '') . ')' : '';
            } else {
                $doctype = ($evntdocdata->document_typeid != 0) ?  '(' . (!empty($evntdocdata->documenttype) ? $evntdocdata->documenttype->name : '') . ')' : '';
            }

            $resdata['document_name'] = $evntdocdata->doc_name .  $doctype;
            $resdata['document_filename'] = $evntdocdata->file_name;
        }
        if ($invdata->event == 'investigation_documentdelete') {
            $evntdocdata = InvestigationDocuments::withTrashed()->select('doc_name', 'file_name', 'file_extension', 'document_type', 'document_typeid')->where('id', $eventjsondata['id'])->first();
            $resdata['event_title'] = trans('form.timeline.document_deleted');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.document_deleted') . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['document_type'] = $evntdocdata->file_extension;
            $doctype = ($evntdocdata->document_typeid != 0) ?  '(' . $evntdocdata->documenttype->name . ')' : '';

            $resdata['document_name'] = $evntdocdata->doc_name . $doctype;
            $resdata['document_filename'] = $evntdocdata->file_name;
        }
        if ($invdata->event == 'investigator_assigneed') {

            $paymentNote = '';
            if (isset($eventjsondata['investigator_investigation_id'])) {
                $getData = InvestigatorInvestigations::find($eventjsondata['investigator_investigation_id']);
                if ($getData->note) {
                    $paymentNote = '<br>'.trans('form.investigation.note').' : '.$getData->note;
                }
            }


            $evntinvestigatordata = User::select('name')->whereHas('investigator', function ($query) use ($eventjsondata) {
                $query->where('id', $eventjsondata['id']);
            })->first();
            $desc = '';
            $resdata['event_title'] = trans('form.timeline.investigator_assigned');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $by2 = ($isadminsm) ?  '(' . $evntinvestigatordata->name . ')</br>' : '</br>';
            $desc .= trans('form.timeline.investigator_assigned') . $by2;
            if ($isadminsm) $desc .= trans('form.timeline.investigator_assigned') . $by;
            $desc .= $paymentNote;
            $resdata['event_desc'] = $desc;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            // ($invdata->investigation_status == 'Pending Approval')?trans('form.investigation_status.PendingApproval'):'';;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['investigator_assign_status'] = $eventjsondata['status'];
        }
        if ($invdata->event == 'investigator_changestatus') {

            $evntinvestigatordata = User::select('id', 'name')->whereHas('investigator', function ($query) use ($eventjsondata) {
                $query->where('id', $eventjsondata['id']);
            })->first();
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';

            $byName = "";
            if ($isadminsm) {
                $byName = $evntuserdata->name;
            } else if (isInvestigator()) {
                if (isset($evntinvestigatordata->id) && $evntinvestigatordata->id == $evntuserdata->id) {
                    $byName = $evntuserdata->name;
                } else {
                    $byName = trans('form.timeline.administrator');
                }
            } else {
                $byName = trans('form.timeline.user');
            }

            $resdata['event_title'] = trans('form.timeline.changed_assigned_investigator_status');
            $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['investigator_assign_status'] = trans('form.timeline_status.'.$eventjsondata['status']);

            if ($eventjsondata['status'] == "Investigation Started") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_started_inv');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Investigation Declined") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_declined_inv');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
                $resdata['reason'] = $eventjsondata['reason'];
            } else if ($eventjsondata['status'] == "Report Writing") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_started_rep_writing');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Report Submitted") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_submitted_report');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Modification Required") {
                $resdata['event_title'] = trans('form.timeline.has_mod_req');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Returned To Center") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_returned_center');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Completed") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_completed');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Not Completed") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_not_completed');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Completed With Findings") {
                // 
                $getInvestigation = '';
                if (isset($eventjsondata['investigator_investigation_id'])) {
                    $getInvest = InvestigatorInvestigations::find($eventjsondata['investigator_investigation_id']);

                    $getSummerSubject = '';
                    if($getInvest['completion_subject_summary']){
                        $getSubSum = json_decode($getInvest['completion_subject_summary'],true);

                        if (is_array($getSubSum)) {
                            foreach ($getSubSum as $key => $value) {
                                $getSubect = Subjects::find($key);
                                $getSummerSubject .= '<br>'.trans('form.investigation.final_summary').' '.(isset($getSubect->first_name) ? $getSubect->first_name : '') .' : '.$value;
                            }
                        }
                    }

                    $getFinalSummery = '';
                    if($getInvest['completion_summary']) {
                        $getFinalSummery = '<br>'.trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' : ' .$getInvest['completion_summary'];
                    }

                    $getInvestigation = $getFinalSummery.$getSummerSubject;
                }
                // 
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_completed_withfind');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by. $getInvestigation;
            } else if ($eventjsondata['status'] == "Completed Without Findings") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_completed_withoutfind');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Report Accepted") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_report_accepted');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Report Declined") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_report_declined');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Returned To Investigator") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_returned_to_investigator');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Delivered") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_delivered');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Not Delivered") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_not_delivered');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Final Report Submitted") {
                $getNote = '';
                if (isset($eventjsondata['investigation_investigation_id'])) {
                    $getInvestigatorInvestigationData = InvestigatorInvestigations::find($eventjsondata['investigation_investigation_id']);

                    $getNote = '<br> '.trans('form.investigation.note').' : '.$getInvestigatorInvestigationData->note;
                }

                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_not_delivered');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by. $getNote;
            }
        }
        if ($invdata->event == 'investigator_changeassignee') {
            $evntinvestigatordata = User::select('name')->whereHas('investigator', function ($query) use ($eventjsondata) {
                $query->where('id', $eventjsondata['id']);
            })->first();
            $desc = '';
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $by2 = ($isadminsm) ?  '(' . $evntinvestigatordata->name . ')</br>' : '</br>';
            $resdata['event_title'] = trans('form.timeline.changed_assigned_new_investigator');
            $desc .= trans('form.timeline.new_investigator_assigned') . $by2;
            if ($isadminsm) $desc .= trans('form.timeline.investigator_assigned') . $by;
            $resdata['event_desc'] =   $desc;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['investigator_assign_status'] = trans('form.timeline_status.'.$eventjsondata['status']);
        }
        if ($invdata->event == 'mail_send') {
            $desc = '';
            $resdata['event_title'] = trans('form.timeline.mail_send');
            $desc .= trans('form.timeline.mail_send_for') . trans('form.timeline_status.'.$eventjsondata['mailtag']) . '</br>';
            $desc .= $eventjsondata['remark'];
            $resdata['event_desc'] = $desc;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if ($invdata->event == 'investigation_action') {
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            if ($eventjsondata['type'] == 'declined') {
                $resdata['event_title'] = trans('form.timeline.investigation_decline');
                $resdata['event_desc'] = trans('form.timeline.investigation_decline') . $by;
                $resdata['decline_reason'] = trans('form.timeline.investigation_decline_reason') . $eventjsondata['reason'];
            } else {
                $resdata['event_title'] = trans('form.timeline.investigation_approve');
                $resdata['event_desc'] = trans('form.timeline.investigation_approve') . $by;
            }
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if ($invdata->event == 'investigation_changestatus') {
            $resdata['event_title'] = trans('form.timeline.investigation_status_updated');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.investigation_updated') . $by;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if($invdata->event == 'investigation_price_change') {
            $resdata['event_title'] = trans('form.timeline.investigation_updated');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.investigation_updated') . $by;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
        }
        if ($invdata->event == 'deliveryboy_assigneed') {
            $deliveryboyInvestigations = $extraDesc ='';
            if (isset($eventjsondata['deliveryboy_investigations_id'])) {
                $deliveryboyInvestigations = DeliveryboyInvestigations::find($eventjsondata['deliveryboy_investigations_id']);

                if ($deliveryboyInvestigations->note) {
                    $extraDesc = '<br> '.trans('form.investigation.note').' : '.$deliveryboyInvestigations->note;
                }
            }

            $evntinvestigatordata = User::select('name')->whereHas('deliveryboy', function ($query) use ($eventjsondata) {
                $query->where('id', $eventjsondata['id']);
            })->first();
            $desc = '';
            $resdata['event_title'] = trans('form.timeline.deliveryboy_assigned');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $by2 = ($isadminsm) ?  '(' . $evntinvestigatordata->name . ')</br>' : '</br>';
            $desc .= trans('form.timeline.deliveryboy_assigned') . $by2;
            if ($isadminsm) $desc .= trans('form.timeline.deliveryboy_assigned') . $by;
            $resdata['event_desc'] = $desc.$extraDesc;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['deliveryboy_assign_status'] = trans('form.timeline_status.'.$eventjsondata['status']);

        }
        if ($invdata->event == 'deliveryboy_changestatus') {

            $evntinvestigatordata = User::select('name')->whereHas('deliveryboy', function ($query) use ($eventjsondata) {
                $query->where('id', $eventjsondata['id']);
            })->first();
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $byName = ($isadminsm) ? $evntuserdata->name : trans('form.timeline.administrator');
            $resdata['event_title'] = trans('form.timeline.changed_assigned_deliveryboy_status');
            $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['deliveryboy_assign_status'] = trans('form.timeline_status.'.$eventjsondata['status']);

            if ($eventjsondata['status'] == "Returned To Center") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_returned_center');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Accepted") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_accepted_del');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Rejected") {
                $resdata['event_title'] = $byName . ' ' . trans('content.notificationdata.msg.has_rejected_del');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Done And Delivered") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_delivered');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Done And Not Delivered") {
                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_not_delivered');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by;
            } else if ($eventjsondata['status'] == "Return To Center") {
                $getInvestigation = '';
                if (isset($eventjsondata['deliveryboy_investigation_id'])) {
                    $getDeliveryBoyIn = DeliveryboyInvestigations::find($eventjsondata['deliveryboy_investigation_id']);

                    $getSummerSubject = '';
                    if($getDeliveryBoyIn['completion_subject_summary']){
                        $getSubSum = json_decode($getDeliveryBoyIn['completion_subject_summary'],true);
                        if (is_array($getSubSum)) {
                            $getSummerSubject = '<br>'.trans('form.investigation.final_summary').' : '.array_values($getSubSum)[0];
                        }
                    }

                    $getFinalSummery = '';
                    if($getDeliveryBoyIn['completion_summary']) {
                        $getFinalSummery = '<br>'.trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' : ' .$getDeliveryBoyIn['completion_summary'];
                    }

                    $getInvestigation = $getSummerSubject.$getFinalSummery;
                }

                $resdata['event_title'] = $byName . ' ' . trans('form.timeline.has_not_delivered');
                $resdata['event_desc'] = trans('form.timeline.status_updated') . $by. $getInvestigation;
            }
        }
        if ($invdata->event == 'deliveryboy_changeassignee') {
            $evntinvestigatordata = User::select('name')->whereHas('deliveryboy', function ($query) use ($eventjsondata) {
                $query->where('id', $eventjsondata['id']);
            })->first();
            $desc = '';
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $by2 = ($isadminsm) ?  '(' . $evntinvestigatordata->name . ')</br>' : '</br>';
            $resdata['event_title'] = trans('form.timeline.changed_assigned_new_deliveryboy');
            $desc .= trans('form.timeline.new_deliveryboy_assigned') . $by2;
            if ($isadminsm) $desc .= trans('form.timeline.deliveryboy_assigned') . $by;
            $resdata['event_desc'] =   $desc;
            $resdata['investigation_status'] = trans('form.timeline_status.'.$invdata->investigation_status);
            $resdata['investigation_payment_status'] = $invdata->investigation_payment_status;
            $resdata['deliveryboy_assign_status'] = trans('form.timeline_status.'.$eventjsondata['status']);
        }
        if ($invdata->event == 'investigation_generateinvoice') {
            $evntinvoicedata = PerformaInvoice::withTrashed()->select('id', 'invoice_no', 'amount', 'status')->where('id', $eventjsondata['id'])->first();
            $resdata['event_title'] = trans('form.timeline.invoice_genearted');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.invoice_genearted') . '(' . (!empty($evntinvoicedata)?$evntinvoicedata->invoice_no:'') . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['invoice_id'] = !empty($evntinvoicedata)?$evntinvoicedata->id:'';
            $resdata['invoice_no'] = !empty($evntinvoicedata)?$evntinvoicedata->invoice_no:'';
            $resdata['invoice_status'] = trans('form.timeline_status.'.(!empty($evntinvoicedata)?$evntinvoicedata->status:''));
        }

        if ($invdata->event == 'investigator_investigation_generateinvoice') {

            $resdata['event_title'] = trans('form.timeline.invoice_genearted');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.invoice_genearted') . '(' . (!empty($eventjsondata)?$eventjsondata['inv_no']:'') . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['invoice_id'] = !empty($eventjsondata)?$eventjsondata['id']:'';
            $resdata['invoice_no'] = !empty($evntinvoicedata)?$eventjsondata['inv_no']:'';
            $resdata['invoice_status'] = trans('form.timeline_status.'.(!empty($eventjsondata)?$eventjsondata['status']:''));
        }

        if ($invdata->event == 'deliveryboy_investigation_generateinvoice') {

            $resdata['event_title'] = trans('form.timeline.invoice_genearted');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.invoice_genearted') . '(' . (!empty($eventjsondata)?$eventjsondata['inv_no']:'') . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['invoice_id'] = !empty($eventjsondata)?$eventjsondata['id']:'';
            $resdata['invoice_no'] = !empty($evntinvoicedata)?$eventjsondata['inv_no']:'';
            $resdata['invoice_status'] = trans('form.timeline_status.'.(!empty($eventjsondata)?$eventjsondata['status']:''));
        }

        if ($invdata->event == 'investigation_invoicestatus_changed') {
            $evntinvoicedata = Invoice::withTrashed()->select('id', 'invoice_no', 'amount', 'status')->where('id', $eventjsondata['id'])->first();
            
            $resdata['event_title'] = trans('form.timeline.invoice_statuschange');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.invoice_statuschange') . '(' . (!empty($evntinvoicedata)?$evntinvoicedata->invoice_no:'') . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['invoice_id'] = !empty($evntinvoicedata)?$evntinvoicedata->id:'';
            $resdata['invoice_no'] = !empty($evntinvoicedata)?$evntinvoicedata->invoice_no:'';
            $resdata['invoice_status'] = trans('form.timeline_status.'.(!empty($evntinvoicedata)?$evntinvoicedata->status:''));
        }

        if ($invdata->event == 'ticket_create') {
            $evntinvoicedata = Tickets::withTrashed()->select('id', 'investigation_id', 'user_id', 'subject', 'type', 'message', 'status')->where('id', $eventjsondata['ticket_id'])->first();
            $resdata['event_title'] = trans('form.timeline.new_ticket_created');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.new_ticket_created') . ' '. trans('form.timeline.with_subject') . '(' . $evntinvoicedata->subject . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['id'] = $evntinvoicedata->id;
            $resdata['investigation_id'] = $evntinvoicedata->investigation_id;
            $resdata['status'] = trans('form.timeline_status.'.$evntinvoicedata->status);
        }
        if ($invdata->event == 'ticket_status_change') {
            $evntinvoicedata = Tickets::withTrashed()->select('id', 'investigation_id', 'user_id', 'subject', 'type', 'message', 'status')->where('id', $eventjsondata['ticket_id'])->first();
            $resdata['event_title'] = trans('form.timeline.ticket_statuschange');
            $by = ($isadminsm) ? ' ' . trans('general.by') . ' ' . $evntuserdata->name : '';
            $resdata['event_desc'] = trans('form.timeline.ticket_statuschange') . ' '. trans('form.timeline.with_subject') . '(' . $evntinvoicedata->subject . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['id'] = $evntinvoicedata->id;
            $resdata['investigation_id'] = $evntinvoicedata->investigation_id;
            $resdata['status'] = trans('form.timeline_status.'.$evntinvoicedata->status);
        }

        if ($invdata->event == 'investigation_markPaid_client') {
            
            $invoiceDoc = $extradescription = '';
            if (isset($eventjsondata['invoice_doc_id'])) {
                $invoiceDoc = InvoiceDocuments::find($eventjsondata['invoice_doc_id']);
                if(config('app.locale') == 'hr'){
                    $doctype = ($invoiceDoc->document_typeid != 0) ?  '(' . (!empty($invoiceDoc->documenttype) ? $invoiceDoc->documenttype->hr_name : '') . ')' : '';
                } else {
                    $doctype = ($invoiceDoc->document_typeid != 0) ?  '(' . (!empty($invoiceDoc->documenttype) ? $invoiceDoc->documenttype->name : '') . ')' : '';
                }

                // $resdata['document_type'] = $invoiceDoc->file_extension;
                // $resdata['document_name'] = $invoiceDoc->doc_name .  $doctype;
                // $resdata['document_filename'] = '../invoice-pay-docs/'.$invoiceDoc->file_name;

                if ($eventjsondata['client_payment_notes']) {
                    $extradescription = '<br> '.trans('form.investigation.note').' : '.$eventjsondata['client_payment_notes'];
                }
            }

            $evntinvoicedata = Investigations::withTrashed()->where('id', $eventjsondata['id'])->first();
            $evntinvoicedata = Investigations::withTrashed()->where('id', $invdata->investigation_id)->first();
            $performaInvoice = $evntinvoicedata->clientinvoice->first();
            
            $userName = $evntinvoicedata['user']['name'];

            $resdata['event_title'] = trans('form.timeline.investigation_markas_paid_client');
            $by = trans('general.by') . ' ' . $userName;
            $resdata['event_desc'] = trans('form.timeline.invoice_statuschange') . '(' . $performaInvoice->invoice_no . ')' . $by.$extradescription;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['invoice_id'] = $performaInvoice->id;
            $resdata['invoice_no'] = $performaInvoice->invoice_no;
            $resdata['invoice_status'] = trans('form.timeline_status.'.$performaInvoice->status);
        }

        if ($invdata->event == 'investigation_markPaid_investigator') {
            $evntinvoicedata = Investigations::withTrashed()->where('id', $invdata->id)->first();
            // $evntinvoicedata = Investigations::withTrashed()->where('id', $invdata->investigation_id)->first();
            // $performaInvoice = $evntinvoicedata->clientinvoice->first();
            $userName = $eventjsondata['perform_by'];

            $resdata['event_title'] = trans('form.timeline.investigation_markas_paid_client');
            $by = trans('general.by') . ' ' . $userName;
            $resdata['event_desc'] = trans('form.timeline.invoice_statuschange') . '(' . $eventjsondata['invoice_no'] . ')' . $by;
            //$resdata['investigation_status'] = $invdata->investigation_status;

            $resdata['invoice_id'] = $eventjsondata['id'];
            $resdata['invoice_no'] = $eventjsondata['invoice_no'];
            $resdata['invoice_status'] = trans('form.timeline_status.Paid');
        }


        $resdata['event_date'] = gmdate('d M h:i:s A', strtotime($invdata->created_at));
        $resdata['event'] = $invdata->event;
        $resdata['event_by'] = $invdata->perform_by;

        return $resdata;
    }

    /**
     * Add User Notifications as per the event perform on addTransion() function.
     * @param $data array
     *  @return boolean
     */
    static function addNotifications($data, $invid)
    {
        $msg = '';
        $hrMsg = '';
        $invdata = Investigations::where('id', $invid)->first();
        $isadminsm = 0;

        if (isAdmin() || isSM()) {
            $isadminsm = 1;
        }

        if ($data['event'] == 'investigation_create') {
            $notidata = [];
            $message = [];
            if ($isadminsm) {
                //$notidata['user_id'] = $invdata->user_id;

                $msg = trans('content.notificationdata.msg.new_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_created', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                $hrMsg = trans('content.notificationdata.msg.new_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_created', [], 'hr'). ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
            }
            if (isClient()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                $notidata['user_id'] =  $adminid->id;

                $msg = auth()->user()->name . ' ' . trans('content.notificationdata.msg.has_created_new_inv', [], 'en') . '(' . $invdata->work_order_number . ') ';
                $msgby = $msg;

                $hrMsg = auth()->user()->name . ' ' . trans('content.notificationdata.msg.has_created_new_inv', [], 'hr') . '(' . $invdata->work_order_number . ') ';
            }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;

            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            //UserNotification::create($notidata);
            insertNotifications($notidata, ['admin','sm'], [], $message);
        }
        if ($data['event']  == 'investigation_delete') {
            $notidata = [];
            $message = [];
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
            }
            if (isClient()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                $notidata['user_id'] =  $adminid->id;
            }
            $msg = trans('content.notificationdata.msg.delete_investigation', [], 'en') . '(' . $invdata->work_order_number . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.delete_investigation', [], 'hr') . '(' . $invdata->work_order_number . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            //UserNotification::create($notidata);

            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin','sm', 'user'], [$invdata->user_id], $message);
        }
        if ($data['event']  == 'investigation_update') {
            $notidata = [];
            $message = [];
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
            }
            if (isClient()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                $notidata['user_id'] =  $adminid->id;
            }
            $msg = trans('content.notificationdata.msg.updated_investigation', [], 'en') . '(' . $invdata->work_order_number . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.updated_investigation', [], 'hr') . '(' . $invdata->work_order_number . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            //UserNotification::create($notidata);

            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin','sm', 'user'], [$notidata['user_id']], $message);
        }
        if ($data['event']  == 'investigation_documentupload') {
            $notidata = [];
            $message = [];
            $msg = trans('content.notificationdata.msg.new_document_uploaded_on', [], 'en') . '(' . $invdata->work_order_number . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.new_document_uploaded_on', [], 'hr') . '(' . $invdata->work_order_number . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;

            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
                //UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$invdata->user_id], $message);
            }
            if (isInvestigator()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$adminid->id, $invdata->user_id], $message);
            }
            if (isDeliveryboy()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$adminid->id, $invdata->user_id], $message);
            }
            if (isClient()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$adminid->id, $invdata->user_id], $message);
            }
        }
        if ($data['event']  == 'investigation_documentdelete') {
            $notidata = [];
            $message = [];
            $msg = trans('content.notificationdata.msg.document_deleted_on', [], 'en') . '(' . $invdata->work_order_number . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.document_deleted_on', [], 'hr') . '(' . $invdata->work_order_number . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;

            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
                UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$invdata->user_id], $message);
            }
            if (isInvestigator()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$adminid->id, $invdata->user_id], $message);
            }
            if (isDeliveryboy()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$adminid->id, $invdata->user_id], $message);
            }
            if (isClient()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin','sm'], [$adminid->id, $invdata->user_id], $message);
            }
        }
        if ($data['event']  == 'investigator_assigneed') {
            $notidata = [];
            $message = [];
            $eventData = json_decode($data['data'], true);

            if ($isadminsm) {
                $notidata['investigation_id'] = $invid;
                $notidata['event'] = $data['event'];
                $notidata['data'] = $data['data'];
                $notidata['with_link'] = 1;
                $notidata['perform_by'] = auth()->user()->id;

                //Send to investigator (Action performed by Admin)
                $msg = trans('content.notificationdata.msg.new_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_assigned', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');

                $hrMsg = trans('content.notificationdata.msg.new_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_assigned', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                $notidata['message'] = $msgby;
                $notidata['hr_message'] = $hrMsg;
                $notidata['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                //UserNotification::create($notidata);
                $message['en'] = $msgby;
                $message['hr'] = $hrMsg;
                insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);

                //Send to client (Action performed by Admin)
                // $msg = trans('content.notificationdata.msg.invr_assigned', [], 'en') . ' (' . $invdata->work_order_number . ') ';
                // $hrMsg = trans('content.notificationdata.msg.invr_assigned', [], 'hr') . ' (' . $invdata->work_order_number . ') ';
                // $msgby = $msg;
                // $notidata['message'] = $msgby;
                // $notidata['hr_message'] = $hrMsg;
                // $notidata['user_id'] = $invdata->user_id;
                // //UserNotification::create($notidata);

                // insertNotifications($notidata, ['user'], [$invdata->user_id]);
            }
        }
        if ($data['event']  == 'investigator_changestatus') {
            $notidata = [];
            $toClientData = [];
            $toInvestigatorData = [];
            $toAdminData = [];
            $eventData = json_decode($data['data'], true);
            $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();

            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['with_link'] = 1;
            $notidata['perform_by'] = auth()->user()->id;
            $msgby = $hrMsg = "";
            if ($eventData['data']['status'] == 'Investigation Started') {
                //Send to client (Action performed by Investigator)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.InvestigationStarted', [], 'en');
                // $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.InvestigationStarted', [], 'hr');
                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $invrname = auth()->user()->name;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.InvestigationStarted', [], 'en');
                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.InvestigationStarted', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] =  $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Investigation Declined') {
                // $eventData = json_decode($data['data'], true);
                //Send to client (Action performed by Investigator)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.InvestigationDeclined', [], 'en');
                
                // $$hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.InvestigationDeclined', [], 'hr');

                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $$hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $invrname = auth()->user()->name;
                    
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en') .' '.trans('form.investigation_status.InvestigationDeclined', [], 'en'). ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name.' '. trans('form.timeline.investigation_decline_reason') . $eventData['data']['reason'];
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr') .' '.trans('form.investigation_status.InvestigationDeclined', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name.' '. trans('form.timeline.investigation_decline_reason') . $eventData['data']['reason'];
                    
                    $msgby = $msg;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] =  $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Report Writing') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ReportWriting', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ReportWriting', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ReportWriting', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ReportWriting', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ReportWriting', [], 'en');

                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ReportWriting', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] =  $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Report Submitted') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ReportSubmitted', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ReportSubmitted', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ReportSubmitted', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ReportSubmitted', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ReportSubmitted', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ReportSubmitted', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['message'] = $hrMsg;
                    $toAdminData['user_id'] =  $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Modification Required') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ModificationRequired', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ModificationRequired', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.ModificationRequired', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.ModificationRequired', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }
            }
            if ($eventData['data']['status'] == 'Returned To Center') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_center', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_center', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_center', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_center', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_center', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_center', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Completed') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.Completed', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.Completed', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.Completed', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.Completed', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }
            }
            if ($eventData['data']['status'] == 'Not Completed') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.NotCompleted', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.NotCompleted', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.NotCompleted', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.NotCompleted', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [] , 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [] , 'en').' '.trans('form.investigation_status.NotCompleted', [] , 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.NotCompleted', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [] , 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Completed With Findings') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.completed_findings', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.completed_findings', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.completed_findings', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.completed_findings', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.completed_findings', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.completed_findings', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Completed Without Findings') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.completed_without_findings', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.completed_without_findings', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.completed_without_findings', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.completed_without_findings', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.completed_without_findings', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.completed_without_findings', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            } 
            if ($eventData['data']['status'] == 'Report Accepted') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.accept_report', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.accept_report', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.accept_report', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.accept_report', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.accept_report', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.accept_report', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            } 
            if ($eventData['data']['status'] == 'Report Declined') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.report_declined', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.report_declined', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.report_declined', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.report_declined', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigator_investigation_status.report_declined', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigator_investigation_status.report_declined', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Returned To Investigator') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_investigator', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_investigator', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_investigator', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_investigator', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Investigator)
                if (isInvestigator()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_investigator', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_investigator', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            } 
            if ($eventData['data']['status'] == 'Delivered') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Delivered', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Delivered', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Delivered', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Delivered', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }
            }
            if ($eventData['data']['status'] == 'Not Delivered') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.NotDelivered', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.NotDelivered', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.NotDelivered', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.NotDelivered', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }
            }
            if ($eventData['data']['status'] == 'Cancel') {
                //Send to client (Action performed by Admin)
                $toClientData = $notidata;
                $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Cancel', [], 'en');
                
                $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Cancel', [], 'hr');

                $msgby = $msg;
                $toClientData['message'] = $msgby;
                $toClientData['hr_message'] = $hrMsg;
                $toClientData['user_id'] = $invdata->user_id;

                //Send to Investigator (Action performed by Admin)
                if ($isadminsm) {
                    $toInvestigatorData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Cancel', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Cancel', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toInvestigatorData['message'] = $msgby;
                    $toInvestigatorData['hr_message'] = $hrMsg;
                    $toInvestigatorData['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                }
            } 
            // else {
            //     $msg = trans('content.notificationdata.msg.status_changed_on_investigation') . '(' . $invdata->work_order_number . ')';
            //     $msgby = $msg . ' ' . trans('general.by') . ' ' . auth()->user()->name;
            //     $notidata['message'] = $msgby;

            //     if ($isadminsm) {
            //         $notidata['user_id'] = $invdata->user_id;
            //         UserNotification::create($notidata);
            //     }
            //     if (isInvestigator()) {
            //         $notidata['user_id'] =  $adminid->id;
            //         UserNotification::create($notidata);
            //         $notidata['user_id'] =  $invdata->user_id;
            //         UserNotification::create($notidata);
            //     }
            // }

            // if (!empty($toClientData)) {
            //     //UserNotification::create($toClientData);
            //     insertNotifications($toClientData, ['user'], [$toClientData['user_id']]);
            // }
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            if (!empty($toInvestigatorData)) {
                //UserNotification::create($toInvestigatorData);
                insertNotifications($toInvestigatorData, ['user'], [$toInvestigatorData['user_id']], $message);
            }
            if (!empty($toAdminData)) {
                //UserNotification::create($toAdminData);
                insertNotifications($toAdminData, ['admin','sm'], [], $message);
            }
        }
        if ($data['event']  == 'investigator_changeassignee') {
            $notidata = [];
            $message = [];
            $eventData = json_decode($data['data'], true);

            if ($isadminsm) {
                $msg = trans('content.notificationdata.msg.new_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.is_assigned', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');

                $hrMsg = trans('content.notificationdata.msg.new_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.is_assigned', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                $notidata['message'] = $msgby;
                $notidata['hr_message'] = $hrMsg;
                $notidata['investigation_id'] = $invid;
                $notidata['event'] = $data['event'];
                $notidata['data'] = $data['data'];
                $notidata['perform_by'] = auth()->user()->id;
                $notidata['with_link'] = 1;
                $notidata['user_id'] = AppHelper::getUserIdFromInvestigatorId($eventData['data']['id']);
                //UserNotification::create($notidata);
                $message['en'] = $msgby;
                $message['hr'] = $hrMsg;
                insertNotifications($notidata, [], [], $message);
            }
        }
        if ($data['event']  == 'investigation_action') {
            if ($isadminsm) {
                if ($invdata->status == 'Approved') {
                    $msg = trans('content.notificationdata.msg.your_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_approved', [], 'en');

                    $hrMsg = trans('content.notificationdata.msg.your_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_approved', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
                }
                if ($invdata->status == 'Declined') {
                    $msg = trans('content.notificationdata.msg.your_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_declined', [], 'en');

                    $hrMsg = trans('content.notificationdata.msg.your_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_declined', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
                }
                $message = [];
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                $notidata['message'] = $msgby;
                $notidata['hr_message'] = $hrMsg;
                $notidata['investigation_id'] = $invid;
                $notidata['event'] = $data['event'];
                $notidata['data'] = $data['data'];
                $notidata['perform_by'] = auth()->user()->id;
                $notidata['with_link'] = 1;
                $notidata['user_id'] = $invdata->user_id;
                //UserNotification::create($notidata);

                $message['en'] = $msgby;
                $message['hr'] = $hrMsg;
                insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
            }
        }
        if ($data['event']  == 'investigation_changestatus') {
            $notidata = [];
            $message = [];
            $msg = trans('content.notificationdata.msg.investigation', [], 'en') . '(' . $invdata->work_order_number . ')' . trans('content.notificationdata.msg.has_been_marked', [], 'en') .' '.$invdata->status;
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr') . '(' . $invdata->work_order_number . ')' . trans('content.notificationdata.msg.has_been_marked', [], 'hr') .' '.$invdata->status . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;

            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
                //UserNotification::create($notidata);
                insertNotifications($notidata, [], [$notidata['user_id']], $message);
            }
            if (isInvestigator() || isDeliveryboy()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                // $notidata['user_id'] =  $adminid->id;
                // UserNotification::create($notidata);
                // $notidata['user_id'] =  $invdata->user_id;
                // UserNotification::create($notidata);

                insertNotifications($notidata, ['admin', 'sm'], [$adminid->id, $invdata->user_id], $message);
            }
        }
        if ($data['event']  == 'deliveryboy_assigneed') {
            $notidata = [];
            $message = [];
            $eventData = json_decode($data['data'], true);

            if ($isadminsm) {
                $notidata['investigation_id'] = $invid;
                $notidata['event'] = $data['event'];
                $notidata['data'] = $data['data'];
                $notidata['with_link'] = 1;
                $notidata['perform_by'] = auth()->user()->id;

                //Send to delivery boy (Action performed by Admin)
                $msg = trans('content.notificationdata.msg.new_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_assigned', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                
                $hrMsg = trans('content.notificationdata.msg.new_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_assigned', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
                
                $notidata['message'] = $msgby;
                $notidata['hr_message'] = $hrMsg;
                $notidata['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                //UserNotification::create($notidata);  
                $message['en'] = $msgby;
                $message['hr'] = $hrMsg;
                insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);

                //Send to client (Action performed by Admin)
                // $msg = trans('content.notificationdata.msg.delboy_assigned', [], 'en') . ' (' . $invdata->work_order_number . ') ';

                // $hrMsg = trans('content.notificationdata.msg.delboy_assigned', [], 'hr') . ' (' . $invdata->work_order_number . ') ';
                
                // $msgby = $msg;
                // $notidata['message'] = $msgby;
                // $notidata['hr_message'] = $hrMsg;
                // $notidata['user_id'] = $invdata->user_id;
                // //UserNotification::create($notidata);

                // insertNotifications($notidata, ['user'], [$notidata['user_id']]);
            }
        }
        if ($data['event']  == 'deliveryboy_changestatus') {
            $msgby = $hrMsg = "";
            $notidata = [];
            $toClientData = [];
            $toDeliveryboyData = [];
            $toAdminData = [];
            $eventData = json_decode($data['data'], true);
            $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();

            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['with_link'] = 1;
            $notidata['perform_by'] = auth()->user()->id;

            if ($eventData['data']['status'] == 'Return To Center') {
                //Send to client (Action performed by Delivery boy)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_center', [], 'en');
                
                // $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_center', [], 'hr');

                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Delivery boy (Action performed by Admin)
                if ($isadminsm) {
                    $toDeliveryboyData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_center', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_center', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toDeliveryboyData['message'] = $msgby;
                    $toDeliveryboyData['hr_message'] = $hrMsg;
                    $toDeliveryboyData['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Delivery boy)
                if (isDeliveryboy()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.investigation_status.return_to_center', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.investigation_status.return_to_center', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Accepted') {
                //Send to client (Action performed by Delivery boy)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Accepted', [], 'en');
                
                // $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Accepted', [], 'hr');

                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Delivery boy (Action performed by Admin)
                if ($isadminsm) {
                    $toDeliveryboyData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Accepted', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Accepted', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toDeliveryboyData['message'] = $msgby;
                    $toDeliveryboyData['hr_message'] = $hrMsg;
                    $toDeliveryboyData['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Delivery boy)
                if (isDeliveryboy()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Accepted', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Accepted', [], 'hr'). ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Rejected') {
                //Send to client (Action performed by Delivery boy)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Rejected', [], 'en');
                
                // $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Rejected', [], 'hr');

                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Delivery boy (Action performed by Admin)
                if ($isadminsm) {
                    $toDeliveryboyData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Rejected', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Rejected') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toDeliveryboyData['message'] = $msgby;
                    $toDeliveryboyData['hr_message'] = $hrMsg;
                    $toDeliveryboyData['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                }

                //Send to Admin (Action performed by Delivery boy)
                if (isDeliveryboy()) {
                    $toAdminData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Rejected', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Rejected', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;
                    $toAdminData['message'] = $msgby;
                    $toAdminData['hr_message'] = $hrMsg;
                    $toAdminData['user_id'] = $adminid->id;
                }
            }
            if ($eventData['data']['status'] == 'Done And Delivered') {
                //Send to client (Action performed by Admin)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.DoneAndDelivered', [], 'en');
                
                // $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.DoneAndDelivered', [], 'hr');

                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Delivery boy (Action performed by Admin)
                if ($isadminsm) {
                    $toDeliveryboyData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.DoneAndDelivered', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.DoneAndDelivered', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator');
                    $toDeliveryboyData['message'] = $msgby;
                    $toDeliveryboyData['hr_message'] = $hrMsg;
                    $toDeliveryboyData['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                }
            }
            if ($eventData['data']['status'] == 'Done And Not Delivered') {
                //Send to client (Action performed by Admin)
                // $toClientData = $notidata;
                // $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.DoneAndNotDelivered', [], 'en');
                
                // $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.DoneAndNotDelivered', [], 'hr');

                // $msgby = $msg;
                // $toClientData['message'] = $msgby;
                // $toClientData['hr_message'] = $hrMsg;
                // $toClientData['user_id'] = $invdata->user_id;

                //Send to Delivery boy (Action performed by Admin)
                if ($isadminsm) {
                    $toDeliveryboyData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.DoneAndNotDelivered', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.DoneAndNotDelivered', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toDeliveryboyData['message'] = $msgby;
                    $toDeliveryboyData['hr_message'] = $hrMsg;
                    $toDeliveryboyData['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                }
            }
            if ($eventData['data']['status'] == 'Cancel') {

                //Send to Delivery boy (Action performed by Admin)
                if ($isadminsm) {
                    $toDeliveryboyData = $notidata;
                    $msg = trans('content.notificationdata.msg.investigation', [], 'en'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.deliveryboy_investigation_status.Cancel', [], 'en');
                    
                    $hrMsg = trans('content.notificationdata.msg.investigation', [], 'hr'). ' (' . $invdata->work_order_number . ') ' . trans('content.notificationdata.msg.has_been_marked', [], 'hr').' '.trans('form.deliveryboy_investigation_status.Cancel', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'hr');

                    $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');
                    $toDeliveryboyData['message'] = $msgby;
                    $toDeliveryboyData['hr_message'] = $hrMsg;
                    $toDeliveryboyData['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                }
            } 
            // else {
            //     $msg = trans('content.notificationdata.msg.status_changed_on_investigation') . '(' . $invdata->work_order_number . ')';
            //     $msgby = $msg . ' ' . trans('general.by') . ' ' . auth()->user()->name;
            //     $notidata['message'] = $msgby;

            //     if ($isadminsm) {
            //         $notidata['user_id'] = $invdata->user_id;
            //         UserNotification::create($notidata);
            //         $deliveryuserid = DeliveryBoys::select('user_id')->where('id', $invdata->deliveryboys[0]->deliveryboy_id)->first();
            //         $notidata['user_id'] = $deliveryuserid->user_id;
            //         UserNotification::create($notidata);
            //     }
            //     if (isDeliveryboy()) {
            //         $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
            //         $notidata['user_id'] =  $adminid->id;
            //         UserNotification::create($notidata);
            //         $notidata['user_id'] =  $invdata->user_id;
            //         UserNotification::create($notidata);
            //     }
            // }

            // if (!empty($toClientData)) {
            //     //UserNotification::create($toClientData);
            //     insertNotifications($toClientData, ['user'], [$toClientData['user_id']]);
            // }
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            if (!empty($toDeliveryboyData)) {
                //UserNotification::create($toDeliveryboyData);
                insertNotifications($toDeliveryboyData, ['user'], [$toDeliveryboyData['user_id']], $message);
            }
            if (!empty($toAdminData)) {
                //UserNotification::create($toAdminData);
                insertNotifications($toAdminData, ['admin','sm'], [], $message);
            }
        }
        if ($data['event']  == 'deliveryboy_changeassignee') {
            $notidata = [];
            $message = [];
            $eventData = json_decode($data['data'], true);

            if ($isadminsm) {
                $msg = trans('content.notificationdata.msg.new_investigation', [], 'en') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.is_assigned', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                $hrMsg = trans('content.notificationdata.msg.new_investigation', [], 'hr') . '(' . $invdata->work_order_number . ') ' . trans('content.notificationdata.is_assigned', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

                $notidata['message'] = $msgby;
                $notidata['hr_message'] = $hrMsg;
                $notidata['investigation_id'] = $invid;
                $notidata['event'] = $data['event'];
                $notidata['data'] = $data['data'];
                $notidata['perform_by'] = auth()->user()->id;
                $notidata['with_link'] = 1;
                $notidata['user_id'] = AppHelper::getUserIdFromDeliveryboyId($eventData['data']['id']);
                //UserNotification::create($notidata);
                $message['en'] = $msgby;
                $message['hr'] = $hrMsg;
                insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
            }
        }
        if ($data['event']  == 'investigation_generateinvoice') {
            $notidata = [];
            $message = [];
            $newData = json_decode($data['data'], true);
            $invoice = PerformaInvoice::where('id', $newData['data']['id'])->first();
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
            }
            $msg = trans('content.notificationdata.msg.invoice_generate', [], 'en') . '(' . $invoice->invoice_no . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.invoice_generate', [], 'hr') . '(' . $invoice->invoice_no . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            $notidata['redirect_link'] = '/invoices/invoice/'.Crypt::encrypt($invoice->id).'/pinvoice';
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
        }

        if ($data['event']  == 'investigator_investigation_generateinvoice') {
            $notidata = [];
            $message = [];
            $newData = json_decode($data['data'], true);
            // $invoice = PerformaInvoice::where('id', $newData['data']['id'])->first();
            if ($isadminsm) {
                $notidata['user_id'] = $newData['data']['user_id'];
            }
            $msg = trans('content.notificationdata.msg.invoice_generate', [], 'en') . '(' . $newData['data']['inv_no'] . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.invoice_generate', [], 'hr') . '(' . $newData['data']['inv_no'] . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            $notidata['redirect_link'] = '/investigator-invoice/'.Crypt::encrypt($newData['data']['id']);
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
        }

        if ($data['event']  == 'deliveryboy_investigation_generateinvoice') {
            $notidata = [];
            $message = [];
            $newData = json_decode($data['data'], true);
            // $invoice = PerformaInvoice::where('id', $newData['data']['id'])->first();
            if ($isadminsm) {
                $notidata['user_id'] = $newData['data']['user_id'];
            }
            $msg = trans('content.notificationdata.msg.invoice_generate', [], 'en') . '(' . $newData['data']['inv_no'] . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.invoice_generate', [], 'hr') . '(' . $newData['data']['inv_no'] . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            $notidata['redirect_link'] = '/deliveryboy/invoice/'.Crypt::encrypt($newData['data']['id']);
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
        }

        if ($data['event']  == 'investigation_invoicestatus_changed') {
            $notidata = [];
            $message = [];
            $newData = json_decode($data['data'], true);
            
            $invoice = PerformaInvoice::where('invoice_id', $newData['data']['id'])->first();
            // print_r($invoice->client);die;
            if ($isadminsm || isSM() || isAccountant()) {
                $notidata['user_id'] = $invoice->client->user->id;
            }
            if($newData['data']['status'] == 'Partial Paid'){
                $msg = trans('content.notificationdata.msg.invoice', [], 'en') . ' (' . $invoice->invoice_no . ') ' .trans('content.notificationdata.msg.has_been_marked', [], 'en').' '.trans('form.timeline_status.Partial Paid', [], 'en').' '.trans('general.with', [], 'en').' '.trans('general.money_symbol').$newData['data']['parital_amount'];
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                $hrMsg = trans('content.notificationdata.msg.invoice', [], 'hr') . ' (' . $invoice->invoice_no . ') ' .trans('content.notificationdata.msg.has_been_marked', [], 'hr') . ' '.trans('form.timeline_status.Partial Paid', [], 'en').' '.trans('general.with', [], 'en').' '.trans('general.money_symbol').$newData['data']['parital_amount'] . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
            } else {
                $msg = trans('content.notificationdata.msg.invoice', [], 'en') . ' (' . $invoice->invoice_no . ') ' .trans('content.notificationdata.msg.mark_as_paid_by_client', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                $hrMsg = trans('content.notificationdata.msg.invoice', [], 'hr') . ' (' . $invoice->invoice_no . ') ' .trans('content.notificationdata.msg.mark_as_paid_by_client', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
            }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            $notidata['redirect_link'] = '/invoices/invoice/'.Crypt::encrypt($invoice->id).'/pinvoice';
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin','sm','user'], [$notidata['user_id']], $message);
        }
        if ($data['event'] == 'ticket_create') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $ticketData = Tickets::where('id', $newData['data']['ticket_id'])->first();
            if ($isadminsm) {
                $notidata['user_id'] = $ticketData->user_id;

                $msg = trans('content.notificationdata.msg.new_ticket', [], 'en') . '(' . $ticketData->id . ') ' . trans('content.notificationdata.msg.has_created', [], 'en');
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                $hrMsg = trans('content.notificationdata.msg.new_ticket', [], 'hr') . '(' . $ticketData->id . ') ' . trans('content.notificationdata.msg.has_created', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
            }
            if (isClient()) {
                $adminid = UserTypes::select('id')->where('type_name', env('USER_TYPE_ADMIN'))->first();
                $notidata['user_id'] =  $adminid->id;

                $msg = auth()->user()->name . ' ' . trans('content.notificationdata.msg.has_created_new_ticket', [], 'en') . '(' . $ticketData->subject . ') ';
                $msgby = $msg;

                $hrMsg = auth()->user()->name . ' ' . trans('content.notificationdata.msg.has_created_new_ticket', [], 'hr') . '(' . $ticketData->subject . ') ';
            }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $ticketData->investigation_id;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 0;
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin','sm','user'], [$notidata['user_id']], $message);
        }
        if ($data['event'] == 'ticket_status_change') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $status = null;
            $ticketData = Tickets::where('id', $newData['data']['ticket_id'])->first();
            if ($isadminsm) {
                $notidata['user_id'] = $ticketData->user_id;
                if($ticketData->status == "Open") {
                    $status = trans('form.ticket.open');
                } else {
                    $status = trans('form.ticket.close');
                }
                $msg = trans('content.notificationdata.msg.update_ticket', [], 'en') . '(' . $ticketData->subject . ') ' . trans('content.notificationdata.msg.status_changed', [], 'en').' '.$status;
                $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

                $hrMsg = trans('content.notificationdata.msg.update_ticket', [], 'hr') . '(' . $ticketData->subject . ') ' . trans('content.notificationdata.msg.status_changed', [], 'hr').' '.$status . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
            }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $ticketData->investigation_id;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 0;
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin','sm','user'], [$notidata['user_id']], $message);
        }
        if ($data['event'] == 'send_message') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $status = null;
            $ticketData = Tickets::where('id', $newData['data']['id'])->first();
            $userData = User::where('id', $ticketData->user_id)->first();
            $msgby='';
            // $notidata['user_id'] = Auth::id();
            // if ($isadminsm) {
            $notidata['user_id'] = $ticketData->user_id;
            
            $msg = trans('form.email_tem.ticket_create.new_message', [], 'en') . $ticketData->subject;
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('form.email_tem.ticket_create.new_message', [], 'hr') . $ticketData->subject . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;
            // }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $ticketData->investigation_id?$ticketData->investigation_id:NULL;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            $notidata['redirect_link'] = '/ticket/messages/'.Crypt::encrypt($ticketData->id);
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin','user'], [$notidata['user_id']], $message);
        }
        if ($data['event']  == 'investigation_price_change') {
            $notidata = [];
            $message = [];
            if ($isadminsm) {
                $notidata['user_id'] = $invdata->user_id;
            }
            $msg = trans('content.notificationdata.msg.updated_investigation', [], 'en') . '(' . $invdata->work_order_number . ')';
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('content.notificationdata.msg.updated_investigation', [], 'hr') . '(' . $invdata->work_order_number . ')' . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
        }
        if ($data['event']  == 'investigation_markPaid_client') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $msg = trans('form.ticket.investigation', [], 'en').' (' . $invdata->work_order_number . ') '.trans('content.notificationdata.msg.mark_as_paid_by_client', [], 'en') ;
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('form.ticket.investigation', [], 'hr').' (' . $invdata->work_order_number . ') '.trans('content.notificationdata.msg.mark_as_paid_by_client', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            $notidata['redirect_link'] = '/invoices/invoice/'.Crypt::encrypt($newData['data']['id']).'/invoice';
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            if (isClient()){
                $notidata['user_id'] = 1;
                //UserNotification::create($notidata);

                insertNotifications($notidata, ['admin', 'sm'], [], $message);
            }
        }

        if ($data['event']  == 'investigation_markPaid_investigator') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $msg = trans('form.ticket.investigation', [], 'en').' (' . $invdata->work_order_number . ') '.trans('content.notificationdata.msg.mark_as_paid_by_client', [], 'en') ;
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . auth()->user()->name;

            $hrMsg = trans('form.ticket.investigation', [], 'hr').' (' . $invdata->work_order_number . ') '.trans('content.notificationdata.msg.mark_as_paid_by_client', [], 'hr') . ' ' . trans('general.by', [], 'hr') . ' ' . auth()->user()->name;

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = $invid;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = auth()->user()->id;
            $notidata['with_link'] = 1;
            if($newData['data']['type'] == "deliveryboy_invoice_markaspaid"){
                $notidata['redirect_link'] = '/deliveryboy/invoice/'.Crypt::encrypt($newData['data']['id']);
            } else {
                $notidata['redirect_link'] = '/investigator-invoice/'.Crypt::encrypt($newData['data']['id']);
            }
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            // if (isClient()){
                $notidata['user_id'] = $newData['data']['user_id'];
                //UserNotification::create($notidata);

                insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
            // }
        }

        if ($data['event'] == 'client_created') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $user = User::where('id', $newData['data']['id'])->first();
            // if ($isadminsm) {
            $notidata['user_id'] = 1;

            $msg = trans('content.notificationdata.msg.new_client', [], 'en') . ' ' . $user->name . ' ' . trans('content.notificationdata.msg.has_registered', [], 'en');
            $msgby = $msg;

            $hrMsg = trans('content.notificationdata.msg.new_client', [], 'hr') . ' ' . $user->name . ' ' . trans('content.notificationdata.msg.has_registered', [], 'hr');
            // }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = NULL;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = $user->id;
            $notidata['with_link'] = 1;
            if ($user->status == 'pending' || $user->status == 'disabled') {
                $notidata['redirect_link'] = '/clients/' . Crypt::encrypt($user->id).'/approve';
            } else {
                $notidata['redirect_link'] = '/clients/' . Crypt::encrypt($user->id);
            }   
            //UserNotification::create($notidata);
            $message['hr'] = $msgby;
            $message['en'] = $hrMsg;
            insertNotifications($notidata, ['admin', 'sm'], [], $message);
        }
        if ($data['event'] == 'investigator_created') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $ticketData = User::where('id', $newData['data']['id'])->first();
            // if ($isadminsm) {
            $notidata['user_id'] = 1;

            $msg = trans('content.notificationdata.msg.new_investigator', [], 'en') . ' ' . $ticketData->name . ' ' . trans('content.notificationdata.msg.has_registered', [], 'en');
            $msgby = $msg;

            $hrMsg = trans('content.notificationdata.msg.new_investigator', [], 'hr') . ' ' . $ticketData->name . ' ' . trans('content.notificationdata.msg.has_registered', [], 'hr');
            // }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = NULL;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = $ticketData->id;
            $notidata['with_link'] = 1;
            if ($ticketData->status == 'pending' || ($ticketData->status == 'disabled')) {
                $notidata['redirect_link'] = '/investigators/' . Crypt::encrypt($ticketData->id).'/approve';
            } else {
                $notidata['redirect_link'] = '/investigators/' . Crypt::encrypt($ticketData->id);
            }
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin', 'sm'], [], $message);
        }
        if ($data['event'] == 'deliveryboy_created') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $ticketData = User::where('id', $newData['data']['id'])->first();
            // if ($isadminsm) {
            $notidata['user_id'] = 1;

            $msg = trans('content.notificationdata.msg.new_deliveryboy', [], 'en') . ' ' . $ticketData->name . ' ' . trans('content.notificationdata.msg.has_registered', [], 'en');
            $msgby = $msg;

            $hrMsg = trans('content.notificationdata.msg.new_deliveryboy', [], 'hr') . ' ' . $ticketData->name . ' ' . trans('content.notificationdata.msg.has_registered', [], 'hr');
            // }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = NULL;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = $ticketData->id;
            $notidata['with_link'] = 1;
            if ($ticketData->status == 'pending' || ($ticketData->status == 'disabled')) {
                $notidata['redirect_link'] = '/deliveryboys/' . Crypt::encrypt($ticketData->id).'/approve';
            } else {
                $notidata['redirect_link'] = '/deliveryboys/' . Crypt::encrypt($ticketData->id);
            }
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin', 'sm'], [], $message);
        }
        if ($data['event'] == 'user_approve'){
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $user = User::where('id', $newData['data']['id'])->first();
            // if ($isadminsm) {
            $notidata['user_id'] = 1;
            if($newData['data']['type'] == 'client_approve'){
                $msg = trans('form.client_reg', [], 'en') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'en');
                $msgby = $msg;

                $hrMsg = trans('form.client_reg', [], 'hr') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'hr');
            } else if($newData['data']['type'] == 'investigator_approve') {
                $msg = trans('form.investigator_reg', [], 'en') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'en');
                $msgby = $msg;

                $hrMsg = trans('form.investigator_reg', [], 'hr') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'hr');
            } else if($newData['data']['type'] == 'deliveryboy_approve') {
                $msg = trans('form.delivery_boy_reg', [], 'en') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'en');
                $msgby = $msg;

                $hrMsg = trans('form.delivery_boy_reg', [], 'hr') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'hr');
            } else if($newData['data']['type'] == "user_approve") {
                $msg = trans('form.client_reg', [], 'en') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'en') . ' ' . trans('general.by', [], 'en') . ' ' . Auth::user()->name;
                $msgby = $msg;

                $hrMsg = trans('form.client_reg', [], 'hr') . ' ' . $newData['data']['name'] . ' ' . trans('content.notificationdata.is_approved', [], 'hr') . ' ' . trans('general.by', [], 'en') . ' ' . Auth::user()->name;
            }
            // }

            $notidata['message'] = $msgby;
            $notidata['hr_message'] = $hrMsg;
            $notidata['investigation_id'] = NULL;
            $notidata['event'] = $data['event'];
            $notidata['data'] = $data['data'];
            $notidata['perform_by'] = Auth::id();
            $notidata['with_link'] = 1;
            if($newData['data']['type'] == 'client_approve'){
                if ($user->status == 'pending' || $user->status == 'disabled') {
                    $notidata['redirect_link'] = '/clients/' . Crypt::encrypt($user->id).'/approve';
                } else {
                    $notidata['redirect_link'] = '/clients/' . Crypt::encrypt($user->id);
                }
            } else if($newData['data']['type'] == 'investigator_approve') {
                if ($user->status == 'pending' || ($user->status == 'disabled')) {
                    $notidata['redirect_link'] = '/investigators/' . Crypt::encrypt($user->id).'/approve';
                } else {
                    $notidata['redirect_link'] = '/investigators/' . Crypt::encrypt($user->id);
                }
            } else if($newData['data']['type'] == 'deliveryboy_approve') {
                if ($user->status == 'pending' || ($user->status == 'disabled')) {
                    $notidata['redirect_link'] = '/deliveryboys/' . Crypt::encrypt($user->id).'/approve';
                } else {
                    $notidata['redirect_link'] = '/deliveryboys/' . Crypt::encrypt($user->id);
                }
            }   
            //UserNotification::create($notidata);
            $message['en'] = $msgby;
            $message['hr'] = $hrMsg;
            insertNotifications($notidata, ['admin', 'sm', 'user'], [$user->id], $message);
        }

        if ($data['event'] == 'notify-unpaid-invoice') {
            $newData = json_decode($data['data'], true);
            $notidata = [];
            $message = [];
            $msg = trans('form.investigationinvoice.your_invoice', [], 'en').' (' . $newData['data']['invoice_no'] . ') '.trans('form.investigationinvoice.is_now', [], 'en').' '. $newData['data']['days'] . ' ' . trans('form.investigationinvoice.days_overdue');
            $msgby = $msg . ' ' . trans('general.by', [], 'en') . ' ' . trans('form.timeline.administrator', [], 'en');

            $hrMsg = trans('form.investigationinvoice.your_invoice', [], 'hr').' (' . $newData['data']['invoice_no'] . ') '.trans('form.investigationinvoice.is_now', [], 'hr').' '. $newData['data']['days'] . ' ' . trans('form.investigationinvoice.days_overdue') . ' ' . trans('general.by', [], 'hr') . ' ' . trans('form.timeline.administrator', [], 'en');
            
            $isExsist = 0;
            if($newData['data']['type'] == 'once'){
                $isExsist = UserNotification::where('event', $data['event'])->where('investigation_id', $invid)->whereHas('targets', function($q) use ($newData){
                    $q->where('user_id', $newData['data']['user_id']);
                })->exists();
                if(!$isExsist){
                    $notidata['message'] = $msgby;
                    $notidata['hr_message'] = $hrMsg;
                    $notidata['investigation_id'] = $invid;
                    $notidata['event'] = $data['event'];
                    $notidata['data'] = $data['data'];
                    $notidata['perform_by'] = 1;
                    $notidata['with_link'] = 1;
                    $notidata['redirect_link'] = '/invoices/invoice/'.Crypt::encrypt($newData['data']['id']).'/pinvoice';
                    
                    $notidata['user_id'] = $newData['data']['user_id'];
                    //UserNotification::create($notidata);
                    $message['en'] = $msgby;
                    $message['hr'] = $hrMsg;
                    insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
                } else {
                    UserNotification::where('event', $data['event'])->where('investigation_id', $invid)->whereHas('targets', function($q) use ($newData){
                        $q->where('user_id', $newData['data']['user_id']);
                    })->update(['data' => $data['data'], 'message' => $msgby, 'hr_message' => $hrMsg]);
                }
            } else {
                $notidata['message'] = $msgby;
                $notidata['hr_message'] = $hrMsg;
                $notidata['investigation_id'] = $invid;
                $notidata['event'] = $data['event'];
                $notidata['data'] = $data['data'];
                $notidata['perform_by'] = 1;
                $notidata['with_link'] = 1;
                $notidata['redirect_link'] = '/invoices/invoice/'.Crypt::encrypt($newData['data']['id']).'/pinvoice';

                $notidata['user_id'] = $newData['data']['user_id'];
                //UserNotification::create($notidata);
                $message['en'] = $msgby;
                $message['hr'] = $hrMsg;
                insertNotifications($notidata, ['user'], [$notidata['user_id']], $message);
            }
        }
    }
}
