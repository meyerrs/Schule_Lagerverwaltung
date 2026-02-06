import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import App from "./App";
import Inventory from "./pages/Inventory";




// nur für Entwicklung
const PREVIEW_INVENTORY = true;

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    {PREVIEW_INVENTORY ? (
      <Inventory />
    ) : (
      <BrowserRouter>
        <App />
      </BrowserRouter>
    )}
  </React.StrictMode>
);
