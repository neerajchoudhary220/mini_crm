@props([
'editUrl' => null,
'deleteUrl' => null,
'data' => null,
'updateUrl'=>null,
'mergeSimpleListUrl'=>null,
'contactId'=>null,
'mergeLogUrl'=>null,
])

<div class="d-flex justify-content-center gap-2">

    {{-- Edit Button --}}
    @if($editUrl)
    <button
        class="btn btn-sm btn-warning text-white edt-btn"
        data-values='@json($data)'
        data-edit-url="{{ $editUrl }}" data-update-url="{{$updateUrl}}">
        <i class="fa fa-pencil"></i> Edit
    </button>
    @endif

    {{-- Delete Button --}}
    @if($deleteUrl)
    <button
        class="btn btn-sm btn-danger text-white dlt-btn"
        data-url="{{ $deleteUrl }}">
        <i class="fa fa-trash"></i> Delete
    </button>
    @endif

    {{-- Merge Button --}}
    @if($mergeSimpleListUrl)
    <button class="btn btn-sm btn-info text-white merge-btn"
        data-simple-list-url="{{$mergeSimpleListUrl}}" data-contact-id="{{$contactId}}">
        <i class="bi bi-sign-merge-right"></i> Merge
    </button>
    @endif

    {{-- Merge Log View  Button--}}
    @if($mergeLogUrl)
    <a class="btn btn-sm btn-secondary text-white" href="{{ $mergeLogUrl }}">
        <i class="bi bi-sign-merge-right"></i> Merge Log
    </a>
    @endif


</div>