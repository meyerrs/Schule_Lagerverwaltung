import { useCallback, useEffect, useState } from "react";
import { Routes, Route, Navigate, useNavigate } from "react-router-dom";
import Inventory from "./pages/Inventory";
import Login from "./pages/Login";
import { checkAuth, logout } from "./api/auth";

function App() {
  const navigate = useNavigate();
  const [loggedIn, setLoggedIn] = useState(false);
  const [username, setUsername] = useState("");
  const [roles, setRoles] = useState([]);

  const refreshAuthState = useCallback(async () => {
    try {
      const data = await checkAuth();
      setUsername(data.username);
      setRoles(data.roles);
      setLoggedIn(true);
    } catch (error) {
      setLoggedIn(false);
      setUsername("");
      setRoles([]);
    }
  }, []);

  useEffect(() => {
    refreshAuthState();
  }, [refreshAuthState]);

  const handleLogout = async () => {
    try {
      await logout();
    } catch (error) {
      console.error("Logout Fehler:", error);
    } finally {
      setLoggedIn(false);
      setUsername("");
      setRoles([]);
      navigate("/login");
    }
  };

  return (
    <>
      {loggedIn && (
        <header>
          <button onClick={handleLogout}>Logout</button>
        </header>
      )}

      <Routes>
        <Route
          path="/login"
          element={
            loggedIn ? (
              <Navigate to="/inventory" />
            ) : (
              <Login onLogin={refreshAuthState} />
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
