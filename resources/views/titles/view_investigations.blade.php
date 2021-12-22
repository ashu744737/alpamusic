<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">

		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">{{ trans('form.investigations') }} </h5>

			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div class="modal-body">
			<div class="table-responsive">
				<table class="table table-centered table-vertical table-nowrap">
					<thead>
					<tr>
						<td>{{trans('form.registration.investigation.user_inquiry')}}</td>
						<td>{{trans('form.registration.investigation.work_order_number')}}</td>
						<td>{{ trans('form.registration.investigation.req_type_inquiry') }}</td>
					</tr>
					</thead>
					<tbody>
					@foreach($investigations ?? [] as $row)
						<tr onclick="window.location.href='{{ route('investigation.show', [Crypt::encrypt($row->investigation_id)]) }}'">
							<td>
								{{$row->investigation->user_inquiry}}
							</td>
							<td>
								{{$row->investigation->work_order_number}}
							</td>
							<td>
								{{$row->investigation->product->name}}
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>

		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('general.cancel')}}</button>
		</div>
	</div>
</div>