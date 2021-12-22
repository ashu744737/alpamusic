@if( !$investigations->isEmpty() )
    <div class="col-md-12 form-group mb-3 search-result-list" id="investigation_list" style="height: 300px; min-height: 300px;">
    @foreach($investigations as $investigation)

        <div class="form-row result-row-wrapper {{ !in_array($investigation->id, $assigned) ? 'assignInvestigation' : 'assignedInvestigation' }}"
             data-investigationid="{{$investigation->id}}" data-investigatorid="{{$investigatorId}}"
             title="{{ !in_array($investigation->id, $assigned) ? trans('form.investigation.click_to_assign') : trans('form.investigation.already_assigned') }}"
             >

            @if(in_array($investigation->id, $assigned))
                <div class="col-md-12">
                    <label class="assigned-msg">{{ trans('form.investigation.already_assigned_msg') }}</label>
                </div>
            @endif

            <div class="col-md-6 details-wrapper">
                <label><strong>{{ trans('form.registration.investigation.user_inquiry') }}: </strong>{{$investigation->user_inquiry }}</label>
            </div>
            <div class="col-md-6 details-wrapper">
                <label><strong>{{ trans('form.registration.investigation.family') }}: </strong>{{$investigation->subjects->implode('family_name', ', ') }}</label>
            </div>
            <div class="col-md-6 details-wrapper">
                <label><strong>{{ trans('form.registration.investigation.firstname') }}: </strong>{{$investigation->subjects->implode('first_name', ', ') }}</label>
            </div>
            <div class="col-md-6 details-wrapper">
                <label><strong>{{ trans('form.registration.investigation.id') }}: </strong>{{$investigation->subjects->implode('id_number', ', ') }}</label>
            </div>
            <div class="col-md-6 details-wrapper">
                <label><strong>{{ trans('form.registration.investigation.work_order_number') }}: </strong>{{$investigation->work_order_number }}</label>
            </div>
            <div class="col-md-6 details-wrapper">
                <label><strong>{{ trans('form.registration.investigation.req_type_inquiry') }}: </strong>{{$investigation->product->name }}</label>
            </div>
        </div>

    @endforeach
    </div>
@else
    <h5 class="text-center">{{ trans('general.no_record_found') }}</h5>
@endif

