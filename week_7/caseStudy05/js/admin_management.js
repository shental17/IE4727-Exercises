document.addEventListener("DOMContentLoaded", function () {
  // Modal functions
  //onClick function of "Add New" Button at the bottom of Products table
  //Makes the addProductModal visible
  window.openAddProductModal = function () {
    document.getElementById("addProductModal").style.display = "block";
  };

  //onClick function of "Edit" Button at the Products table
  //Makes the editProductModal visible
  window.openEditProductModal = function (id, name, description) {
    const form = document.getElementById("editProductForm");
    form.name.value = name;
    form.description.value = description;
    form.id.value = id;
    document.getElementById("editProductModal").style.display = "block";
  };

  //onClick function of "Add New" Button at the bottom of Categories table
  //Makes the addCategoryModal visible
  window.openAddCategoryModal = function () {
    document.getElementById("addCategoryModal").style.display = "block";
  };

  //onClick function of "Edit" Button at the Categories table
  //Makes the editCategoriesModal visible
  window.openEditCategoryModal = function (id, name, price, productId) {
    const form = document.getElementById("editCategoryForm");
    form.name.value = name;
    form.price.value = price;
    form.id.value = id;
    form.product_id.value = productId;
    document.getElementById("editCategoryModal").style.display = "block";
  };

  //Makes the modal not visible
  window.closeModal = function (modalId) {
    document.getElementById(modalId).style.display = "none";
  };

  // Form submission with AJAX
  // Common function to handle form submission
  function handleFormSubmit(formId) {
    document.getElementById(formId).onsubmit = function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("../utils/process.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          location.reload();
        });
    };
  }

  // Attach the common form submission handler to each form
  [
    "addProductForm",
    "editProductForm",
    "addCategoryForm",
    "editCategoryForm",
  ].forEach(handleFormSubmit);

  // Delete function
  window.deleteItem = function (action, id) {
    if (confirm("Are you sure you want to delete this item?")) {
      fetch("../utils/delete.php", {
        method: "POST",
        body: new URLSearchParams({ action: action, id: id }),
      })
        .then((response) => response.text())
        .then((data) => {
          location.reload();
        });
    }
  };

  console.log("Script ran!");
});
