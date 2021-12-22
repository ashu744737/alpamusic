
<button type="button" class="btn header-item noti-icon waves-effect" onclick='openDropdown();' id="page-header-notifications-dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="ti-bell"></i>
   @if(count($notificationdata)>0) <span class="noticount badge badge-danger badge-pill" id="notificationCount">{{count($notificationdata)}}</span>@endif
</button>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown" id="notification-messages">
    @if(count($notificationdata)>0)
    <div class="p-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="m-0" id="notification_label_count"> {{ trans('content.notificationdata.notifications') }} ({{count($notificationdata)}}) </h5>
            </div>
        </div>
    </div>
    <div data-simplebar style="max-height: 230px;">
        @foreach ($notificationdata as $notification)
        @php 
            $item = $notification->notification;
            $eventjsondata = json_decode($item->data, true)['data'];
            $brdlbl='success';$icnlbl='message';
            if($item->event=='investigation_documentupload')
            {$brdlbl='success';$icnlbl='image';}
            if($item->event=='investigation_documentdelete')
            {$brdlbl='danger';$icnlbl='image';}
            if($item->event=='investigator_assigneed' || $item->event=='investigator_changeassignee')
            {$brdlbl='success';$icnlbl='account';}
            if($item->event=='investigation_action' && $eventjsondata['type']=='declined')
            {$brdlbl='danger';$icnlbl='cash-usd';}
            if($item->event=='investigation_action' && $eventjsondata['type']=='approved')
            {$brdlbl='success';$icnlbl='cash-usd';}
            if($item->event=='notify-unpaid-invoice')
            {$brdlbl='danger';$icnlbl='alert-circle';}
            if($item->event=='invoice-not-send-to-client')
            {$brdlbl='danger';$icnlbl='alert-circle';}
            $link='';
            if($item->with_link==1) { $link=route('investigation.show', Crypt::encrypt($item->investigation_id)); }
            if(isset($item->redirect_link) && !empty($item->redirect_link)){ $link = $item->redirect_link; }
            
            if($item->event == 'user_approve' || $item->event == 'investigator_approve' || $item->event == 'deliveryboy_approve'){
                if(isClient() || isInvestigator() || isDeliveryboy()){
                    $link = '';
                }
            }
        @endphp
        <div class="text-reset notification-item" id="notification_{{$notification->id}}">
            <div class="media" style="cursor: pointer;">
                <div class="avatar-xs mr-3" onclick='readNotifications("{{$notification->id}}", "{{$link}}");'>
                    <span class="avatar-title border-{{$brdlbl}} rounded-circle ">
                        <i class="mdi mdi-{{$icnlbl}}"></i>
                    </span>
                </div>
                <div class="media-body" onclick='readNotifications("{{$notification->id}}", "{{$link}}");'>
                    @if(config('app.locale') == 'hr')
                        @php $ex=explode('by',$item->hr_message);@endphp
                    @else
                    @php $ex=explode('by',$item->message);@endphp
                    @endif
                   
                    @if(!empty($ex[0]))<h6 class="mt-0 mb-1">{{ $ex[0]}}</h6>@endif

                    @if(!empty($item->performby))
                        @if(($item->performby->user_type->type_name!=env('USER_TYPE_INVESTIGATOR') && $item->performby->user_type->type_name!=env('USER_TYPE_DELIVERY_BOY')) || (isAdmin() || isSM() ))
                            <div class="text-muted">
                                <p class="mb-1">{{ trans('general.by') }} {{ $item->performby->name}}</p>
                            </div>
                        @endif
                    @endif

                    <div class="text-muted">
                        <p class="mb-1">@php echo gmdate('d M h:i:s A', strtotime($item->created_at));@endphp</p>
                    </div>
                </div>
                <div style="padding: 0 7px;" onclick='readNotifications("{{$notification->id}}","read");'>
                    <i class="fa fa-trash" style="margin-top: 40px;color:red;"></i>
                </div>
            </div>
        </div>
        @endforeach
       
    </div>
    
    @else
    <div class="p-3">
        <div class="row align-items-center text-center">
            <div class="col">
                <h5 class="m-0"> {{ trans('content.notificationdata.you_have_no_notification') }} </h5>
            </div>
        </div>
    </div>
    @endif

</div>
