@if($type=='viewcustomer')
<div class="card">
    <div class="card-header">
        <i class="fas fa-user"></i> {{ trans('form.clientcustomer.connected_paying_customers') }}<a href="javascript:void(0);" onclick="addeditModal('customer_add_model','addcustomer',{{$user->client->id}},'add')" class="float-right dt-btn-action" title="{{trans('form.my_profileaddnew_mobile')}}">
            <i class="mdi mdi-table-plus mdi-18px"></i>
        </a>
        
    </div>
  <div class="row">
    @php $custIndx = 1; @endphp
    @if(count($user->client->customers)>0)
   
    <div class="card-body col-lg-6">
        <blockquote class="card-blockquote mb-0">
            @foreach($user->client->customers as $customer)
            @if($custIndx%2!=0)                                          
            <p>{{$customer->customer->name}}                    
                <a class="float-right dt-btn-action text-danger delete-record" data-outputid="customer_data" data-type="viewcustomer" data-id="{{$customer->id}}" data-clientid="{{$customer->client_id}}" title="{{ trans('form.clientcustomer.delete_customer') }}">
                    <i class="mdi mdi-delete mdi-18px"></i>
                </a>   
                <a href="javascript:void(0);" onclick="addeditModal('customer_edit_model','editcustomer','{{$customer->id}}','edit')" class="float-right dt-btn-action" title="{{ trans('form.clientcustomer.edit_customer') }}">
                    <i class="mdi mdi-table-edit mdi-18px"></i>
                </a> 
            </p>
            @endif
            @php $custIndx++; @endphp
            @endforeach
                                                      
        </blockquote>
    </div>
   
   
  
    @php $custIndx = 1; @endphp
    
    <div class="card-body col-lg-6">
        <blockquote class="card-blockquote mb-0">
            @foreach($user->client->customers as $customer)
            @if($custIndx%2==0)                                      
            <p>{{$customer->customer->name}}                        
                <a class="float-right dt-btn-action text-danger delete-record" data-outputid="customer_data" data-type="viewcustomer" data-id="{{$customer->id}}" data-clientid="{{$customer->client_id}}" title="{{ trans('form.clientcustomer.delete_customer') }}">
                    <i class="mdi mdi-delete mdi-18px"></i>
                </a>   
                <a href="javascript:void(0);" onclick="addeditModal('customer_edit_model','editcustomer','{{$customer->id}}','edit')" class="float-right dt-btn-action" title="{{ trans('form.clientcustomer.edit_customer') }}">
                    <i class="mdi mdi-table-edit mdi-18px"></i>
                </a> 
            </p>
            @endif
            @php $custIndx++; @endphp
            @endforeach
                                            
        </blockquote>
    </div>
    @else
    <div class="card-body col-lg-12">
        <blockquote class="card-blockquote mb-0 text-center">
            <p>{{ trans('form.clientcustomer.client_have_no_customers') }}</p>
        </blockquote>
    </div>
    @endif
</div>
</div>
@endif
@if($type=='addcustomer')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.clientcustomer.addnew_customer') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="add_customer" id="add_customer">
                @csrf  
                <div class="form-row">
                    
                    <div class="form-group col-md-12">
                        <label for="userinquiry">{{ trans('form.clientcustomer.customers') }} <span class="text-danger">*</span></label>
                        <select name="customerid" id="customerid" class="form-control userinquiry_dd" required>
                            <option disabled selected>{{ trans('form.clientcustomer.select_customer_name') }}</option>
                            @foreach($customers as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    
                    
                <input type="hidden" id="clientid" name="clientid" value="{{$client->id}}"/>
                <div class="form-group text-right">
                    <button type="button" onclick="savecontactform('add_customer','viewcustomer','customer_data')" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.save') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light mr-3" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<style>
    .select2-container .select2-selection--single{
        height: 33px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        line-height: 33px !important;
    }
    .select2-container{
        width: 100% !important;
    }
</style>
<script>
      $(document).ready(function () {
            $('#add_customer').validate({
                rules: {
                    customerid: "required"
                }
            });
            $('.userinquiry_dd').select2();
           
    });
</script>
@endif
@if($type=='editcustomer')
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.clientcustomer.edit_customer') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="edit_customer_{{$customer->id}}" id="edit_customer_{{$customer->id}}">
               @csrf

                    
                    <div class="form-row">
                    
                        <div class="form-group col-md-12">
                            <label for="userinquiry">{{ trans('form.clientcustomer.customers') }} <span class="text-danger">*</span></label>
                            <select name="customerid" id="customerid" class="form-control userinquiry_dd" required>
                                <option disabled selected>{{ trans('form.clientcustomer.select_customer_name') }}</option>
                                @foreach($customers as $key => $value)
                                    <option value="{{$key}}" {{ $key == old('customerid', $customer->customer_id) ? 'selected' : ''  }}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                 
                    <input type="hidden" id="clientid" name="clientid" value="{{$customer->client_id}}"/>
                    <input type="hidden" id="id" name="id" value="{{$customer->id}}"/>
                
                <div class="form-group text-right">
                    <button onclick="updateform('edit_customer_{{$customer->id}}','viewcustomer','customer_data','update')" type="button" data-id="{{$customer->id}}" class="btn btn-primary w-md waves-effect waves-light editbtn">{{ trans('general.update') }}</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<style>
    .select2-container .select2-selection--single{
        height: 33px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        line-height: 33px !important;
    }
    .select2-container{
        width: 100% !important;
    }
</style>
<script>
    $(document).ready(function () {
          $('#edit_customer_{{$customer->id}}').validate({
              rules: {
                customerid: "required"
              }
          });
          $('.userinquiry_dd').select2();
       
  });
</script>
@endif