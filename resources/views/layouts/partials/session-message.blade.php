@if ($message = Session::get('success'))
    <div class="alert alert-card alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <span>
            {{ $message }}
        </span>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-card alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <span>{{ $message }}</span>
    </div>
@endif
