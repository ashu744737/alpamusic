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
								<p class="text-muted text-center">{{ trans('form.registration.verifymail.profile_update_text') }} {{ $invoice['client']['user']['name'] ?? '' }}</p>
								<p class="text-muted text-center">{{ trans('form.email_tem.unpaid_invoice.your_invoice') }} <b>{{ $invoice['invoice_no'] ?? '' }}</b> {{ trans('form.email_tem.unpaid_invoice.is_due') }} {{ \Carbon\Carbon::parse($invoice['created_at'])->format('d-m-y') }}.</p>
								<p class="text-muted text-center">{{trans('form.email_tem.unpaid_invoice.further_text')}}</p>
                                <p>
                                    @php $link=route('invoice.show', [Crypt::encrypt($invoice['id']), 'pinvoice']); @endphp
                                    <a href="{{$link}}" target="_blank">{{trans('form.email_tem.ticket_update.view_invoice')}}</a>
                                </p>
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