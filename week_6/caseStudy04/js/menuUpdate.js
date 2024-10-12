document.addEventListener("DOMContentLoaded", function () {
  var justJavaQty = document.getElementsByName("justJavaqty")[0];
  var laitSingleQty = document.getElementsByName("laitSingleqty")[0];
  var laitDoubleQty = document.getElementsByName("laitDoubleqty")[0];
  var cappucinoSingleQty = document.getElementsByName("cappucinoSingleQty")[0];
  var cappucinoDoubleQty = document.getElementsByName("cappucinoDoubleQty")[0];
  var totalPriceText = document.getElementById("totalPrice");

  function calculateTotalPrice() {
    const justJavaPrice = 2;
    const laitSinglePrice = 2;
    const laitDoublePrice = 3;
    const cappucinoSinglePrice = 4.75;
    const cappucinoDoublePrice = 5.75;

    const justJavaQtyValue = parseFloat(justJavaQty.value) || 0;
    const laitSingleQtyValue = parseFloat(laitSingleQty.value) || 0;
    const laitDoubleQtyValue = parseFloat(laitDoubleQty.value) || 0;
    const cappucinoSingleQtyValue = parseFloat(cappucinoSingleQty.value) || 0;
    const cappucinoDoubleQtyValue = parseFloat(cappucinoDoubleQty.value) || 0;

    const totalPrice =
      justJavaQtyValue * justJavaPrice +
      laitSingleQtyValue * laitSinglePrice +
      laitDoubleQtyValue * laitDoublePrice +
      cappucinoSingleQtyValue * cappucinoSinglePrice +
      cappucinoDoubleQtyValue * cappucinoDoublePrice;

    totalPriceText.value = `$${totalPrice.toFixed(2)}`;
  }

  // Add event listeners to all input fields
  justJavaQty.addEventListener("input", calculateTotalPrice);
  laitSingleQty.addEventListener("input", calculateTotalPrice);
  laitDoubleQty.addEventListener("input", calculateTotalPrice);
  cappucinoSingleQty.addEventListener("input", calculateTotalPrice);
  cappucinoDoubleQty.addEventListener("input", calculateTotalPrice);

  // Initial calculation
  calculateTotalPrice();
});
