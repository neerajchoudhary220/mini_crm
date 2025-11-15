<div class="modal fade" id="contact-form-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="mb-0 modal-title"><i class="bi bi-journals me-2"></i>
                    Add Contact
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="contactForm" enctype="multipart/form-data">
                        <input type="hidden" id="contactId">

                        <!-- HEADER -->
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1" id="formTitle">Add New Contact</h4>
                            <p class="text-muted">Fill the information below to save the contact</p>
                            <hr>
                        </div>

                        <!-- PROFILE IMAGE -->
                        <div class="text-center mb-4">
                            <img id="previewImage" src="https://via.placeholder.com/120"
                                class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                            <div class="mt-2">
                                <label class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-camera"></i> Choose Image
                                    <input type="file" id="profile_image" name="profile_image" hidden accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="row g-3">

                            <!-- NAME -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-user text-primary"></i>Name
                                </label>
                                <input type="text" class="form-control form-control-lg" id="name" required>
                                <small class="text-muted">Enter the contact's full name</small>
                            </div>

                            <!-- EMAIL -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-envelope text-danger"></i> Email
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email">
                                <small class="text-muted">We'll use this email for communication</small>
                            </div>

                            <!-- PHONE -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-phone text-success"></i> Phone
                                </label>
                                <input type="text" class="form-control form-control-lg" id="phone">
                            </div>

                            <!-- GENDER -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">
                                    <i class="fa fa-venus-mars text-info"></i> Gender
                                </label>

                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="gender" value="male" id="genderMale">
                                    <label class="btn btn-outline-primary" for="genderMale">Male</label>

                                    <input type="radio" class="btn-check" name="gender" value="female" id="genderFemale">
                                    <label class="btn btn-outline-primary" for="genderFemale">Female</label>

                                    <input type="radio" class="btn-check" name="gender" value="other" id="genderOther">
                                    <label class="btn btn-outline-primary" for="genderOther">Other</label>
                                </div>
                            </div>

                            <!-- DOCUMENT UPLOAD -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-file text-warning"></i> Additional Document
                                </label>

                                <input type="file" class="form-control" id="document" accept=".pdf,.jpg,.png,.jpeg">
                                <small class="text-muted">You may upload a PDF or image</small>
                            </div>

                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Custom Field</label>

                            <select id="customFieldSelect" class="form-select multiple" style="width: 100%"></select>

                            <small class="text-muted">Search custom fields using Select2</small>
                        </div>

                        <div id="dynamicFieldsArea"></div>

                        <!-- ACTION BUTTONS -->
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times"></i> Cancel
                            </button>

                            <button type="submit" class="btn btn-primary" id="btnSave">
                                <i class="fa fa-save"></i> Save Contact
                            </button>
                        </div>
                    </form>

                    <style>
                        /* Smooth form look */
                        #contactForm .form-control-lg {
                            border-radius: 10px;
                        }
                    </style>

                </div>
            </div>

        </div>
    </div>
</div>