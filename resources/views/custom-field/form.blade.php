<x-static-modal heading="Custom Field" modalId="custom-field-form-modal" headingIconClass="bi bi-journals me-2">
    <div class="row">
        <form id="fieldForm" action="{{route('custom.fields.store')}}">
            <div class="modal-body">

                <input type="hidden" id="fieldId">
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label" for="field_type">Field Label</label>
                        <input class="form-control mb-2" name="field_label" id="field_label" placeholder="e.g. Birthday">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label" for="field_name">Field Name</label>
                        <input class="form-control mb-2" name="field_name" id="field_name" placeholder="e.g. birthday" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label" for="field_type">Field Type</label>
                        <select class="form-select mb-2" name="field_type" id="field_type" required>
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="email">Email</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                </div>

                <div class="#dynamicFieldsArea">

                </div>
                <input type="hidden" id="custom-field-id">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-info text-white" id="btnSaveField">Save Field</button>
            </div>

        </form>

    </div>
</x-static-modal>