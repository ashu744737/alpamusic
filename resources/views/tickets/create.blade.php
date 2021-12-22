@extends('layouts.master')

@section('title') {{ trans('form.ticket.create') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.ticket.create') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row mt-3">
                        <div class="col-12">

                            <form action="{{ route('tickets.store') }}" method="POST" id="create_tickets" class="form needs-validation" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                <div class="form-group col-md-6">
                                        <label for="subject" class="ul-form__label">{{trans('form.ticket.subject')}} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="{{ trans('form.ticket.subject') }}" value="{{ old('subject') }}" required/>
                                        @error('subject')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="type" class="ul-form__label">{{trans('form.ticket.type')}} <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-control " required>
                                            <option value="">{{trans('form.ticket.select_ticket_type')}}</option>
                                            <option value="Thank You">{{trans('form.ticket.thank_you')}}</option>
                                            <option value="Complaint">{{trans('form.ticket.complaint')}}</option>
                                        </select>
                                        @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    @if(count($investigations))
                                    <div class="form-group col-md-6">
                                        <label for="investigation" class="ul-form__label">{{trans('form.investigations')}} </label>
                                        <select name="investigation_id" id="investigation_id" class="form-control ">
                                            <option value="">{{trans('form.ticket.select_investigation')}}</option>
                                            @foreach($investigations as $investigation)
                                            <option value="{{$investigation->id}}">{{$investigation->work_order_number}} - {{$investigation->user_inquiry}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    @if(count($investigations))
                                        <div class="form-group col-md-6">
                                    @else
                                        <div class="form-group col-md-12">
                                    @endif
                                        <label for="type" class="ul-form__label">{{trans('form.ticket.doc')}} </label>
                                        <input type="file" class="form-control" id="file" name="file">
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="message" class="ul-form__label">{{trans('form.ticket.ticket_message')}} <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="message" name="message" placeholder="{{ trans('form.ticket.ticket_message') }}" value="{{ old('message') }}"></textarea>
                                        @error('message')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.create')}}</button>
                                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

@endsection

@section('footerScript')
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>    
    <script src="{{ URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js') }}" async></script>
    @if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
    <!-- footerScript -->
    <script type="text/javascript"> 
        $(function() {
            $("#create_tickets").validate({           
                rules: { 
                    type: {
                        required: true 
                    },
                    subject:{
                        required: true,
                    },
                    message:{
                        required: true,
                    }
                },
                messages: 
                {
                   
                }
            });
        });
    </script>
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.select.min.js') }}"></script>

@endsection