//Save Field
function submitForm() {
  let formData = {
    field_label: $("#field_label").val(),
    field_name: $("#field_name").val(),
    field_type: $("#field_type").val(),
    options: $("#options").val(), // only shown for select
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
  //when choose the select option
  $("#field_type").on("change", function () {
    if ($(this).val() === "select") {
      $("#optionsArea").removeClass("d-none");
    } else {
      $("#optionsArea").addClass("d-none");
    }
  });

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
      options: {
        required: function () {
          console.log($("#field_type").val());
          return $("#field_type").val() === "select";
        },
        // comma-separated validation
        pattern: /^[^,\s][^,]*(?:,[^,\s][^,]*)*$/,
      },
    },

    messages: {
      field_label: "Please enter label",
      field_name: "Enter a valid key (example: birthday)",
      field_type: "Select type",
      // Error message for select options
      options: {
        required: "Provide comma-separated options",
        pattern: "Options must be comma separated (ex: Small,Medium,Large)",
      },
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
