<x-static-modal heading="Merge Contacts" modalId="merge-modal" headingIconClass="bi bi-sign-merge-right me-2">

    <input type="hidden" id="primaryContactId">

    <div class="mb-3">
        <label class="form-label">Select Contact to Merge Into</label>
        <select id="mergeContactSelect" class="form-select"></select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Choose Master Contact</label>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="master" value="primary" checked>
            <label class="form-check-label">
                Keep Original Contact as Master
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="master" value="secondary">
            <label class="form-check-label">
                Use Selected Contact as Master
            </label>
        </div>
    </div>


    <x-slot name="footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="mergeContacts()">Merge</button>
    </x-slot>
</x-static-modal>