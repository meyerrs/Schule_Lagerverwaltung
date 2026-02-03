import { useState } from "react";
import { Routes, Route, Navigate } from "react-router-dom";
import Inventory from "./pages/Inventory";
import Login from "./pages/Login";



function App() {
  const [loggedIn, setLoggedIn] = useState(false);

  return (
    <>
      {loggedIn && (
        <header>
          <button onClick={() => setLoggedIn(false)}>Logout</button>
        </header>
      )}

      <Routes>
        <Route
          path="/login"
          element={
            loggedIn ? (
              <Navigate to="/inventory" />
            ) : (
              <Login onLogin={() => setLoggedIn(true)} />
            )
          }
        />

        <Route
          path="/inventory"
          element={
            loggedIn ? <Inventory /> : <Navigate to="/login" />
          }
        />

        {/* Fallback */}
        <Route path="*" element={<Navigate to="/login" />} />
      </Routes>
    </>
  );
}

export default App;
