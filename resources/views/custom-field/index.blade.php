@extends('layouts.main')
@section('title',"Custom Field")
@section('contents')

<x-header-action
    title="Custom Fields"
    btnId="add-new-custom-field-btn"
    btnText="Add New Field"
    btnIcon="bi bi-plus"
    btnClass="btn btn-info btn-sm text-white" />

<x-table
    id="custom-field-dt-tbl"
    :headers="[
        ['label' => '#'],
        ['label' => 'Label'],
        ['label' => 'Name'],
        ['label' => 'Type'],
        ['label' => 'Created At'],
        ['label' => 'Action', 'class' => 'text-center']
    ]">
    <x-slot>
        <div class="row mb-3">
            <div class="col-lg-3">
                <label for="field-type-filter" class="form-label fw-semibold">Gender</label>
                <select id="field-type-filter" class="form-select shadow-sm" onchange="filedTypeFilter(this)">
                    <option value="">All</option>
                    <option value="text">Text</option>
                    <option value="date">Date</option>
                    <option value="textarea">Textarea</option>
                    <option value="email">Email</option>
                </select>
            </div>
        </div>
    </x-slot>
</x-table>

@include('custom-field.form')
@push('custom-js')
@include('layouts.includes.datatable-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    const customFieldListUrl = "{{ route('custom.fields.list') }}"
    const customFieldFormModal = $("#custom-field-form-modal");
    const fieldForm = $("#fieldForm");
    const btnSaveField = $("#btnSaveField")

    function resetCustomFieldForm() {
        fieldForm.validate().resetForm();
        fieldForm.find('label[class="text-danger small"]').remove()
        fieldForm.find(".border-danger").removeClass('border-danger')
    }

    $("#add-new-custom-field-btn").on("click", () => {
        btnSaveField.text('Save Field')
        resetCustomFieldForm()
        fieldForm[0].reset()
        fieldForm.attr('action', "{{ route('custom.fields.store') }}")
        customFieldFormModal.modal("show");
    });
</script>
<script src="{{asset('assets/js/custom-fields/custom-field-list.dt.js')}}"></script>
<script src="{{asset('assets/js/custom-fields/custom-field.form.js')}}"></script>
@endpush
@endsection