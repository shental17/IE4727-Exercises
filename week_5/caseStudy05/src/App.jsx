import { useState } from "react";
import reactLogo from "./assets/react.svg";
import viteLogo from "/vite.svg";
// import FormValidationExample from "./FormValidationExample";
import SignUpValidation from "./SignUpValidation";
import LoginValidation from "./LoginValidation";
import "./App.css";

function App() {
  const [isLoginPage, setIsLoginPage] = useState(true);

  const togglePage = () => {
    setIsLoginPage((prevIsLoginPage) => !prevIsLoginPage);
  };

  return (
    <div className="page">
      {isLoginPage ? (
        <>
          <LoginValidation />
          <span>
            Don't have an account? <a onClick={togglePage}>Sign Up</a>
          </span>
        </>
      ) : (
        <>
          <SignUpValidation />
          <span>
            Already have an account? <a onClick={togglePage}>Login</a>
          </span>
        </>
      )}
    </div>
  );
}

export default App;
