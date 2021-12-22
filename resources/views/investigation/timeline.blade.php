@if($type=='admin')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <section id="cd-timeline" class="cd-container" dir="ltr">
                        @if(count($transitions)>0)
                        @php $indxtrans = 1; @endphp
                        @foreach($transitions as $transition)
                        @if(($indxtrans%2)!=0)
                        <div class="cd-timeline-block {{ count($transitions) != $indxtrans ? 'timeline-right' : '' }}">
                            <div class="cd-timeline-img bg-success {{ count($transitions) == $indxtrans ? 'd-xl-none' : '' }}">
                                <i class="mdi mdi-adjust"></i>
                            </div>
                            <!-- cd-timeline-img -->
                            

                            <div class="cd-timeline-content">
                                <h3>@php echo $transition['event_title'];@endphp  
                                    @if(!empty($transition['investigation_status']))
                                    @php $statusbadge='';
                                    if($transition['investigation_status']== trans('form.timeline_status.Pending Approval') || $transition['investigation_status']== trans('form.timeline_status.Open') || $transition['investigation_status']== trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']== trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']== trans('form.timeline_status.Declined') || $transition['investigation_status']== trans('form.timeline_status.Closed') || $transition['investigation_status']== trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge = 'primary';
                                    @endphp
                                    <span class="badge dt-badge badge-{{ $statusbadge }}"> {{$transition['investigation_status']}}</span>
                                    @endif
                                   

                                </h3>
                                <p class="mb-0 text-muted">@php echo $transition['event_desc'];@endphp<br><br></p>
                                @if(!empty($transition['decline_reason'])) <p class="mb-0 text-muted">@php echo $transition['decline_reason'];@endphp</p>@endif
                                @if(!empty($transition['reason'])) <p class="mb-0 text-muted">{{trans('form.timeline.investigation_decline_reason')}} @php echo $transition['reason'];@endphp</p>@endif
                                @if(!empty($transition['document_filename']))
                                @php $isimage=0;
                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                    if(in_array($transition['document_type'], $imageExtensions))
                                        {$isimage=1;
                                        }
                                        $imgurl='/investigation-documents/'.$transition['document_filename'];
                                @endphp
                                @if($isimage==1)
                                <p>{{$transition['document_name']}}</p>
                                <p><a href="{{URL::asset($imgurl)}}" target="_blank"><img src="{{url($imgurl)}}" alt="" class="rounded" width="90"/></a></p>
                              
                                @elseif($isimage==0)
                                <p><a href="{{URL::asset($imgurl)}}" class="card-link" target="_blank">{{$transition['document_name']}}</a></p>
                                @endif
                                @endif
                                @if(!empty($transition['investigator_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['investigator_assign_status']== trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['investigator_assign_status']}}</span>
                                    @endif

                                    @if(!empty($transition['deliveryboy_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['deliveryboy_assign_status']}}</span>
                                    @endif
                                    @if($transition['event']=='investigation_generateinvoice')
                                    <a target="_blank" href="{{route('investigation.showinvoice', [Crypt::encrypt($invn->id)])}}" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" {{ trans('form.investigationinvoice.viewandgenerateinvoice') }}">
                                        {{ trans('form.investigationinvoice.view_invoice') }}
                                    </a>
                                    @endif
                                <span class="cd-date">@php echo $transition['event_date'];@endphp</span>
                                
                            </div>
                            <!-- cd-timeline-content -->
                        </div>
                        <!-- cd-timeline-block -->
                        @else  
                        <div class="cd-timeline-block {{ count($transitions) != $indxtrans ? 'timeline-left' : '' }} ">
                            <div class="cd-timeline-img bg-danger {{ count($transitions) == $indxtrans ? 'd-xl-none' : '' }}">
                                <i class="mdi mdi-adjust"></i>
                            </div>
                            <!-- cd-timeline-img -->
                           
                            <div class="cd-timeline-content">
                                <h3>@php echo $transition['event_title'];@endphp  
                                    @if(!empty($transition['investigation_status']))
                                    @php $statusbadge='';
                                    if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge = 'primary';
                                    @endphp
                                    <span class="badge dt-badge badge-{{ $statusbadge }}"> {{$transition['investigation_status']}}</span>
                                    @endif
                                </h3>
                                <p class="mb-4 text-muted">@php echo $transition['event_desc'];@endphp</p>
                                @if(!empty($transition['decline_reason'])) <p class="mb-0 text-muted">@php echo $transition['decline_reason'];@endphp</p>@endif
                                @if(!empty($transition['reason'])) <p class="mb-0 text-muted">@php echo $transition['reason'];@endphp</p>@endif
                                @if(!empty($transition['document_filename']))
                                @php $isimage=0;
                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                    if(in_array($transition['document_type'], $imageExtensions))
                                        {$isimage=1;
                                        }
                                        $imgurl='/investigation-documents/'.$transition['document_filename'];
                                @endphp
                                @if($isimage==1)
                                <p>{{$transition['document_name']}}</p>
                                <p><a href="{{URL::asset($imgurl)}}" target="_blank"><img src="{{url($imgurl)}}" alt="" class="rounded" width="90"/></a></p>
                              
                                @elseif($isimage==0)
                                <p><a href="{{URL::asset($imgurl)}}" class="card-link" target="_blank">{{$transition['document_name']}}</a></p>
                                @endif
                                @endif
                              
                                @if(!empty($transition['investigator_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['investigator_assign_status']}}</span>
                                    @endif

                                    @if(!empty($transition['deliveryboy_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['deliveryboy_assign_status']}}</span>
                                    @endif
                               
                                {{-- <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5">See more detail</button>
                            --}}
                                    @if($transition['event']=='investigation_generateinvoice')
                                    <a target="_blank" href="{{route('investigation.showinvoice', [Crypt::encrypt($invn->id)])}}" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" {{ trans('form.investigationinvoice.viewandgenerateinvoice') }}">
                                        {{ trans('form.investigationinvoice.view_invoice') }}
                                    </a>
                                    @endif
                                <span class="cd-date">@php echo $transition['event_date'];@endphp</span>
                            </div>
                            <!-- cd-timeline-content -->
                        </div>
                        <!-- cd-timeline-block -->
                        @endif
                        {{--  @endforeach --}}
                         @php $indxtrans++; @endphp
                         @endforeach
                         @endif
                        
                    </section>
                    <!-- cd-timeline -->

                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
@endif
@if($type=='client')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <section id="cd-timeline" class="cd-container" dir="ltr">
                    @if(count($transitions)>0)
                    @php $indxtrans = 1; @endphp
                    @foreach($transitions as $transition)
                   
                   
                    @if(($indxtrans%2)!=0)
                    <div class="cd-timeline-block {{ count($transitions) != $indxtrans ? 'timeline-right' : '' }}">
                        <div class="cd-timeline-img bg-success {{ count($transitions) == $indxtrans ? 'd-xl-none' : '' }}">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       

                        <div class="cd-timeline-content">
                            <h3>@php echo $transition['event_title'];@endphp  
                                @if(!empty($transition['investigation_status']))
                                @php $statusbadge='';
                                if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                $statusbadge='warning';
                                else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                $statusbadge='dark';
                                else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                $statusbadge='success';
                                else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                $statusbadge='danger';
                                else
                                $statusbadge = 'primary';
                                @endphp
                                <span class="badge dt-badge badge-{{ $statusbadge }}"> {{$transition['investigation_status']}}</span>
                                @endif
                               

                            </h3>
                            <p class="mb-0 text-muted">@php echo $transition['event_desc'];@endphp<br><br></p>
                            @if(!empty($transition['document_filename']) && ((!empty($invn->invoice) && $invn->invoice->status == 'paid') || ($transition['event_by']==auth()->user()->id)))
                            @php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            @endphp
                            @if($isimage==1)
                            <p>{{$transition['document_name']}}</p>
                            <p><a href="{{URL::asset($imgurl)}}" target="_blank"><img src="{{url($imgurl)}}" alt="" class="rounded" width="90"/></a></p>
                          
                            @elseif($isimage==0)
                            <p><a href="{{URL::asset($imgurl)}}" class="card-link" target="_blank">{{$transition['document_name']}}</a></p>
                            @endif
                            @else
                            @if(!empty($transition['document_name']))<p>{{$transition['document_name']}}</p>@endif
                            @endif
                            @if(!empty($transition['investigator_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['investigator_assign_status']}}</span>
                                    @endif

                                    @if(!empty($transition['deliveryboy_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['deliveryboy_assign_status']}}</span>
                                    @endif
                                    @if($transition['event']=='investigation_generateinvoice')
                                    <a target="_blank" href="{{route('investigation.showinvoice', [Crypt::encrypt($invn->id)])}}" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" {{ trans('form.investigationinvoice.viewandgenerateinvoice') }}">
                                        {{ trans('form.investigationinvoice.view_invoice') }}
                                    </a>
                                    @endif
                            <span class="cd-date">@php echo $transition['event_date'];@endphp</span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    @else  
                    <div class="cd-timeline-block {{ count($transitions) != $indxtrans ? 'timeline-left' : '' }} ">
                        <div class="cd-timeline-img bg-danger {{ count($transitions) == $indxtrans ? 'd-xl-none' : '' }}">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       
                        <div class="cd-timeline-content">
                            <h3>@php echo $transition['event_title'];@endphp  
                                @if(!empty($transition['investigation_status']))
                                @php $statusbadge='';
                                if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                $statusbadge='warning';
                                else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                $statusbadge='dark';
                                else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                $statusbadge='success';
                                else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                $statusbadge='danger';
                                else
                                $statusbadge='primary';
                                @endphp
                                <span class="badge dt-badge badge-{{ $statusbadge }}"> {{$transition['investigation_status']}}</span>
                                @endif
                            </h3>
                            <p class="mb-4 text-muted">@php echo $transition['event_desc'];@endphp</p>
                            
                            @if(!empty($transition['document_filename']) && ((!empty($invn->invoice) && $invn->invoice->status == 'paid') || ($transition['event_by']==auth()->user()->id)))
                            @php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            @endphp
                            @if($isimage==1)
                            <p>{{$transition['document_name']}}</p>
                            <p><a href="{{URL::asset($imgurl)}}" target="_blank"><img src="{{url($imgurl)}}" alt="" class="rounded" width="90"/></a></p>
                          
                            @elseif($isimage==0)
                            <p><a href="{{URL::asset($imgurl)}}" class="card-link" target="_blank">{{$transition['document_name']}}</a></p>
                            @endif
                            @else
                            @if(!empty($transition['document_name']))<p>{{$transition['document_name']}}</p>@endif
                            @endif
                          
                            @if(!empty($transition['investigator_assign_status']))
                            @php $invstatusbadge='';
                            if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                            $invstatusbadge='warning';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                            $invstatusbadge='success';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                            $invstatusbadge='primary';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                            $invstatusbadge='info';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                            $invstatusbadge='danger';
                            else
                            $invstatusbadge = 'primary';
                            @endphp
                        <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['investigator_assign_status']}}</span>
                            @endif

                            @if(!empty($transition['deliveryboy_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['deliveryboy_assign_status']}}</span>
                                    @endif
                           
                            {{-- <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5">See more detail</button>
                            --}}
                            @if($transition['event']=='investigation_generateinvoice')
                            <a target="_blank" href="{{route('investigation.showinvoice', [Crypt::encrypt($invn->id)])}}" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" {{ trans('form.investigationinvoice.viewandgenerateinvoice') }}">
                           {{ trans('form.investigationinvoice.view_invoice') }}
                            </a>
                            @endif
                            <span class="cd-date">@php echo $transition['event_date'];@endphp</span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    @endif
                    {{--  @endforeach --}}
                     @php $indxtrans++; @endphp
                     @endforeach
                     @endif
                    
                </section>
                <!-- cd-timeline -->

            </div>
        </div>
    </div>
    <!-- end col -->
</div>
@endif
@if($type=='other')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <section id="cd-timeline" class="cd-container" dir="ltr">
                    @if(count($transitions)>0)
                    @php $indxtrans = 1; @endphp
                    @foreach($transitions as $transition)
                    @php $active=0;$inarray=array('investigation_generateinvoice','investigator_changeassignee','investigator_changestatus','investigator_assigneed'); // add investigator events on this
                   $delarray=array('investigation_generateinvoice','deliveryboy_assigneed','deliveryboy_changestatus','deliveryboy_changeassignee');//add delivery boy events on this
                   $ownarray=array('investigation_generateinvoice','mail_send','investigation_documentdelete','investigation_documentupload'); // add commen event if need get user data to show
                   $checkarray=array();
                   @endphp
                   @if(isInvestigator())
                   @php  $checkarray=$delarray;@endphp
                   @elseif(isDeliveryboy())
                   @php  $checkarray=$inarray;@endphp
                   @endif
                   @if(!in_array($transition['event'], $checkarray) || (in_array($transition['event'], $ownarray) && $transition['event_by']==auth()->user()->id))
                   @php $active=1;@endphp
                   @endif
                   @if($active==1)
                    @if(($indxtrans%2)!=0)
                    <div class="cd-timeline-block {{ ((count($transitions) != $indxtrans) && ($active==1)) ? 'timeline-right' : '' }}">
                        <div class="cd-timeline-img bg-success {{ ((count($transitions) != $indxtrans) && ($active==1)) ? '' : 'd-xl-none' }}">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       

                        <div class="cd-timeline-content">
                            <h3>@php echo $transition['event_title'];@endphp  
                                @if(!empty($transition['investigation_status']))
                                    @php $statusbadge='';
                                    if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge='primary';
                                    @endphp
                                    <span class="badge dt-badge badge-{{ $statusbadge }}"> {{$transition['investigation_status']}}</span>
                                    @endif
                               

                            </h3>
                            <p class="mb-0 text-muted">@php echo $transition['event_desc'];@endphp<br><br></p>
                            @if(!empty($transition['document_filename']))
                            @php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            @endphp
                            @if($isimage==1)
                            <p>{{$transition['document_name']}}</p>
                            <p><a href="{{URL::asset($imgurl)}}" target="_blank"><img src="{{url($imgurl)}}" alt="" class="rounded" width="90"/></a></p>
                          
                            @elseif($isimage==0)
                            <p><a href="{{URL::asset($imgurl)}}" class="card-link" target="_blank">{{$transition['document_name']}}</a></p>
                            @endif
                            @endif
                            @if(!empty($transition['investigator_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge='primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['investigator_assign_status']}}</span>
                                    @endif

                                    @if(!empty($transition['deliveryboy_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge='primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['deliveryboy_assign_status']}}</span>
                                    @endif
                            <span class="cd-date">@php echo $transition['event_date'];@endphp</span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    @else  
                    <div class="cd-timeline-block {{ ((count($transitions) != $indxtrans) && ($active==1)) ? 'timeline-left' : '' }} ">
                        <div class="cd-timeline-img bg-danger {{ ((count($transitions) != $indxtrans) && ($active==1)) ? '' : 'd-xl-none' }}">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       
                        <div class="cd-timeline-content">
                            <h3>@php echo $transition['event_title'];@endphp  
                                @if(!empty($transition['investigation_status']))
                                    @php $statusbadge='';
                                    if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge='primary';
                                    @endphp
                                    <span class="badge dt-badge badge-{{ $statusbadge }}"> {{$transition['investigation_status']}}</span>
                                    @endif
                            </h3>
                            <p class="mb-4 text-muted">@php echo $transition['event_desc'];@endphp</p>
                            @if(!empty($transition['document_filename']))
                            @php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            @endphp
                            @if($isimage==1)
                            <p>{{$transition['document_name']}}</p>
                            <p><a href="{{URL::asset($imgurl)}}" target="_blank"><img src="{{url($imgurl)}}" alt="" class="rounded" width="90"/></a></p>
                          
                            @elseif($isimage==0)
                            <p><a href="{{URL::asset($imgurl)}}" class="card-link" target="_blank">{{$transition['document_name']}}</a></p>
                            @endif
                            @endif
                          
                            @if(!empty($transition['investigator_assign_status']))
                            @php $invstatusbadge='';
                            if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                            $invstatusbadge='warning';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                            $invstatusbadge='success';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                            $invstatusbadge='primary';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                            $invstatusbadge='info';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                            $invstatusbadge='danger';
                            else
                            $invstatusbadge='primary';
                            @endphp
                        <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['investigator_assign_status']}}</span>
                            @endif

                            @if(!empty($transition['deliveryboy_assign_status']))
                                    @php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge='primary';
                                    @endphp
                                <span class="badge dt-badge badge-{{ $invstatusbadge }}"> {{$transition['deliveryboy_assign_status']}}</span>
                                    @endif
                           
                            {{-- <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5">See more detail</button>
    --}}
                            <span class="cd-date">@php echo $transition['event_date'];@endphp</span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    @endif
                    {{--  @endforeach --}}
                     @php $indxtrans++; @endphp
                     @endif
                     @endforeach
                     @endif
                    
                </section>
                <!-- cd-timeline -->

            </div>
        </div>
    </div>
    <!-- end col -->
</div>
@endif
