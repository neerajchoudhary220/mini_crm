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

    #contactForm .form-control-lg {
        border-radius: 10px;
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
    ]">
    <x-slot>
        <div class="row mb-3">
            <div class="col-lg-3">
                <label for="gender-filter" class="form-label fw-semibold">Gender</label>
                <select id="gender-filter" class="form-select shadow-sm" onchange="genderFilter(this)">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
    </x-slot>
</x-table>
@include('contact.form')
@include('contact.merge')
@push('custom-js')
@include('layouts.includes.datatable-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    const contactListUrl = "{{ route('contacts.list') }}"
    const fieldListUrl = "{{ route('custom.fields.items') }}"
    const customFieldsUrl = "{{route('custom.fields.show')}}"
    const mergeUrl = "{{ route('contacts.merge') }}"
    const customFieldSelect = $("#customFieldSelect");
    const contactFormModal = $("#contact-form-modal");
    const contactForm = $("#contactForm");
    const profilePlaceHolderImgUrl = "{{ asset('assets/img/profile_placeholder.png') }}"
    const formTitle = $("#formTitle")
    const saveBtn = $("#btnSave")
    const dynamicFieldsArea = $("#dynamicFieldsArea")


    function resetContactForm() {
        customFieldSelect.html("");
        customFieldSelect.val(null).trigger('change');
        contactForm.validate().resetForm();
        contactForm.find('label[class="text-danger small"]').remove();
        contactForm.find(".border-danger").removeClass("border-danger");
        $("#previewImage").attr("src", profilePlaceHolderImgUrl);
        dynamicFieldsArea.html("")

    }

    function generateFieldHTML(field, value = "") {
        let html = "";
        switch (field.field_type) {
            case "Text":
            case "Email":
            case "Date":
                html = `
            <div class="mb-3 dynamic-field" id="field_${field.id}">
                <label class="form-label">${field.field_label}</label>
                <input type="${field.field_type}" 
                       name="custom[${field.id}]" 
                       data-id="${field.id}"
                       value="${value}"
                       class="form-control custom-field">
            </div>`;
                break;

            case "Textarea":
                html = `
            <div class="mb-3 dynamic-field" id="field_${field.id}">
                <label class="form-label">${field.field_label}</label>
                <textarea name="custom[${field.id}]" 
                data-id="${field.id}"
                          class="form-control custom-field" rows="3">${value}</textarea>
            </div>`;
                break;


        }

        return html;
    }
</script>
<script src="{{asset('assets/js/contact/contact-list.dt.js')}}"></script>
<script src="{{asset('assets/js/contact/conact-form.js')}}"></script>

@endpush
@endsection