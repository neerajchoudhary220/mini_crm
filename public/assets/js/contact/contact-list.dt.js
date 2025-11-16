let contact_dt_tbl = "";
let simpleListUrl = "";
const mergeModal = $("#merge-modal");
const primaryContact = $("#primaryContactId");
const mergeContactSelect = $("#mergeContactSelect");

function dbTble() {
  contact_dt_tbl = $("#contact-dt-tbl").DataTable({
    serverSide: true,
    stateSave: false,
    pageLength: 10,
    responsive: false,
    language: {
      searchPlaceholder: "Search name,email,phone",
    },
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
    order: [5, "desc"],
    createdRow: function (row, data, dataIndex) {
      // Add data-label for each td based on its column title
      $("td", row).each(function (colIndex) {
        var columnTitle = contact_dt_tbl.column(colIndex).header().innerText;
        $(this).attr("data-label", columnTitle);
      });
    },
    drawCallback: function () {
      //Delete Contact
      $(".dlt-btn").on("click", function () {
        confirmDelete({
          url: $(this).data("url"),
          text: "You want to delete this contact",
          onSuccess: function () {
            reloadContactTable();
          },
        });
      });

      //Edit Contact
      $(".edt-btn").on("click", function () {
        resetContactForm();
        editContact($(this).data("edit-url"));
        contactForm.attr("action", $(this).data("update-url"));
      });
      //Merge Contact
      $(".merge-btn").on("click", function () {
        primaryContact.val($(this).data("contact-id"));
        simpleListUrl = $(this).data("simple-list-url");
        fetchSimpleList(simpleListUrl);
      });
    },
  });
}

//Gender Filter
function genderFilter(e) {
  contact_dt_tbl.settings()[0].ajax.data = {
    gender: e.value,
  };
  contact_dt_tbl.ajax.reload();
}
function reloadContactTable() {
  contact_dt_tbl.destroy();
  dbTble();
}

$(document).ready(function () {
  dbTble();
});

function editContact(editContactUrl) {
  formTitle.text("Edit Contact");
  saveBtn.text("Update");
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
  mergeModal.modal("show");
  //Fetch contact list with pagination using select2
  mergeContactSelect.select2({
    multiple: false,
    placeholder: "Search Contact",
    minimumInputLength: 1,
    width: "100%",
    dropdownParent: mergeContactSelect.parent(),
    ajax: {
      url: simpleListUrl,
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
  });
}

//Merge Contacts with confirmation
function mergeContacts() {
  const primaryId = primaryContact.val();
  const secondaryId = mergeContactSelect.val();
  const master = $("input[name='master']:checked").val();
  const policy = $("#mergePolicy").val();

  if (!secondaryId) {
    showToast("Select a contact to merge", "danger");
    return;
  }
  if (primaryId === secondaryId) {
    showToast("Cannot merge same contact", "danger");
    return;
  }

  Swal.fire({
    title: "Are you sure you want to merge these contacts?",
    text:
      "This action will mark the secondary contact as inactive and copy data into the master. Data is preserved in the merge log.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#0dcaf0",
    confirmButtonText: "Yes",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: mergeUrl, // set mergeUrl variable in blade: route('contacts.merge')
        method: "POST",
        data: {
          primary_id: primaryId,
          secondary_id: secondaryId,
          master: master,
          policy: policy,
          _token: $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
          $("#btnConfirmMerge").prop("disabled", true).text("Merging...");
        },
        success: function (res) {
          $("#btnConfirmMerge").prop("disabled", false).text("Merge");
          Swal.fire("Merged!", res.message, "success");
          mergeModal.hide();
          window.location.reload();
        },
        error: function (xhr) {
          $("#btnConfirmMerge").prop("disabled", false).text("Merge");
          if (xhr.responseJSON && xhr.responseJSON.message) {
            showToast(xhr.responseJSON.message, "danger");
          } else {
            showToast("Merge failed", "danger");
          }
        },
      });
    }
  });
}
