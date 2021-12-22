@extends('layouts.master')

@section('title') {{ trans('form.ticket.ticket_detail') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" /></link>
    <link href="{{ URL::asset('/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" /></link>
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}">
    <link href="{{ URL::asset('/libs/magnific-popup/magnific-popup.min.css')}}" rel="stylesheet" type="text/css" /> 
    <style>
        .action-dd{
            right: 0 !important;
            left: auto !important;
        }
        @media (max-width: 575px) {
            .action-dd {
                left: 0 !important;
                right: auto !important;
            }
        }
    </style>
@endsection

@section('content')

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('tickets.index')}}">{{ trans('form.tickets') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('general.view') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card investogators_Details">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title pb-4">{{ trans('form.ticket.ticket_detail') }} - #{{$ticket->id}}
                                @if(!empty($ticket->status))
                                    @php $statusbadge='';
                                        if($ticket->status=='Open')
                                        $statusbadge='warning';
                                        else
                                        $statusbadge='success';
                                    @endphp
                                    <span class="badge dt-badge badge-{{ $statusbadge }}"> {{trans('form.timeline_status.'.$ticket->status)}}</span>
                                @endif
                            </h4>
                        </div>

                        <!-- Actions for Admin -->
                        @if(isAdmin() || isSM())
                        <div class="actiondropdown col-12 col-sm-8 col-xs-12 text-sm-right">
                            <div class="dropdown dropdown-topbar d-inline-block">
                                <a class="btn btn-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ trans('general.action') }} <i class="mdi mdi-chevron-down"></i>
                                </a>

                                <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                    @if(check_perm('tickets_edit') && $ticket->status == 'Close')
                                        <a onclick="changestatus({{$ticket->id}}, 'Open')" class="dropdown-item" href="javascript:void(0);">{{ trans('form.ticket.open') }}</a>
                                    @endif
                                    @if(check_perm('tickets_edit') && $ticket->status == 'Open')
                                        <a onclick="changestatus({{$ticket->id}}, 'Close')" class="dropdown-item action-decline" data-toggle="modal"  href="javascript:void(0);">{{ trans('form.ticket.close') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group row">
                                <label for="inv_name" class="col-4">{{ trans('form.ticket.name') }}:</label>
                                <div class="col-8">
                                    {{ $ticket->user->name ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group row">
                                <label for="inv_name" class="col-4">{{ trans('form.ticket.subject') }}:</label>
                                <div class="col-8">
                                    {{$ticket->subject ?? ''}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group row">
                                <label for="inv_name" class="col-4">{{ trans('form.ticket.type') }}:</label>
                                <div class="col-8">
                                    @if(isset($ticket->type))
                                        @if($ticket->type == "Thank You")
                                            {{trans('form.ticket.thank_you')}}
                                        @else
                                            {{trans('form.ticket.complaint')}}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(!empty($ticket->investigations))
                        <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group row">
                                <label for="inv_name" class="col-4">{{ trans('form.ticket.investigation') }}:</label>
                                <div class="col-8">
                                    @if(!empty($ticket->investigations))
                                        {{$ticket->investigations->user_inquiry}}({{$ticket->investigations->work_order_number}})
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group row">
                                <label for="inv_name" class="col-2">{{ trans('form.ticket.ticket_message') }}:</label>
                                <div class="col-10">
                                    {{$ticket->message ?? ''}}
                                </div>
                            </div>
                        </div>
                        @if(!empty($ticket->file))
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group row">
                                <label for="inv_name" class="col-2">{{ trans('form.ticket.doc') }}:</label>
                                <div class="col-6">
                                    {{$ticket->file ?? ''}}
                                    <span class="ml-3 d-inline-flex">
                                        @php
                                            $docurl='/ticket-documents/'.$ticket->file;
                                            $docName='Ticket-'.$ticket->id.'-'.$ticket->file;
                                        @endphp
                                        <a href="{{URL::asset($docurl)}}" class="dt-btn-action" title="{{trans('general.view')}}" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{URL::asset($docurl)}}" class="ml-2 dt-btn-action" title="{{trans('general.download_doc')}}" download="{{$docName}}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footerScript')

<!-- footerScript -->
<!-- Required datatable js -->
<script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/form-xeditable.init.js') }}"></script>
<script src="{{ URL::asset('/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('/libs/rwd-table/rwd-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/table-responsive.init.js') }}"></script>
<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
<script src="{{ URL::asset('/libs/magnific-popup/magnific-popup.min.js')}}"></script>
<!-- lightbox init js-->
<script src="{{ URL::asset('/js/pages/lightbox.init.js')}}"></script>
<script>
    function changestatus(id, status) {
        var inid = id;
        var message = (status=="Open")?"{{trans('form.ticket.open_ticket')}}":"{{trans('form.ticket.close_ticket')}}";
        var buttonText = (status=="Open")?"{{trans('general.yes_open')}}":"{{trans('general.yes_close')}}"
        Swal.fire({
            title: "{{trans('general.are_you_sure')}}",
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: buttonText,
            cancelButtonText: "{{trans('general.cancel')}}",
        }).then(function (result) {
            if (result.value) {
                $('.actiondropdown').addClass('d-none');
                $('.loading').removeClass('d-none');
                $.ajax({
                    url: '{{ route("tickets.changestatus") }}',
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "status": status,
                        "id": inid
                    },
                    success: function (result) {
                        if (result.status == 'success') {
                            Swal.fire(
                                result.msg
                            ).then((result) => {
                                if (result.value) {
                                    location.reload(true);
                                }
                            });
                        } else {
                            $('.actiondropdown').removeClass('d-none');
                            $('.loading').addClass('d-none');
                            Swal.fire("{{trans('general.error_text')}}", (result.msg) ? result.msg : "{{trans('general.something_wrong')}}", "error")
                        }
                    }

                });

            } else {
                $('.actiondropdown').removeClass('d-none');
                $('.loading').addClass('d-none');
                Swal.close();
            }
        });
    }
</script>
@endsection