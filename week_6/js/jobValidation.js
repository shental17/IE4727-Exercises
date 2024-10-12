document.addEventListener("DOMContentLoaded", function () {
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const startDateInput = document.getElementById("startDate");
  const experienceInput = document.getElementById("experience");
  const submitButton = document.getElementById("submitButton");
  const form = document.querySelector("form");

  const nameError = document.getElementById("nameError");
  const emailError = document.getElementById("emailError");
  const startDateError = document.getElementById("startDateError");
  const experienceError = document.getElementById("experienceError");

  function validateDate(date) {
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(23, 59, 59, 999); // Set hours to 23:59:59 for comparison

    // Check if the selected date is today or in the past
    return selectedDate > today || isNaN(selectedDate.getTime());
  }

  function validateForm() {
    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const startDate = startDateInput.value;
    const experience = experienceInput.value.trim();

    const nameValid = /^[A-Za-z\s]+$/.test(name);
    const emailValid = /\S+@\S+\.\S+/.test(email);
    const startDateValid = validateDate(startDate);
    const allFieldsFilled = [name, email, experience].every(
      (value) => value.length > 0
    );

    nameError.textContent = name
      ? nameValid
        ? "" // No error message if the name is valid
        : "Name can only have alphabet characters and spaces"
      : "Name is required";

    emailError.textContent = email
      ? emailValid
        ? "" // No error message if the email is valid
        : "Email is not valid"
      : "Email is required";

    startDateError.textContent = startDate
      ? startDateValid
        ? "" // No error message if the date is valid
        : "The date cannot be today or in the past."
      : ""; // No error message if the date is not inputed

    experienceError.textContent = experience ? "" : "Experience is required";

    const isSubmitButtonEnabled =
      allFieldsFilled &&
      nameValid &&
      emailValid &&
      (startDate ? startDateValid : true);
    submitButton.disabled = !isSubmitButtonEnabled;
    submitButton.className = isSubmitButtonEnabled ? "enabled" : "disabled";
  }

  nameInput.addEventListener("input", validateForm);
  emailInput.addEventListener("input", validateForm);
  startDateInput.addEventListener("input", validateForm);
  experienceInput.addEventListener("input", validateForm);

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    alert("Form has been submitted successfully!");
    console.log({
      name: nameInput.value.trim(),
      email: emailInput.value.trim(),
      startDate: startDateInput.value,
      experience: experienceInput.value.trim(),
    });

    // Reset the form
    form.reset();
    validateForm(); // To reset button state
  });
});
