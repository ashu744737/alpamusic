
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title mt-0">{{ trans('form.registration.investigation.investigation_decline') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="declineform" id="declineform">
                @csrf  
                <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group">
                    <textarea rows="7" id="reason_1" name="reason_1" type="textarea" placeholder="reason" class="form-control" >{{trans('form.decline_reason.first')}}
{{trans('form.decline_reason.second')}}
{{trans('form.decline_reason.third')}}
                    </textarea>
                    </div>
                </div>
                
            </div>
            <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div class="form-group text-center text-xs-center">
                   <button id="button_1" type="button" value="1" onclick="declineconfirm(this.value)" class="btn btn-danger waves-effect">{{{ trans('general.decline') }}}</button>
                   <button type="button" data-dismiss="modal"  class="btn btn-secondary waves-effect">{{{ trans('general.cancel') }}}</button>
                </div>     
                      
            </div>
            </div>

            </form>      
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<script>
    
    function declineconfirm(val){
        var inid="{{$id}}";
        var decreason=$('#reason_'+val).val();
       
        Swal.fire({
            title: "{{trans('general.are_you_sure')}}",
            text: "{{trans('form.registration.investigation.decline_confirm')}}",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "{{trans('general.yes_decline')}}",
            cancelButtonText: "{{trans('general.cancel')}}"
        }).then(function (result) {
            if (result.value){
                $('#button_'+val).html("{{ trans('general.updating') }}");
                $.ajax({
                        url: '{{ route("investigation.actiondata") }}' ,
                        type: "POST",
                        data: {
                        "_token": "{{ csrf_token() }}",
                        "type": "decline_update",
                        "decline_reason": decreason,
                        "id": inid
                        },
                        success: function( response ) {
                            if(response.status==1){
                                //alert(response.data)
                                $('#decline_model').modal('hide');
                                Swal.fire({
                                    title: "{{trans('general.decline')}}",
                                    text: (result.msg) ? result.msg : "{{trans('form.registration.investigation.decline_confirmed')}}", 
                                    type: "success",
                                    confirmButtonText: "{{ trans('general.ok') }}",
                                })
                                .then((result) => {
                                    if(result.value){
                                        location.reload(true);
                                    }
                                });
                                
                            }else{
                               // $('#decline_model').modal('hide');  
                               $('#button_'+val).html("{{ trans('general.select') }}"); 
                               Swal.fire("{{trans('general.error_text')}}",(result.msg) ? result.msg : "{{trans('general.something_wrong')}}", "error")   
                            }
                        }

                    });
               
            }else{
                Swal.close();
            }
        });
       
       
       
    } 
</script>
