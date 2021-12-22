@extends('layouts.email-master')

@section('title', '')

@section('content')
    <div class="account-pages my-5 pt-sm-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 col-lg-6 col-xl-5">
					<div class="card overflow-hidden">
						<div class="card-body pt-0">
							<h3 class="text-center mt-4">
								<a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}" height="50" alt="logo"></a>
							</h3>
							<div class="p-3">
                                <p class="text-muted text-center">{{ trans('form.email_tem.client_verify.hello') }} {{ $investigator['user']['name'] ?? '' }}</p>
                                <p class="text-muted text-center">{{trans('content.notificationdata.msg.new_investigation')}} {{$investigationData['work_order_number']}}({{$investigationData['user_inquiry']}}) {{trans('content.notificationdata.msg.has_assigned')}} {{trans('general.by')}} {{$assignedUser['name'] ?? trans('form.timeline.administrator')}}</p>
                                <p class="text-muted text-center">
                                    @php $link=route('investigation.show', [Crypt::encrypt($investigationData['id'])]); @endphp
                                    <a href="{{$link}}" target="_blank">{{trans('general.view')}} {{trans('form.investigation_req')}}</a>
                                </p>
                                <br>
                                <p class="text-muted text-center">
                                        <b>{{trans('form.email_tem.verification.thank_you')}}</b>
                                    </p>
                                </div>
                            </div>
						</div>
					</div>
					<div class="mt-5 text-center">
					<p>{{ trans('general.developedby') }} <a target="_blank" href="https://soft-l.com/">{{ trans('general.company') }} </a></p>
                    </div>
				</div>
			</div>
		</div>
	</div>

@stop