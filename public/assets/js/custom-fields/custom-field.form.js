//Save Field
function submitForm() {
  let formData = {
    field_label: $("#field_label").val(),
    field_name: $("#field_name").val(),
    field_type: $("#field_type").val(),
  };

  $.ajax({
    url: fieldForm.attr("action"),
    method: "POST",
    data: formData,
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    beforeSend: function () {
      $("#btnSaveField").attr("disabled", true).text("Saving...");
    },
    success: function (res) {
      // Reset button
      $("#btnSaveField").attr("disabled", false).text("Save Field");
      // Close modal
      customFieldFormModal.modal("hide");
      showToast(res.message, "success");
      reloadContactFieldTable(); //reload custom field table
    },

    error: function (xhr) {
      $("#btnSaveField").attr("disabled", false).text("Save Field");

      if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        let msg = Object.values(errors)[0][0];
        showToast(msg, "danger");
      } else {
        showToast("Something went wrong!", "danger");
      }
    },
  });
}

$(document).ready(function () {
  //Click to add new custom field btn
  fieldForm.validate({
    rules: {
      field_label: {
        required: true,
        minlength: 3,
      },
      field_name: {
        required: true,
        minlength: 3,
      },
      field_type: {
        required: true,
      },
    },

    messages: {
      field_label: "Please enter label",
      field_name: "Enter a valid key (example: birthday)",
      field_type: "Select type",
    },
    errorClass: "text-danger small border-danger",
    errorPlacement: function (error, element) {
      error.insertAfter(element.parent());
    },
    submitHandler: function () {
      submitForm();
    },
  });
});
