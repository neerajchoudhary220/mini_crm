<x-static-modal heading="Contact" modalId="contact-form-modal" headingIconClass="bi bi-journals me-2">
    <div class="row">
        <form id="contactForm" action="{{route('contacts.store')}}">
            <input type="hidden" id="contactId">

            <!-- HEADER -->
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1" id="formTitle">Add New Contact</h4>
                <p class="text-muted">Fill the information below to save the contact</p>
                <hr>
            </div>

            <!-- PROFILE IMAGE -->
            <div class="text-center mb-4">
                <img id="previewImage" src="{{asset('assets/img/profile_placeholder.png')}}"
                    class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                <div class="mt-2">
                    <label class="btn btn-outline-info btn-sm">
                        <i class="fa fa-camera"></i> Choose Image
                        <input type="file" id="profile_image" name="profile_image" hidden accept="image/*">
                    </label>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <!-- NAME -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="name">
                        <i class="fa fa-user text-info"></i>Name
                    </label>
                    <input type="text" class="form-control form-control-lg" placeholder="Enter full name" name="name" id="name">
                </div>

                <!-- EMAIL -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="email">
                        <i class="fa fa-envelope text-info"></i> Email
                    </label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="e.g. example@mail.com">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <!-- PHONE -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="phone">
                        <i class="fa fa-phone text-info"></i> Phone
                    </label>
                    <input type="text" name="phone" class="form-control form-control-lg" id="phone" placeholder="e.g. 9876543210">
                </div>

                <!-- GENDER -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold d-block" for="gender">
                        <i class="fa fa-venus-mars text-info"></i> Gender
                    </label>

                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="gender" value="male" id="genderMale" checked>
                        <label class="btn btn-outline-primary" for="genderMale">Male</label>

                        <input type="radio" class="btn-check" name="gender" value="female" id="genderFemale">
                        <label class="btn btn-outline-primary" for="genderFemale">Female</label>

                        <input type="radio" class="btn-check" name="gender" value="other" id="genderOther">
                        <label class="btn btn-outline-primary" for="genderOther">Other</label>
                    </div>
                </div>
            </div>


            <!-- DOCUMENT UPLOAD -->
            <div class="col-md-12">
                <label class="form-label fw-semibold" for="document">
                    <i class="fa fa-file text-warning"></i> Additional Document
                </label>

                <input type="file" class="form-control" id="document" accept=".pdf,.jpg,.png,.jpeg">
                <small class="text-muted">You may upload a PDF or image</small>
            </div>

    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Select Custom Field</label>

        <select id="customFieldSelect" class="form-select multiple" style="width: 100%"></select>

        <!-- <small class="text-muted">Search custom fields using Select2</small> -->
    </div>
    <div id="dynamicFieldsArea"></div>
    <!-- ACTION BUTTONS -->
    <div class="mt-4 text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times"></i> Cancel
        </button>
        <button class="btn btn-info text-white" id="btnSave"><i class="fa fa-save"></i> Contact
        </button>
    </div>
    </form>
</x-static-modal>