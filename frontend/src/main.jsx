import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import App from "./App";
import Admin from "./pages/Admin"

// nur für Entwicklung
const SKIP_LOGIN = true;

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    {SKIP_LOGIN ? (
      <Admin />
    ) : (
      <BrowserRouter>
        <App />
      </BrowserRouter>
    )}
  </React.StrictMode>
);


