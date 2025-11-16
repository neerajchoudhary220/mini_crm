function saveContact() {
  let formData = new FormData();
  //Basic Fields
  formData.append("name", $("#name").val());
  formData.append("email", $("#email").val());
  formData.append("phone", $("#phone").val());
  formData.append("gender", $("input[name='gender']:checked").val());

  //Files
  let profile = $("#profile_image")[0].files[0];
  if (profile) formData.append("profile_image", profile);

  let doc = $("#document")[0].files[0];
  if (doc) formData.append("document", doc);

  // Custom fields
  $(".custom-field").each(function () {
    formData.append($(this).attr("name"), $(this).val());
  });

  $.ajax({
    url: contactForm.attr("action"),
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    beforeSend: () => {
      $("#btnSave").prop("disabled", true).text("Saving...");
    },
    success: function (res) {
      contactFormModal.modal("hide");
      $("#btnSave").prop("disabled", false).text("Save Contact");
      reloadContactTable();
      showToast(res.message, "success");
    },
    error: function (xhr) {
      $("#btnSave").prop("disabled", false).text("Save Contact");

      if (xhr.status === 422) {
        let msg = Object.values(xhr.responseJSON.errors)[0][0];
        showToast(msg, "danger");
      } else {
        showToast("Something went wrong!", "danger");
      }
    },
  });
}
$(document).ready(function () {
  //Click to add contact button
  $("#add-new-contact-btn").on("click", () => {
    resetContactForm();
    contactForm[0].reset();
    formTitle.text("Add Contact");
    saveBtn.text("Save");

    contactFormModal.modal("show");
  });

  //Preview Profile Image
  $("#profile_image").change(function () {
    let file = this.files[0];
    if (file) {
      let reader = new FileReader();
      reader.onload = function (e) {
        $("#previewImage").attr("src", e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });

  customFieldSelect.select2({
    multiple: true,
    ajax: {
      url: fieldListUrl,
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          page: params.page || 1,
        };
      },
      processResults: function (data, params) {
        params.page = params.page || 1;

        return {
          results: data.results,
          pagination: {
            more: data.pagination.more,
          },
        };
      },
    },
    placeholder: "Search custom field",
    minimumInputLength: 1,
    width: "100%",
    dropdownParent: customFieldSelect.parent(),
  });

  //When select custom field
  customFieldSelect.on("select2:select", function (e) {
    let fieldId = e.params.data.id;
    $.get(`${customFieldsUrl}/${fieldId}`, function (field) {
      let html = generateFieldHTML(field);
      $("#dynamicFieldsArea").append(html);
    });
  });

  //validate form
  contactForm.validate({
    rules: {
      name: {
        required: true,
        minlength: 3,
        maxlength: 20,
      },
      email: {
        required: true,
        email: true,
      },
      phone: {
        required: true,
        digits: true,
        minlength: 8,
        maxlength: 15,
      },
      profile_image: { extension: "jpg|jpeg|png" },
      document: { extension: "pdf|jpg|jpeg|png" },
    },
    messages: {
      name: "Enter a valid name",
      email: "Enter valid email",
      phone: "Phone must be digits only",
      profile_image: "Allowed: jpg, jpeg, png",
      document: "Allowed: pdf, jpg, jpeg, png",
    },
    errorClass: "text-danger small border-danger",
    errorPlacement: function (error, element) {
      error.insertAfter(element.parent());
    },
    submitHandler: function () {
      saveContact();
    },
  });
});
