import { useState } from "react";
import "./App.css";

const FormValidationExample = () => {
  const [formData, setFormData] = useState({
    username: "",
    email: "",
    password: "",
    confirmPassword: "",
  });

  //setting up state variable errors to manage form validation errors
  const [errors, setErrors] = useState({});
  const validateForm = (data) => {
    const validationErrors = {};
    if (!formData.username.trim()) {
      validationErrors.username = "Username is required";
    }
    if (!formData.email.trim()) {
      validationErrors.email = "Email is required";
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      validationErrors.email = "Email is not valid";
    }
    if (!formData.password.trim()) {
      validationErrors.password = "Password is required";
    } else if (formData.password.length < 6) {
      validationErrors.password = "Password should have at least 6 characters";
    }
    if (formData.password != formData.confirmPassword) {
      validationErrors.confirmPassword = "Password not matched";
    }
    return validationErrors;
  };

  //handleChange function is used to update the formData state based on the user input in the form fields
  const handleChange = (event) => {
    //extract the name (input fields) and value (user input) from the event target
    const { name, value } = event.target;
    setFormData((prevData) => {
      const updatedData = {
        //use ... spread operator to optain the existing data in formData and update the specific input field with the new value
        ...prevData,
        [name]: value,
      };
      return updatedData;
    });
  };

  //handleSubmit function is used to validate the form data when the form is submitted
  const handleSubmit = (event) => {
    //Prevent the default form submission behaviour
    //Check each form field for validation errors based on the defined criteria
    event.preventDefault();
    const validationErrors = validateForm(formData);
    //If there are no validation errors, an alert is shown and the input fields are cleared
    if (Object.keys(validationErrors).length === 0) {
      alert("Form has been submitted successfully!");
      console.log(formData);
      //Clear all input fields
      event.target.reset();
      // Reset the formData state to clear the form fields
      setFormData({
        username: "",
        email: "",
        password: "",
        confirmPassword: "",
      });
      setErrors({});
    } else {
      setErrors(validationErrors);
    }
  };

  return (
    <>
      <form onSubmit={handleSubmit}>
        <label>Username</label>
        <input
          name="username"
          type="text"
          placeholder="username"
          value={formData.username}
          onChange={handleChange}
          required={true}
        />
        {errors.username && <p>{errors.username}</p>}
        <label>Email</label>
        <input
          name="email"
          type="email"
          placeholder="example@gmail.com"
          value={formData.email}
          onChange={handleChange}
          required={true}
        />
        {errors.email && <p>{errors.email}</p>}
        <label>Password</label>
        <input
          name="password"
          type="password"
          placeholder="Password"
          value={formData.password}
          onChange={handleChange}
          required={true}
        />
        {errors.password && <p>{errors.password}</p>}
        <label>Confirm Password</label>
        <input
          name="confirmPassword"
          type="password"
          placeholder="Confirm Password"
          value={formData.confirmPassword}
          onChange={handleChange}
          required={true}
        />
        {errors.confirmPassword && <p>{errors.confirmPassword}</p>}
        <button type="submit" className="enabled">
          Submit
        </button>
      </form>
    </>
  );
};

export default FormValidationExample;
