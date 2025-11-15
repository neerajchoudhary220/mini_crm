@props([
'title' => '',
'btnId' => '',
'btnText' => 'Add New',
'btnIcon' => 'bi bi-plus',
'btnClass' => 'btn btn-info btn-sm text-white',
])

<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ $title }}</h4>

        <button id="{{ $btnId }}" class="{{ $btnClass }}">
            <i class="{{ $btnIcon }} me-2"></i>{{ $btnText }}
        </button>
    </div>
</div>