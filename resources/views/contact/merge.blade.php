<x-static-modal heading="Merge Contacts" modalId="merge-modal" headingIconClass="bi bi-sign-merge-right me-2">

    <input type="hidden" id="primaryContactId">

    <div class="mb-3">
        <label class="form-label">Select Contact to Merge</label>
        <select id="mergeContactSelect" class="form-select"></select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Choose Master Contact</label>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="master" id="masterPrimary" value="primary" checked>
            <label class="form-check-label" for="masterPrimary">Keep Original Contact as Master</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="master" id="masterSecondary" value="secondary">
            <label class="form-check-label" for="masterSecondary">Use Selected Contact as Master</label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Conflict Policy</label>
        <select id="mergePolicy" class="form-select">
            <option value="keep_master">Keep master's values on conflict</option>
            <option value="append">Append differing values (emails/phones/custom)</option>
        </select>
    </div>

    <div class="mb-2 text-muted small">
        Note: Secondary contact will be marked as inactive and preserved. Files and custom fields will be copied/merged.
    </div>

    <x-slot name="footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="btnConfirmMerge" onclick="mergeContacts()">Merge</button>
    </x-slot>
</x-static-modal>