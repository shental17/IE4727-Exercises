import { useState } from "react";
import "./App.css";

function LoginValidation() {
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });

  const [errors, setErrors] = useState({});
  const [formStatus, setFormStatus] = useState({
    emailValid: false,
    passwordValid: false,
    isSubmitButtonEnabled: false,
  });

  // Validation logic for email and password
  const validateForm = (updatedData) => {
    const emailValid = /\S+@\S+\.\S+/.test(updatedData.email);
    const passwordValid = updatedData.password.length >= 8;

    setErrors((prevErrors) => ({
      ...prevErrors,
      email: updatedData.email
        ? !emailValid
          ? "Email is not valid"
          : null
        : "Email is required",
      password: updatedData.password
        ? !passwordValid
          ? "Password invalid"
          : null
        : "Password is required",
    }));

    setFormStatus({
      emailValid,
      passwordValid,
      isSubmitButtonEnabled: emailValid && passwordValid,
    });
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setFormData((prevData) => {
      const updatedData = {
        ...prevData,
        [name]: value.trimStart(),
      };
      validateForm(updatedData);
      return updatedData;
    });
  };

  const handleSubmit = (event) => {
    event.preventDefault();
    setFormData((prevData) => {
      const updatedData = {
        ...prevData,
      };
      for (let key in formData) {
        if (formData.hasOwnProperty(key)) {
          formData[key] = formData[key].trim();
        }
      }
      return updatedData;
    });
    alert("Login successful!");
    console.log(formData);

    setFormData({
      email: "",
      password: "",
    });
    setFormStatus({
      emailValid: false,
      passwordValid: false,
      isSubmitButtonEnabled: false,
    });
    event.target.reset();
  };

  return (
    <>
      <form onSubmit={handleSubmit}>
        <h2>Login</h2>
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
        <button
          type="submit"
          disabled={!formStatus.isSubmitButtonEnabled}
          className={formStatus.isSubmitButtonEnabled ? "enabled" : "disabled"}
        >
          Login
        </button>
      </form>
    </>
  );
}

export default LoginValidation;
