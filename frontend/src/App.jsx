import { useEffect, useState } from "react";
import { Routes, Route, Navigate } from "react-router-dom";
import Inventory from "./pages/Inventory";
import Login from "./pages/Login";



function App() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [username, setUsername] = useState('');
  const [roles, setRoles] = useState([]);

  useEffect(() => {
    fetch('http://127.0.0.1:8080/api/isAuth', {
    method: "GET",
    credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          setUsername(data.username);
          setRoles(data.roles);
          setLoggedIn(true);
      })
      .catch(error => console.error('Fehler:', error));
  }, []);

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
