@if($type=='investigator')
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"class="col-4">{{ trans('form.registration.investigator.viewform.company') }}</label>
              <div class="col-8">
                 {{ $user->investigator->bank_details->company ?? '-' }}
                </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
            class="col-4">{{ trans('form.registration.investigator.viewform.name_of_bank') }}</label>
        <div class="col-8">
            {{ $user->investigator->bank_details->name ?? '' }}
        </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.bank_number') }}</label>
           <div class="col-8">
              {{ $user->investigator->bank_details->number ?? '' }}
               </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.branch_name') }}</label>
               <div class="col-8">
                {{ $user->investigator->bank_details->branch_name ?? '' }}
               </div>
        </div>
    </div>  
</div>
<div class="row">
  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.branch_no') }}</label>
               <div class="col-8">
                {{ $user->investigator->bank_details->branch_number ?? '' }}
                </div>
        </div>
    </div>  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.account_no') }}</label>
          <div class="col-8">
           {{ $user->investigator->bank_details->account_no ?? '' }}
             </div>
        </div>
    </div>  
</div>

@endif
@if($type=='deliveryboy')
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"class="col-4">{{ trans('form.registration.investigator.viewform.company') }}</label>
              <div class="col-8">
                 {{ $user->deliveryboy->bank_details->company ?? '-' }}
                </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
            class="col-4">{{ trans('form.registration.investigator.viewform.name_of_bank') }}</label>
        <div class="col-8">
            {{ $user->deliveryboy->bank_details->name ?? '' }}
        </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.bank_number') }}</label>
           <div class="col-8">
              {{ $user->deliveryboy->bank_details->number ?? '' }}
               </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.branch_name') }}</label>
               <div class="col-8">
                {{ $user->deliveryboy->bank_details->branch_name ?? '' }}
               </div>
        </div>
    </div>  
</div>
<div class="row">
  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.branch_no') }}</label>
               <div class="col-8">
                {{ $user->deliveryboy->bank_details->branch_number ?? '' }}
                </div>
        </div>
    </div>  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name" class="col-4">{{ trans('form.registration.investigator.viewform.account_no') }}</label>
          <div class="col-8">
           {{ $user->deliveryboy->bank_details->account_no ?? '' }}
             </div>
        </div>
    </div>  
</div>

@endif