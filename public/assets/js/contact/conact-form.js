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
});
