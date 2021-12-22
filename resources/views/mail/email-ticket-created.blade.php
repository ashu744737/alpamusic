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
								<a href="/" class="logo logo-admin">
                                <img src="{{ URL::asset('/images/logo-dark.png')}}" height="50" alt="logo"></a>
							</h3>
							<div class="p-3">
								<h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.email_tem.client_verify.hello') }} {{ $user['name'] ?? '' }},</h4>
								<p class="text-muted text-center">{{trans('form.email_tem.ticket_create.your_ticket')}} #{{$ticketData['id']}}({{$ticketData['subject']}}) @if(!empty($ticketData['investigations'])) {{ trans('form.email_tem.ticket_create.for') }} {{$ticketData['investigations']['user_inquiry']}}({{$ticketData['investigations']['work_order_number']}}) @endif ,{{trans('form.email_tem.ticket_create.created')}} </p>
								<p class="text-muted text-center"><b>{{trans('form.email_tem.ticket_create.ticket_detail')}}</b></p>
								<table class="table mb-0">
									@if(!empty($ticketData['investigations']))
									<tr>
										<td>{{trans('form.email_tem.ticket_create.investigation')}}</td>
										<td>{{$ticketData['investigations']['work_order_number']}}({{$ticketData['investigations']['user_inquiry']}})</td>
									</tr>
									@endif
									<tr>
										<td>{{trans('form.email_tem.ticket_create.subject')}}</td>
										<td>{{$ticketData['subject']}}</td>
									</tr>
									<tr>
										<td>{{trans('form.email_tem.ticket_create.message')}}</td>
										<td>{{$ticketData['message']}}</td>
									</tr>
									<tr>
										<td>{{trans('form.email_tem.ticket_create.status')}}</td>
										@if($ticketData['status']=='Open')
										<td>{{trans('form.ticket.open')}}</td>
										@else
										<td>{{trans('form.ticket.close')}}</td>
										@endif
									</tr>
									<tr>
										<td>{{trans('form.email_tem.ticket_create.created_on')}}</td>
										<td>{{$ticketData['created_at']}}</td>
									</tr>
								</table>
								<br/>
								<p class="text-muted text-center">
									<b>{{trans('form.email_tem.verification.thank_you')}}</b>
								</p>
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