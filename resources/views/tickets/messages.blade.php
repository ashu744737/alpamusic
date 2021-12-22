@extends('layouts.master')

@section('title') {{ trans('form.tickets') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ucwords($ticket->subject)}}</h4>
                        </div>
                    </div>

                    @include('layouts.partials.session-message')

                    <div class="flash-message"></div>

                    <div class="row">
                        <div class="col-12" style="padding: 10px calc(24px / 2) calc(70px + 24px) calc(24px / 2);">
                            <div style="height: 320px;max-height: 320px;padding: 10px;border: 1px solid #ced4da;overflow: scroll;" id="messageWrapper">
                                
                            </div>
                        </div>
                        <div class="col-12" style="position: absolute;bottom: 10px;">
                            <div class="form-row">
                                <div class="form-group col-10">
                                    <input type="hidden" name="ticket_id" id="ticket_id" value="{{$ticket->id}}" />
                                    <textarea placeholder="{{trans('form.ticket.message.type')}}" id="message" name="message" class="form-control "></textarea>
                                </div>
                                <div class="form-group col-2">
                                    <button type="submit" class="btn btn-primary p-2 mt-2" id="send-message">{{trans('form.send')}}</button>
                                </div>
                            </div>
                        </div>
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
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.checkboxes.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
    <script src="{{ URL::asset('/libs/pdfmake/vfs_fonts.js') }}"></script>
    <script type="text/javascript"> 
        $(function() {
            refreshChat($('#ticket_id').val())
            const interval = setInterval(function() {
                refreshChat($('#ticket_id').val())
            }, 5000);
            
            let userId = "{{Auth::user()->id}}";
            $('#send-message').on('click', function() {
                if($('#message').val() != '') {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ route('ticket.send-message') }}",
                        data: {
                            'ticket_id': $('#ticket_id').val(),
                            'message': $('#message').val()
                        },
                        success: function(resultData){
                            if(resultData.status == "success"){
                                $('#message').val('');
                                refreshChat($('#ticket_id').val());
                            }
                        }
                    })
                }
            })

            function refreshChat(id){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/ticket/refreshChat/"+ id,
                    type: 'GET',
                    success: function (response){
                        if(response.status == "success"){
                            let messages = "";
                            let msgData = response.data
                            msgData.map((msg, idx) => {
                                if(userId == msg.user_id) {
                                    messages += `<div style="text-align: right;margin: 12px 0px;">
                                    <span style="background: #105C8D;color: white;padding: 4px 10px;border-radius: 5px;">
                                        ${msg.message}
                                    </span>
                                    <br />
                                    <p style="margin: 2px 5px;font-size: 11px;">${msg.timeAgo}</p>
                                </div>`;
                                } else {
                                    messages += `<div style="text-align: left;margin: 12px 0px;">
                                    <span style="background: #ced4da;padding: 4px 10px;border-radius: 5px;">
                                        ${msg.message}
                                    </span>
                                    <br />
                                    <p style="margin: 2px 5px;font-size: 11px;">${msg.timeAgo}</p>
                                </div>`;
                                }
                            });
                            $('#messageWrapper').html(messages);
                        }
                    }
                });
            }
        });
    </script>
@endsection