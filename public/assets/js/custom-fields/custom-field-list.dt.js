let custom_field_dt = "";
function customFieldDT() {
  custom_field_dt = $("#custom-field-dt-tbl").DataTable({
    serverSide: true,
    stateSave: false,
    pageLength: 10,
    responsive: false,
    language: {
      searchPlaceholder: "Search label",
    },
    ajax: {
      url: customFieldListUrl,
    },
    columns: [
      { name: "idx", data: "idx" },
      { name: "field_label", data: "field_label" },
      { name: "field_name", data: "field_name" },
      { name: "field_type", data: "field_type" },
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
    order: [4, "desc"],
    createdRow: function (row, data, dataIndex) {
      // Add data-label for each td based on its column title
      $("td", row).each(function (colIndex) {
        var columnTitle = custom_field_dt.column(colIndex).header().innerText;
        $(this).attr("data-label", columnTitle);
      });
    },
    drawCallback: function () {
      //Click to delete button
      $(".dlt-btn").on("click", function () {
        confirmDelete({
          url: $(this).data("url"),
          text: "You want to delete this field",
          onSuccess: function () {
            reloadContactFieldTable();
          },
        });
      });
      //click to edit button
      $(".edt-btn").on("click", function () {
        btnSaveField.text("Update Field");
        fieldForm.attr("action", $(this).data("update-url"));
        customFieldFormModal.modal("show");
        customFieldFormModal.find(".modal-heading").text("Edit Field");
        customFieldEdit($(this).data("edit-url"));
      });
    },
  });
}

function customFieldEdit(updateUrl) {
  $.ajax({
    url: updateUrl,
    method: "GET",
    success: function (res) {
      const fields = ["field_label", "field_name", "field_type"];
      fields.forEach((id) => {
        $("#" + id).val(res[id]);
      });
    },
    error: function (error) {
      console.error(error);
    },
  });
}

//Filter type filter
function filedTypeFilter(e) {
  custom_field_dt.settings()[0].ajax.data = {
    field_type: e.value,
  };
  custom_field_dt.ajax.reload();
}
function reloadContactFieldTable() {
  custom_field_dt.destroy();
  customFieldDT();
}

function putCustomFieldFormValue(fieldName, fieldValue) {
  resetCustomFieldForm();
  $(`#fieldForm [name="${fieldName}"]`).val(fieldValue);
}

$(document).ready(function () {
  customFieldDT();
});
