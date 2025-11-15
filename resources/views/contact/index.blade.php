@extends('layouts.main')
@section('title',"Contacts")
@section('contents')

<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Contacts</h4>
        <button class="btn btn-info btn-sm text-white" id="add-new-contact-btn">
            <i class="bi bi-plus me-2"></i>Add New Contact
        </button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-3">
                <div class="row">
                    @include('contact.contact-list-table')
                </div>
            </div>
        </div>
    </div>
</div>

@include('contact.form')

@push('custom-js')
@include('layouts.includes.datatable-js')
<script>
    const contactListUrl = "{{ route('contacts.list') }}"
</script>
<script src="{{asset('assets/js/contact/conact-form.js')}}"></script>
<script src="{{asset('assets/js/contact/contact-list.dt.js')}}"></script>

@endpush
@endsection