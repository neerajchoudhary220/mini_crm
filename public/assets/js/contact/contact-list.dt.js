let contact_dt_tbl = "";
const mergeModal = $("#merge-modal");
const primaryContact = $("#primaryContactId");
function dbTble() {
  contact_dt_tbl = $("#contact-dt-tbl").DataTable({
    serverSide: true,
    stateSave: false,
    pageLength: 10,
    responsive: false,
    ajax: {
      url: contactListUrl,
      data: {
        // 'category':$("#categories :selected").val()
      },
    },
    columns: [
      { name: "idx", data: "idx" },
      { name: "name", data: "name" },
      { name: "email", data: "email" },
      { name: "phone", data: "phone" },
      { name: "gender", data: "gender" },
      {
        name: "created_at",
        data: "created_at_display",
        render: function (data, type, row) {
          if (type === "sort") {
            return row.created_at;
          }
          return data;
        },
      },
      { name: "action", data: "action", orderable: false },
    ],
    order: [1, "desc"],
    createdRow: function (row, data, dataIndex) {
      // Add data-label for each td based on its column title
      $("td", row).each(function (colIndex) {
        var columnTitle = contact_dt_tbl.column(colIndex).header().innerText;
        $(this).attr("data-label", columnTitle);
      });
    },
    drawCallback: function () {
      //Edit Contact
      $(".edt-btn").on("click", function () {
        resetContactForm();
        editContact($(this).data("edit-url"));
        contactForm.attr("action", $(this).data("update-url"));
      });
      //Merge Contact
      $(".merge-btn").on("click", function () {
        const simpleListUrl = $(this).data("simple-list-url");
        primaryContact.val($(this).data("contact-id"));
        fetchSimpleList(simpleListUrl);
      });
    },
  });
}

function editContact(editContactUrl) {
  $.ajax({
    url: editContactUrl,
    method: "GET",
    success: function (res) {
      const data = res.data;
      $("#name").val(data.name);
      $("#phone").val(data.phone);
      $("#email").val(data.email);
      $(`input[name='gender'][value=${data.gender}]`).prop("checked", true);
      $("#previewImage").attr("src", data.profile_image);
      $("#dynamicFieldsArea").html("");
      customFieldSelect.val(null).trigger("change");
      let selected = [];
      data.custom_fields.forEach((field) => {
        selected.push(field.id);
        let option = new Option(field.field_label, field.id, true, true);
        customFieldSelect.append(option);
        let html = generateFieldHTML(field, field.value);
        $("#dynamicFieldsArea").append(html);
      });
      customFieldSelect.trigger("change");
      contactFormModal.modal("show");
    },
    error: function (error) {},
  });
}

function fetchSimpleList(simpleListUrl) {
  // $("#primaryContactId").val(id);
  $("#mergeContactSelect").empty();

  $.ajax({
    url: simpleListUrl,
    method: "GET",
    success: function (res) {
      res.forEach((c) => {
        $("#mergeContactSelect").append(
          `<option value="${c.id}">${c.name} - ${c.email}</option>`
        );
      });
    },
    error: function (error) {
      console.error(error);
    },
  });
  mergeModal.modal("show");
}

function mergeContacts() {
  $.ajax({
    url: mergeUrl,
    method: "POST",
    data: {
      primary_id: primaryContact.val(),
      secondary_id: $("#mergeContactSelect").val(),
      master: $("input[name='master']:checked").val(),
      _token: $('meta[name="csrf-token"]').attr("content"),
    },
    success: function (res) {
      showToast(res.message, "success");
      mergeModal.modal("hide");
      reloadContactTable();
    },
    error: function () {
      showToast("Something went wrong!", "danger");
    },
  });
}

function reloadContactTable() {
  contact_dt_tbl.destroy();
  dbTble();
}

$(document).ready(function () {
  dbTble();
});
