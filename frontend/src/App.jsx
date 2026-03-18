import { useEffect, useState } from "react";
import { Routes, Route, Navigate, useNavigate } from "react-router-dom";

import { AppBar, Toolbar, Button, Box } from "@mui/material";

import Inventory from "./pages/Inventory";
import Login from "./pages/Login";
import Users from "./pages/User";

function App() {

  const [loggedIn, setLoggedIn] = useState(false);
  const [username, setUsername] = useState('');
  const [roles, setRoles] = useState([]);

  const isAdmin = roles.some(role => role === 'admin');
  const isInventoryAdmin = roles.some(role => role === 'inventarAdmin');


  console.log("Is Admin: ", isAdmin);
  console.log("Is InventoryAdmin: ", isInventoryAdmin);
  const navigate = useNavigate();

  useEffect(() => {
    fetch('http://127.0.0.1:8080/api/isAuth', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json())
      .then(data => {
        console.log("data ", data);
        setUsername(data.username);
        setRoles(data.roles);
        setLoggedIn(true);
      })
      .catch(error => console.error('Fehler:', error));
  }, []);

  const handleLogout = () => {
    fetch('http://127.0.0.1:8080/api/logout', {
      method: "GET",
      credentials: "include",
    }).then(() => setLoggedIn(false));
  };

  console.log("roles: ", roles);

  return (
    <>
      {loggedIn && (
        <AppBar position="static">
          <Toolbar>

            <Button
              color="inherit"
              onClick={() => navigate("/inventory")}
            >
              Inventory
            </Button>

            {isAdmin && (
              <Button
                color="inherit"
                onClick={() => navigate("/user")}
              >
                Users
              </Button>
            )}

            <Box sx={{ flexGrow: 1 }} />

            <Button
              color="inherit"
              onClick={handleLogout}
            >
              Logout
            </Button>

          </Toolbar>
        </AppBar>
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
            loggedIn ? <Inventory isAdmin={isAdmin} isInventoryAdmin={isInventoryAdmin} /> : <Navigate to="/login" />
          }
        />
        {isAdmin && (
          <Route
            path="/user"
            element={
              loggedIn ? <Users /> : <Navigate to="/login" />
            }
          />
        )}
      </Routes>
    </>
  );
}

export default App;