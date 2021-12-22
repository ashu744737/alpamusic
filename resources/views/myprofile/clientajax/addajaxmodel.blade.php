@if($type=='phones')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.addnew_phone') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_phone" id="add_phone">
                            @csrf  

                    <div class="form-group">
                        <label>{{ trans('form.registration.client.phone') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                        type="tel" placeholder="{{trans('general.phone_placeholder')}}"
                        class="phone form-control multiple_input_required_s3 arr_other_phone" 
                        name="phone"
                        value=""
                            required
                        >
                    </div>
                    <div class="form-group">
                    <label>{{ trans('form.registration.client.phone_type') }}</label>
                        <select id="arr_addnewphone_type_1" onchange="changeotheroldtextbox(this)" name="phone_type"
                                class="form-control arr_addnewphone_type" required>
                                
                                @foreach($contacttypes as $id => $contact_name)
                                <option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="arr_addnewphone_type_1_div" class="form-group  arr_addnewphone_other_text_div d-none  ">
                        <label id="arr_addnewphone_type_1_olabel">{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                        id="arr_addnewphone_type_1_otext" class="form-control numeric arr_addnewphone_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                    </div>
                
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_phone','phones','phones_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light mr-3" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
      $(document).ready(function () {
            $('#add_phone').validate({
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
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.addnew_mobile') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_mobile" id="add_mobile">
                         @csrf     

                    <div class="form-group">
                        <label>{{ trans('form.registration.client.mobile') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                        type="tel" placeholder="{{trans('general.phone_placeholder')}}"
                        class="mobile form-control multiple_input_required_s3 arr_other_mobile" 
                        name="mobile"
                        value=""
                            required
                        >
                    </div>
                    <div class="form-group">
                    <label>{{ trans('form.registration.client.mobile_type') }}</label>
                        <select id="arr_addnewmobile_type_1" onchange="changeotheroldtextbox(this)" name="mobile_type"
                                class="form-control arr_addnewmobile_type" required>
                                
                                @foreach($contacttypes as $id => $contact_name)
                                <option
                                        value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="arr_addnewmobile_type_1_div" class="form-group  arr_addnewmobile_other_text_div d-none  ">
                        <label id="arr_addnewmobile_type_1_olabel">{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                        id="arr_addnewmobile_type_1_otext" class="form-control numeric arr_addnewmobile_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                    </div>
                
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_mobile','mobiles','mobiles_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
            $('#add_mobile').validate({
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
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.addnew_email') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_email" id="add_email">
                          @csrf 

                    <div class="form-group">
                        <label>{{ trans('form.registration.client.email') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                        type="email" value=""
                        class="form-control multiple_input_required_s3 arr_other_email" 
                        name="email"
                        placeholder="{{trans('general.email_placeholder')}}"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label>{{ trans('form.registration.client.email_type') }}</label>
                        <select id="arr_addnewemail_type_1" onchange="changeotheroldtextbox(this)" name="email_type" class="form-control arr_addnewemail_type" required>
                            @foreach($contacttypes as $id => $contact_name)
                                <option value="{{ $contact_name['type_name'] }}">{{ config('app.locale') == 'hr'?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="arr_addnewemail_type_1_div" class="form-group  arr_addnewemail_other_text_div d-none  ">
                        <label id="arr_addnewemail_type_1_olabel">{{ trans('form.registration.client.type') }}</label>
                        <input type="text" value=""
                        id="arr_addnewemail_type_1_otext" class="form-control numeric arr_addnewemail_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                    </div>
                
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_email','emails','emails_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
            $('#add_email').validate({
                rules: {
                    email: "required"
                }
            });
    });
</script>
@endif
@if($type=='faxes')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.addnew_fax') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_fax" id="add_fax">
                    @csrf       

                    <div class="form-group">
                        <label>{{ trans('form.registration.client.fax') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                        type="tel" placeholder="{{trans('general.fax_placeholder')}}"
                        class="fax form-control multiple_input_required_s3 arr_other_fax" 
                        name="fax"
                        value=""
                            required
                        >
                    </div>
                    <div class="form-group">
                    <label>{{ trans('form.registration.client.fax_type') }}</label>
                        <select id="arr_addnewfax_type_1" onchange="changeotheroldtextbox(this)" name="fax_type"
                                class="form-control arr_addnewfax_type" required>
                                
                                @foreach($contacttypes as $id => $contact_name)
                                <option
                                        value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="arr_addnewfax_type_1_div" class="form-group  arr_addnewfax_other_text_div d-none  ">
                        <label id="arr_addnewfax_type_1_olabel">{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                        id="arr_addnewfax_type_1_otext" class="form-control numeric arr_addnewfax_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                    </div>
                
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_fax','faxes','faxes_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
            $('#add_fax').validate({
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
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.addnew_address') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_address" id="add_address">
                @csrf 

                <div class="form-group">
                    <label>{{ trans('form.registration.client.address') }}
                        <span
                                class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                           name="address1"
                           id="address_complete_1"
                           placeholder="{{ trans('form.search_address') }}"
                           required
                          
                           autocomplete="off">

                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.address_helper') }}</label>
                    <input type="text" value=""
                           class="form-control arr_address_address2"
                           name="address2"
                           id="address2_1"
                           placeholder="{{ trans('form.registration.client.address_2_helper') }}">
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.city') }}
                        <span
                                class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control multiple_input_required_s2 arr_address_city"
                           name="city"
                           id="city_1"
                           placeholder="{{ trans('form.registration.client.city') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.state') }}
                        <span
                                class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control multiple_input_required_s2 arr_address_state"
                           name="state"
                           id="state_1"
                           placeholder="{{ trans('form.registration.client.state') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.country') }}
                        <span
                                class="text-danger">*</span></label>
                    <select id="country_1"   name="country_id"
                            class="form-control multiple_input_required_s2 arr_address_country"
                            required>
                            @foreach($countries as  $country_name)
                            <option value="{{ $country_name['id'] }}" {{ old('country_id') == $country_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}</option>
                          @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ trans('form.registration.client.zip_code') }}
                        </label>
                    <input type="text" value=""
                        class="form-control numeric arr_address_zip"
                        name="zipcode"
                        id="zipcode_1"
                        placeholder="{{ trans('form.registration.client.zip_code') }}"
                        >
                </div>
                <div class="form-group">
                    <label>{{ trans('form.registration.client.address_type') }}</label>
                    <select id="arr_addnewaddress_type_1" onchange="changeotheroldtextbox(this)" name="address_type"
                            class="form-control arr_address_type" required>
                        @foreach($contacttypes as $id => $contact_name)
                            <option
                                    value="{{ $contact_name['type_name'] }}" {{ old('address_type') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="arr_addnewaddress_type_1_div"  class="form-group arr_addnewaddress_other_text_div d-none">
                    <label id="arr_addnewaddress_type_1_olabel">{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" value=""
                    id="arr_addnewaddress_type_1_otext" class="form-control numeric arr_addnewaddress_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_address','address','address_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light mr-3" >{{ trans('general.cancel') }}</button>
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
            $('#add_address').validate({
                rules: {
                    address2: "required",
                    city: "required",
                    state: "required",
                }
            });
            setAddressnew(1);      
    });
</script>
@endif
@if($type=='contact')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.my_profile.addnew_contact') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_contact" id="add_contact">
                @csrf 

                <div class="form-group">
                    <label>{{ trans('form.registration.client.fax') }}</label>
                    <input type="text" value=""
                           class="fax form-control arr_contacts_fax" id="fax"
                           name="fax"
                           placeholder="{{trans('general.fax_placeholder')}}"
                           data-im-insert="true">
                </div>

                <div class="form-group">
                    <label>{{ trans('form.registration.client.phone') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" value=""
                           class="phone form-control arr_contacts_phone multiple_input_required_s3"
                           name="phone"
                           placeholder="{{trans('general.phone_placeholder')}}"
                           data-im-insert="true" required>
                </div>
                <div class="form-group">
						<label>{{ trans('form.registration.client.mobile') }}</label>
						<input type="text" value=""
							class="mobile form-control arr_contacts_mobile" id="mobile"
							name="mobile"
							placeholder="{{trans('general.phone_placeholder')}}"
							data-im-insert="true">
					</div>
                

                <div class="form-group">
                    <label>{{ trans('form.registration.client.contact_type') }}</label>
                    <select id="arr_addnewconatct_type_1" onchange="changeotheroldtextbox(this)" name="contact_type"
                            class="form-control arr_contact_type" required>
                        @foreach($contacttypes as $id => $contact_name)
                            <option
                                    value="{{ $contact_name['type_name'] }}" {{ old('contact_type') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="arr_addnewconatct_type_1_div"  class="form-group arr_addnewcontact_other_text_div d-none">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" value=""
                        class="form-control numeric arr_addnewcontact_other_text_type"
                        name="other_text"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_contact','contact','contact_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
               
                
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    $(document).ready(function () {
            $('#add_contact').validate({
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
</script>
@endif