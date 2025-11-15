$(document).ready(function () {
  const contactFormModal = $("#contact-form-modal");
  //Click to add contact button
  $("#add-new-contact-btn").on("click", () => {
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

  $("#customFieldSelect").select2({
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
    dropdownParent: $("#customFieldSelect").parent(),
  });

  function generateFieldHTML(field, value = "") {
    let html = "";

    switch (field.field_type) {
      case "text":
      case "email":
      case "number":
      case "date":
        html = `
            <div class="mb-3 dynamic-field" id="field_${field.id}">
                <label class="form-label fw-semibold">${field.field_label}</label>
                <input type="${field.field_type}" 
                       name="custom[${field.id}]" 
                       value="${value}"
                       class="form-control">
            </div>`;
        break;

      case "textarea":
        html = `
            <div class="mb-3 dynamic-field" id="field_${field.id}">
                <label class="form-label fw-semibold">${field.field_label}</label>
                <textarea name="custom[${field.id}]" 
                          class="form-control" rows="3">${value}</textarea>
            </div>`;
        break;

      case "select":
        let options = JSON.parse(field.options);
        let optionHTML = options
          .map(
            (o) =>
              `<option value="${o}" ${
                value === o ? "selected" : ""
              }>${o}</option>`
          )
          .join("");

        html = `
            <div class="mb-3 dynamic-field" id="field_${field.id}">
                <label class="form-label fw-semibold">${field.field_label}</label>
                <select name="custom[${field.id}]" class="form-select">
                    ${optionHTML}
                </select>
            </div>`;
        break;
    }

    return html;
  }

  $("#customFieldSelect").on("select2:select", function (e) {
    let fieldId = e.params.data.id;
    $.get(`${customFieldsUrl}/${fieldId}`, function (field) {
      let html = generateFieldHTML(field);
      $("#dynamicFieldsArea").append(html);
    });
  });
});
