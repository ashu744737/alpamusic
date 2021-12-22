@if($type=='phones')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.edit_phone') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_phone_{{$phone->id}}" id="edit_phone_{{$phone->id}}">
               @csrf

                    <div class="form-group">
                        <label>{{ trans('form.registration.client.phone') }}
                            <span
                                class="text-danger">*</span>
                        </label>
                        <input 
                        type="tel" placeholder="{{trans('general.phone_placeholder')}}"
                        class="phone form-control multiple_input_required_s3 arr_other_phone" 
                        name="phone"
                         value="{{$phone->value}}"
                         required
                            >
                    </div>
                    <div class="form-group">
                   
                        @php $seloth=0; @endphp
                        @foreach($contacttypes as $id => $contact_name)
                            @if($contact_name['type_name']==$phone->phone_type)
                            @php $seloth=1; @endphp
                            @endif
                        @endforeach
                            
                        <label>{{ trans('form.registration.client.phone_type') }}</label>
                        <select id="arr_otherphone_type_{{$phone->id}}" onchange="changeotheroldtextbox(this)" name="phone_type"
                                class="form-control arr_otherphone_type" required>
                                
                            @foreach($contacttypes as $id => $contact_name)
                            <option
                            {{ (($seloth==0) || ($contact_name['type_name'] == $phone->phone_type)) ? 'selected' : '' }} value="{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}">{{ $contact_name }}</option>
                                @endforeach
                        </select>
                     </div>
                    <div id="arr_otherphone_type_{{$phone->id}}_div" class="form-group  arr_otherphone_other_text_div {{ ($seloth==0 || ($phone->phone_type == 'Other' || $phone->phone_type == 'Contact'))? 'd-block' : 'd-none' }} ">
                        <label>{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value="{{ ($seloth==0) ? $phone->phone_type  : '' }}"
                        id="arr_otherphone_type_{{$phone->id}}_otext" class="form-control numeric arr_otherphone_other_text_type"
                            name="other_text"
                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                            >
                    </div>
                    <input type="hidden" name="id" value="{{$phone->id}}"/>
                
                <div class="form-group text-right">
                    <button onclick="updateform('edit_phone_{{$phone->id}}','phones','phones_data','update')" type="button" data-id="{{$phone->id}}" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
          $('#edit_phone_{{$phone->id}}').validate({
              rules: {
                  phone: "required"
              }
          });
          $('.phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
  });
