import { Routes, Route, Navigate } from "react-router-dom";
import Inventory from "./pages/Inventory";
import Login from "./pages/Login";

function App() {
  const isLoggedIn = false; // später State / Context

  return (
    <Routes>
      {/* Startseite */}
      <Route path="/" element={<Navigate to="/login" replace />} />

      <Route path="/login" element={<Login />} />

      <Route
        path="/inventory"
        element={
          isLoggedIn ? <Inventory /> : <Navigate to="/login" replace />
        }
      />

      <Route path="*" element={<h1>404 – Seite nicht gefunden</h1>} />
    </Routes>
  );
}

export default App;
