// Get references to input elements by their IDs
var CafeNode1 = document.getElementById("Qty_Cafe_Single");
var CafeNode2 = document.getElementById("Qty_Cafe_Double");
var CafeNode3 = document.getElementById("Qty_Cafe");

// Add event listeners to the elements that listen for changes
CafeNode1.addEventListener("change", Cal_Cafe, false);
CafeNode2.addEventListener("change", Cal_Cafe, false);
CafeNode3.addEventListener("change", Cal_Cafe, false);

// Function to calculate cafe price
function Cal_Cafe(event) {
  // Get the element that triggered the event
  var qtyCafechk = event.currentTarget;

  // Check if the value entered is not a number
  var pos = qtyCafechk.value.search(/^[0-9]*$/);

  if (pos != 0) {
    // Alert the user if the value is not a number and reset the input and price
    alert(
      "You entered (" +
        qtyCafechk.value +
        ") is not correct. \n" +
        "Only NUMBER are allowed."
    );
    qtyCafechk.value = "";
    document.getElementById("Price_Cafe").value = "";
    qtyCafechk.focus();
    return false;
  }

  if (CafeNode1.checked) {
    Total_Cafe_Price = CafeNode1.value * CafeNode3.value;
    // Find element with ID Price_Cafe in menu.html, and update the value
    // Calculate sub total price
    document.getElementById("Price_Cafe").value =
      parseFloat(Total_Cafe_Price).toFixed(2);
  }

  if (CafeNode2.checked) {
    Total_Cafe_Price = CafeNode2.value * CafeNode3.value;
    document.getElementById("Price_Cafe").value =
      parseFloat(Total_Cafe_Price).toFixed(2);
  }
}

// Calculate total price
Cal_total();
