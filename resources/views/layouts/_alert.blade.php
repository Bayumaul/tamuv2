@if (session('success'))
    <div class="alert alert-solid-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-solid-warning alert-dismissible" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-solid-danger alert-dismissible" role="alert">
        {{ session('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif