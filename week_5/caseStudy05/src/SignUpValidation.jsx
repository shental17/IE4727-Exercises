import { useState } from "react";
import reactLogo from "./assets/react.svg";
import viteLogo from "/vite.svg";
// import FormValidationExample from "./FormValidationExample";
import "./App.css";

function SignUpValidation() {
  const [formData, setFormData] = useState({
    username: "",
    email: "",
    password: "",
    confirmPassword: "",
  });

  //setting up state variable errors to manage form validation errors
  const [errors, setErrors] = useState({});

  //setting up formStatus to manage form validation errors
  const [formStatus, setFormStatus] = useState({
    emailValid: false,
    passwordsMatch: false,
    passwordValid: false,
    isSubmitButtonEnabled: false,
  });

  // Validate password
  const checkPassword = (password) => {
    const requirements = [];

    if (password.length < 8) requirements.push("at least 8 characters");
    if (!/[a-z]/.test(password)) requirements.push("1 lowercase character");
    if (!/[A-Z]/.test(password)) requirements.push("1 uppercase character");
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password))
      requirements.push("1 special character");
    if (!/[0-9]/.test(password)) requirements.push("1 number");

    return requirements;
  };
  // Validation logic moved to a separate function
  const validateForm = (updatedData) => {
    // Check if email has .com
    const emailValid = /\S+@\S+\.\S+/.test(updatedData.email);
    // Check if passwords match after state update
    const passwordsMatch = updatedData.password === updatedData.confirmPassword;
    // Check password validity and get missing requirements
    const passwordRequirements = checkPassword(updatedData.password);
    const passwordValid = passwordRequirements.length === 0;
    // Check if all fields are filled after state update
    const allFieldsFilled = Object.values(updatedData).every((value) =>
      Boolean(value.trim())
    );

    setErrors((prevErrors) => ({
      ...prevErrors,
      username: updatedData.username ? null : "Name is required",
      email: updatedData.email
        ? !emailValid
          ? "Email is not valid"
          : null
        : "Email is required",
      password: updatedData.password
        ? !passwordValid
          ? `Password must contain:\n- ${passwordRequirements.join("\n- ")}`
          : null
        : "Password is required",
      confirmPassword: !passwordsMatch ? "Passwords do not match" : "",
    }));

    setFormStatus({
      emailValid,
      passwordsMatch,
      passwordValid,
      isSubmitButtonEnabled:
        allFieldsFilled && passwordsMatch && passwordValid && emailValid,
    });
  };

  //handleChange function is used to update the formData state based on the user input in the form fields
  const handleChange = (event) => {
    //extract the name (input fields) and value (user input) from the event target
    const { name, value } = event.target;
    setFormData((prevData) => {
      const updatedData = {
        //use ... spread operator to optain the existing data in formData and update the specific input field with the new value
        ...prevData,
        [name]: value.trimStart(),
      };
      validateForm(updatedData);
      return updatedData;
    });
  };

  //handleSubmit function is used to validate the form data when the form is submitted
  const handleSubmit = (event) => {
    //Prevent the default form submission behaviour
    //Check each form field for validation errors based on the defined criteria
    event.preventDefault();
    setFormData((prevData) => {
      const updatedData = {
        //use ... spread operator to optain the existing data in formData and update the specific input field with the new value
        ...prevData,
      };
      // Iterate through formData and trim each value
      for (let key in formData) {
        if (formData.hasOwnProperty(key)) {
          formData[key] = formData[key].trim();
        }
      }
      return updatedData;
    });
    alert("Form has been submitted successfully!");
    console.log(formData);

    // Reset the formData state to clear the form fields
    setFormData({
      username: "",
      email: "",
      password: "",
      confirmPassword: "",
    });
    setFormStatus({
      passwordsMatch: false,
      passwordValid: false,
      isSubmitButtonEnabled: false,
    });
    // Clear the input fields
    event.target.reset();
  };

  return (
    <>
      <form onSubmit={handleSubmit}>
        <h2>Sign Up</h2>
        <label>Username*</label>
        <input
          name="username"
          type="text"
          placeholder="username"
          className={errors.username ? "errors" : null}
          value={formData.username}
          onChange={handleChange}
          required={true}
        />
        {errors.username && <p>{errors.username}</p>}
        <label>Email*</label>
        <input
          name="email"
          type="email"
          placeholder="example@gmail.com"
          className={errors.email ? "errors" : null}
          value={formData.email}
          onChange={handleChange}
          required={true}
        />
        {errors.email && <p>{errors.email}</p>}
        <label>Password*</label>
        <input
          name="password"
          type="password"
          placeholder="Password"
          className={errors.password ? "errors" : null}
          value={formData.password}
          onChange={handleChange}
          required={true}
        />
        {errors.password && <p>{errors.password}</p>}
        <label>Confirm Password*</label>
        <input
          name="confirmPassword"
          type="password"
          placeholder="Confirm Password"
          className={errors.confirmPassword ? "errors" : null}
          value={formData.confirmPassword}
          onChange={handleChange}
          required={true}
        />
        {errors.confirmPassword && <p>{errors.confirmPassword}</p>}
        <button
          type="submit"
          disabled={!formStatus.isSubmitButtonEnabled}
          className={formStatus.isSubmitButtonEnabled ? "enabled" : "disabled"}
        >
          Submit
        </button>
      </form>
    </>
  );
}

export default SignUpValidation;
