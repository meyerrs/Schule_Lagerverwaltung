import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import App from "./App";
import Inventory from "./pages/Inventory";
//hier import für den Skip-Login




// nur für Entwicklung
const SKIP_LOGIN = true;

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    {SKIP_LOGIN ? (
      <Inventory /> //Hier Komponente angeben, welche geladen werden soll
    ) : (
      <BrowserRouter>
        <App />
      </BrowserRouter>
    )}
  </React.StrictMode>
);


