@if($type=='client')
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.name') }}</label>
            <div class="col-8">
                {{ $user->name ?? '' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.email') }}</label>
            <div class="col-8">
                {{ $user->email ?? '' }}
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.customer_type') }}</label>
            <div class="col-8">
                {{ (App::isLocale('hr')?$user->client->client_type->hr_type_name:$user->client->client_type->type_name) ?? '' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.printable_name') }}</label>
            <div class="col-8">
                {{ $user->client->printname ?? '' }}
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.legal_entity_id') }}</label>
            <div class="col-8">
                {{ $user->client->legal_entity_no ?? '' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.website') }}</label>
            <div class="col-8">
                {{ $user->client->website ?? '' }}
            </div>
        </div>
    </div>  
</div>
@endif
@if($type=='investigator')
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.name') }}</label>
            <div class="col-8">
                {{ $user->name ?? '' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.email') }}</label>
            <div class="col-8">
                {{ $user->email ?? '' }}
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.investigator.viewform.id_number') }}</label>
            <div class="col-8">
                {{ $user->investigator->idnumber ?? '-' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.investigator.viewform.date_of_birth') }}</label>
            <div class="col-8">
                {{ date('d M Y', strtotime($user->investigator->dob)) ?? '-' }}
            </div>
        </div>
    </div>  
</div>
<div class="row">
  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.investigator.viewform.family') }}</label>
            <div class="col-8">
                {{ $user->investigator->family ?? '-' }}
            </div>
        </div>
    </div>  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.website') }}</label>
            <div class="col-8">
                {{ $user->investigator->website ?? '-' }}
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.investigator.viewform.area_of_specialization') }}</label>
            <div class="col-8">
                @foreach($user->investigatorspecilizations as $specialize) 
                    @if ($specialize->specializations->name)   
                    <span class="badge dt-badge badge-primary">{{ $specialize->specializations->name ?? '' }}</span>
                    @endif
                    @endforeach  
            </div>
        </div>
    </div>
</div>
@endif
@if($type=='deliveryboy')
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.name') }}</label>
            <div class="col-8">
                {{ $user->name ?? '' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.email') }}</label>
            <div class="col-8">
                {{ $user->email ?? '' }}
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
            class="col-form-label col-4">{{ trans('form.registration.investigator.viewform.family') }}</label>
        <div class="col-form-label col-8">
            {{ $user->deliveryboy->family ?? '-' }}
        </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigator.viewform.id_number') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $user->deliveryboy->idnumber ?? '-' }}
                                                </div>
        </div>
    </div>  
</div>
<div class="row">
  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
            class="col-form-label col-4">{{ trans('form.registration.investigator.viewform.date_of_birth') }}</label>
        <div class="col-form-label col-8">
            {{ date('d M Y', strtotime($user->deliveryboy->dob)) ?? '-' }}
        </div>
        </div>
    </div>  
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.client.viewform.website') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $user->deliveryboy->website ?? '-' }}
                                                </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.deliveryboy.delivery_areas') }}</label>
            <div class="col-8">
                @foreach($user->deliveryboyAreas as $area) 
                                                        @if ($area->areas->area_name)   
                                                        <span class="badge dt-badge badge-primary">{{ $area->areas->area_name ?? '' }}</span>
                                                        @endif
                                                        @endforeach  
            </div>
        </div>
    </div>
</div>
@endif
@if($type=='admin')
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.name') }}</label>
            <div class="col-8">
                {{ $user->name ?? '' }}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="form-group row">
            <label for="inv_name"
                class="col-4">{{ trans('form.registration.client.viewform.email') }}</label>
            <div class="col-8">
                {{ $user->email ?? '' }}
            </div>
        </div>
    </div>  
</div>

@endif