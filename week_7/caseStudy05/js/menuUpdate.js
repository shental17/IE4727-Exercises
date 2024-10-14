document.addEventListener("DOMContentLoaded", function () {
  updateProductSubtotal = function (productId) {
    // Select all input elements with the same data-product-id
    const inputs = document.querySelectorAll(
      `input[data-product-id='${productId}']`
    );
    let productSubtotal = 0;

    // Loop through category rows to sum their subtotals
    inputs.forEach((input) => {
      const price = parseFloat(input.getAttribute("data-price")) || 0; // Get the data-price attribute
      console.log("input price: " + price);
      const quantity = parseInt(input.value) || 0; // Default to 0 if the input is
      console.log("input quantity: " + quantity);
      const subtotal = price * quantity; // Calculate subtotal for the category
      console.log("input subtotal: " + subtotal);
      productSubtotal += subtotal; // Sum up
    });
    console.log("product subtotal: " + productSubtotal);

    // Update the overall product subtotal
    const productSubtotalCell = document.querySelector(
      `.subtotal[data-product-id='${productId}']`
    );
    console.log("productSubtotalCell productId: " + productId);
    if (productSubtotalCell) {
      // Check if the subtotal cell exists
      productSubtotalCell.value = `$${productSubtotal.toFixed(2)}`; // Update product subtotal
      console.log("updated: " + productSubtotalCell);
    }

    // Call updateTotalPrice to refresh the overall total price
    updateTotalPrice(); // Ensure total price is updated after changing product subtotal
  };

  updateTotalPrice = function () {
    const subtotalInputs = document.querySelectorAll(".subtotal");
    let totalPrice = 0;

    subtotalInputs.forEach((input) => {
      const inputValue = input.value || ""; // Default to empty string if undefined
      const subtotal =
        parseFloat(inputValue.replace("$", "").replace(/,/g, "").trim()) || 0; // Remove the dollar sign and commas, then convert to float
      totalPrice += subtotal;
      console.log("subtotal Input:" + subtotal);
    });

    console.log("total Price: " + totalPrice);
    document.getElementById("totalPrice").value = `$${totalPrice.toFixed(2)}`; // Update total price field
  };

  checkout = function () {
    const formData = new FormData(); // Create a FormData object
    const inputs = document.querySelectorAll("input[data-category-id]"); // Select all quantity inputs

    inputs.forEach((input) => {
      const categoryId = input.getAttribute("data-category-id"); // Get the category ID
      const quantity = parseInt(input.value, 10) || 0; // Get the quantity, defaulting to 0

      // Only include category ID if the quantity is greater than 0
      if (quantity > 0) {
        // Append the category ID and quantity to the FormData object
        formData.append("category_id[]", categoryId);
        formData.append("quantity[]", quantity);
      }
    });

    // Send the FormData to the server for insertion into the database
    fetch("../utils/checkout.php", {
      method: "POST",
      body: formData, // Send the FormData
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.text(); // Get the response as text first
      })
      .then((data) => {
        console.log("Response data:", data); // Log the raw response
        // Optionally handle response here
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  // Attach event listeners to quantity inputs
  const quantityInputs = document.querySelectorAll(".quantity");
  quantityInputs.forEach((input) => {
    input.addEventListener("input", function () {
      updateProductSubtotal(input.getAttribute("data-product-id")); // Correct function call
    });
  });
});
