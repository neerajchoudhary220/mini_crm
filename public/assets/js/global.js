const confirmDelete = (options = {}) => {
  const {
    url,
    onSuccess = null,
    title = "Are you sure?",
    text = "You want to delete this item.",
    confirmText = "Yes, delete it!",
  } = options;

  Swal.fire({
    title: title,
    text: text,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#0dcaf0",
    confirmButtonText: confirmText,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: url,
        method: "delete",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
          Swal.fire("Deleted!", data.msg, "success");

          if (typeof onSuccess === "function") {
            onSuccess(data);
          }
        },
      });
    }
  });
};
