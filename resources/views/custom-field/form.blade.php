<div class="modal fade" id="custom-field-form-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="mb-0 modal-title modal-heading"><i class="bi bi-journals me-2"></i>
                    Add New Field
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="fieldForm" action="{{route('custom.fields.store')}}">
                        <div class="modal-body">

                            <input type="hidden" id="fieldId">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" for="field_type">Field Label</label>
                                    <input class="form-control mb-2" name="field_label" id="field_label" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" for="field_name">Field Name</label>
                                    <input class="form-control mb-2" name="field_name" id="field_name" placeholder="company_name" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" for="field_type">Field Type</label>
                                    <select class="form-select mb-2" name="field_type" id="field_type" required>
                                        <option value="text">Text</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="number">Number</option>
                                        <option value="email">Email</option>
                                        <option value="date">Date</option>
                                        <option value="select">Select (Dropdown)</option>
                                    </select>
                                </div>
                            </div>



                            <div class="row mb-3">
                                <div class="col-12">
                                    <div id="optionsArea" class="d-none">
                                        <label class="form-label">Options (comma separated)</label>
                                        <input class="form-control" id="options">
                                        <small class="text-muted">Example: Small, Medium, Large</small>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="custom-field-id">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" id="btnSaveField">Save Field</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>