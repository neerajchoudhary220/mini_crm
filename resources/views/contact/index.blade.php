@extends('layouts.main')
@section('title',"Contacts")
@section('contents')
@push('custom-styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Better UI for Bootstrap */
    .select2-container--default .select2-selection--single {
        height: 45px;
        padding-top: 7px;
        border-radius: 6px;
        border: 1px solid #ced4da;
    }
</style>
@endpush
<x-header-action
    title="Contacts"
    btnId="add-new-contact-btn"
    btnText="Add New Contact"
    btnIcon="bi bi-plus"
    btnClass="btn btn-info btn-sm text-white" />

<x-table
    id="contact-dt-tbl"
    :headers="[
        ['label' => '#'],
        ['label' => 'Name'],
        ['label' => 'Email'],
        ['label' => 'Phone'],
        ['label' => 'Gender'],
        ['label' => 'Created At'],
        ['label' => 'Action', 'class' => 'text-center']
    ]" />
@include('contact.form')

@push('custom-js')
@include('layouts.includes.datatable-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const contactListUrl = "{{ route('contacts.list') }}"
    const fieldListUrl = "{{ route('custom.fields.items') }}"
    const customFieldsUrl = "{{route('custom.fields.show')}}"
</script>
<script src="{{asset('assets/js/contact/conact-form.js')}}"></script>
<script src="{{asset('assets/js/contact/contact-list.dt.js')}}"></script>
@endpush
@endsection