</script>
@endif
@if($type=='mobiles')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.edit_mobile') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_mobile_{{$mobile->id}}" id="edit_mobile_{{$mobile->id}}">
                @csrf

                <div class="form-group">
                    <label>{{ trans('form.registration.client.mobile') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input 
                    type="tel" placeholder="{{trans('general.phone_placeholder')}}" 
                    class="mobile form-control multiple_input_required_s3 arr_other_mobile" 
                    name="mobile"
                     value="{{$mobile->value}}"
                     required
                        >
                </div>
                <div class="form-group">
               
                    @php $seloth=0; @endphp
                    @foreach($contacttypes as $id => $contact_name)
                        @if($contact_name['type_name']==$mobile->phone_type)
                        @php $seloth=1; @endphp
                        @endif
                    @endforeach
                        
                    <label>{{ trans('form.registration.client.mobile_type') }}</label>
                    <select id="arr_othermobile_type_{{$mobile->value}}" onchange="changeotheroldtextbox(this)" name="mobile_type"
                            class="form-control arr_othermobile_type" required>
                            
                        @foreach($contacttypes as $id => $contact_name)
                        <option
                        {{ (($seloth==0) || ($contact_name['type_name'] == $mobile->phone_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                    </select>
                 </div>
                <div id="arr_othermobile_type_{{$mobile->value}}_div" class="form-group arr_othermobile_other_text_div {{ ($seloth==0 || ($mobile->phone_type == 'Other' || $mobile->phone_type == 'Contact')) ? 'd-block' : 'd-none' }} ">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" value="{{ ($seloth==0) ? $mobile->phone_type  : '' }}"
                    id="arr_othermobile_type_{{$mobile->value}}_otext" class="form-control numeric arr_othermobile_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
                <input type="hidden" name="id" value="{{$mobile->id}}"/>
                <div class="form-group text-right">
                    <button type="button" data-id="{{$mobile->id}}" onclick="updateform('edit_mobile_{{$mobile->id}}','mobiles','mobiles_data','update')" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
            $('#edit_mobile_{{$mobile->id}}').validate({
                rules: {
                    mobile: "required"
                }
            });
            $('.mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
    });
</script>
@endif
@if($type=='emails')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.edit_email') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_email_{{$email->id}}" id="edit_email_{{$email->id}}">
                                
                @csrf

                <div class="form-group">
                    <label>{{ trans('form.registration.client.email') }}
                        <span
                            class="text-danger">*</span>
                    </label>
                    <input type="email" value="{{$email->value}}"
                        class="form-control multiple_input_required_s3 arr_other_email" 
                        name="email"
                        placeholder="{{trans('general.email_placeholder')}}"
                        >
                </div>
                <div class="form-group">
               
                    @php $seloth=0; @endphp
                    @foreach($contacttypes as $id => $contact_name)
                        @if($contact_name['type_name']==$email->email_type)
                        @php $seloth=1; @endphp
                        @endif
                    @endforeach
                        
                    <label>{{ trans('form.registration.client.email_type') }}</label>
                    <select id="arr_othemail_type_{{$email->id}}" onchange="changeotheroldtextbox(this)" name="email_type"
                            class="form-control arr_otheremail_type" required>
                            
                        @foreach($contacttypes as $id => $contact_name)
                        <option
                        {{ (($seloth==0) || ($contact_name['type_name'] == $email->email_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                    </select>
                 </div>
                <div id="arr_othemail_type_{{$email->id}}_div" class="form-group arr_otheremail_other_text_div {{ ($seloth==0 || ($email->email_type == 'Other' || $email->email_type == 'Contact')) ? 'd-block' : 'd-none' }} ">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input id="arr_othemail_type_{{$email->id}}_otext" type="text" value="{{ ($seloth==0) ? $email->email_type  : '' }}"
                     class="form-control numeric arr_otheremail_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
                <input type="hidden" name="id" value="{{$email->id}}"/>
                <div class="form-group text-right">
                    <button type="button" onclick="updateform('edit_email_{{$email->id}}','emails','emails_data','update')" data-id="{{$email->id}}" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
@endif
@if($type=='faxes')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.edit_fax') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_fax_{{$fax->id}}" id="edit_fax_{{$fax->id}}">
                     @csrf

                <div class="form-group">
                    <label>{{ trans('form.registration.client.fax') }}
                        <span
                            class="text-danger">*</span>
                    </label>
                    <input 
                    type="tel" placeholder="{{trans('general.fax_placeholder')}}" 
                    class="fax form-control multiple_input_required_s3 arr_other_fax" 
                    name="fax"
                     value="{{$fax->value}}"
                        >
                </div>
                <div class="form-group">
               
                    @php $seloth=0; @endphp
                    @foreach($contacttypes as $id => $contact_name)
                        @if($contact_name['type_name']==$fax->phone_type)
                        @php $seloth=1; @endphp
                        @endif
                    @endforeach
                        
                    <label>{{ trans('form.registration.client.fax_type') }}</label>
                    <select id="arr_otherfax_type_{{$fax->id}}" onchange="changeotheroldtextbox(this)" name="fax_type"
                            class="form-control arr_otherfax_type" required>
                            
                        @foreach($contacttypes as $id => $contact_name)
                        <option
                        {{ (($seloth==0) || ($contact_name['type_name'] == $fax->phone_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                    </select>
                 </div>
                <div id="arr_otherfax_type_{{$fax->id}}_div" class="form-group arr_otherfax_other_text_div {{ ($seloth==0 || ($fax->phone_type == 'Other' || $fax->phone_type == 'Contact')) ? 'd-block' : 'd-none' }} ">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" value="{{ ($seloth==0) ? $fax->phone_type  : '' }}"
                    id="arr_otherfax_type_{{$fax->id}}_otext" class="form-control numeric arr_otherfax_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
                <input type="hidden" name="id" value="{{$fax->id}}"/>
                
                <div class="form-group text-right">
                    <button type="button" data-id="{{$fax->id}}" onclick="updateform('edit_fax_{{$fax->id}}','faxes','faxes_data','update')" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
            $('#edit_fax_{{$fax->id}}').validate({
                rules: {
                    fax: "required"
                }
            });
            $('.fax').inputmask('(999)999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
    });
</script>
@endif
@if($type=='address')

<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.edit_address') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_address" id="edit_address">
                @csrf 

                <div class="form-group">
                    <label>{{ trans('form.registration.client.address') }}
                        <span
                                class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                           name="address1"
                           id="address_complete_{{ $address->id }}"
                           value="{{ $address->address1 }}"
                           placeholder="{{ trans('form.search_address') }}"
                           required
                          
                           autocomplete="off">

                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.address_helper') }}</label>
                    <input type="text" 
                           class="form-control arr_address_address2"
                           name="address2"
                           value="{{ $address->address2 }}"
                           id="address2_{{ $address->id }}"
                           placeholder="{{ trans('form.registration.client.address_2_helper') }}">
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.city') }}
                        <span
                                class="text-danger">*</span></label>
                    <input type="text"   value="{{ $address->city }}"
                           class="form-control multiple_input_required_s2 arr_address_city"
                           name="city"
                           id="city_{{ $address->id }}"
                           placeholder="{{ trans('form.registration.client.city') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.state') }}
                        <span
                                class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control multiple_input_required_s2 arr_address_state"
                           name="state"
                           value="{{ $address->state }}"
                           id="state_{{ $address->id }}"
                           placeholder="{{ trans('form.registration.client.state') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.country') }}
                        <span
                                class="text-danger">*</span></label>
                    <select id="country_{{ $address->id }}"   name="country_id"
                            class="form-control multiple_input_required_s2 arr_address_country"
                            required>
                            @foreach($countries as  $country_name)
                            <option value="{{$country_name['id']}}" {{ $address->country_id == $country_name['id']? 'selected' : '' }}>
                                {{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}
                            </option>
                            
                          @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ trans('form.registration.client.zip_code') }}
                        </label>
                    <input type="text"
                        class="form-control numeric arr_address_zip"
                        name="zipcode"
                        id="zipcode_{{ $address->id }}"
                        value="{{ $address->zipcode }}"
                        placeholder="{{ trans('form.registration.client.zip_code') }}"
                        >
                </div>
                <div class="form-group">
                    @php $seloth=0; @endphp
                    @foreach($contacttypes as $id => $contact_name)
                        @if($contact_name['type_name']==$address->address_type)
                        @php $seloth=1; @endphp
                        @endif
                    @endforeach
                    <label>{{ trans('form.registration.client.address_type') }}</label>
                    <select id="arr_addnewaddress_type_{{ $address->id }}" onchange="changeotheroldtextbox(this)" name="address_type"
                            class="form-control arr_address_type" required>
                        @foreach($contacttypes as $id => $contact_name)
                        <option
                        {{ (($seloth==0) || ($contact_name['type_name'] == $address->address_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                         @endforeach
                    </select>
                </div>
                <div id="arr_addnewaddress_type_{{ $address->id }}_div"  class="form-group arr_addnewaddress_other_text_div {{ ($seloth==0 || ($address->address_type == 'Other' || $address->address_type == 'Contact')) ? 'd-block' : 'd-none' }}">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" value="{{ ($seloth==0) ? $address->address_type  : '' }}"
                    id="arr_addnewaddress_type_{{ $address->id }}_otext" class="form-control numeric arr_addnewaddress_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
                <input type="hidden" name="id" value="{{$address->id}}"/>
                <div class="form-group text-right">
                    <button type="button" data-id="{{$address->id}}" onclick="updateform('edit_address','address','address_data','update')" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
               </div>
               
                
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>

<style>
    .pac-container { z-index: 100000; }
</style>
<script>
    $(document).ready(function () {
            $('#edit_address').validate({
                rules: {
                    address2: "required",
                    city: "required",
                    state: "required",
                }
            });
            setAddressnew('{{$address->id}}');      
    });
</script>
@endif
@if($type=='contact')

<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.edit_contact') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_contact" id="edit_contact">
                @csrf 

                <div class="form-group">
                    <label>{{ trans('form.registration.client.fax') }}</label>
                    <input type="text" value="{{$contact->fax}}"
                           class="fax form-control arr_contacts_fax" id="fax"
                           name="fax"
                           placeholder="{{trans('general.fax_placeholder')}}"
                           data-im-insert="true">
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.phone') }}</label>
                    <input type="text"  value="{{$contact->phone}}"
                           class="phone form-control arr_contacts_phone multiple_input_required_s3"
                           name="phone"
                           placeholder="{{trans('general.phone_placeholder')}}"
                           data-im-insert="true" required>
                </div>
                <div class="form-group">
						<label>{{ trans('form.registration.client.mobile') }}</label>
						<input type="text" value="{{$contact->mobile}}"
							class="mobile form-control arr_contacts_mobile" id="mobile"
							name="mobile"
							placeholder="{{trans('general.phone_placeholder')}}"
							data-im-insert="true">
					</div>
                

                <div class="form-group">
                    @php $seloth = 1; @endphp
                    <label>{{ trans('form.registration.client.contact_type') }}</label>
                    <select id="arr_addnewconatct_type_1" onchange="changeotheroldtextbox(this)" name="contact_type" class="form-control arr_contact_type" required>
                            @foreach($contacttypes as $id => $contact_name)
                            <option
                            {{ (($seloth==0) || ($contact_name['type_name'] == $contact->contact_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                                @endforeach
                    </select>
                </div>
                <div id="arr_addnewconatct_type_1_div"  class="form-group arr_addnewcontact_other_text_div {{ ($seloth == 0)? 'd-block' : 'd-none' }}">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" value="{{ ($seloth == 0) ? $contact->contact_type  : '' }}"
                        class="form-control numeric arr_addnewcontact_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>

                <input type="hidden" name="id" value="{{$contact->id}}"/>
                <div class="form-group text-right">
                    <button type="button" data-id="{{$contact->id}}" onclick="updateform('edit_contact','contact','contact_data','update')" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
               </div>
               
                
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>

<script>
    $(document).ready(function () {
        $('#edit_contact').validate({
                rules: {
                    fax: "required",
                    phone: "required",
                    mobile: "required",
                }
            });
            $('.fax').inputmask('(999)999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
            $('.phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
            $('.mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
    });

@endif