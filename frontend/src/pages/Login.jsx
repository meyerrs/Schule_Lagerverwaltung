import React, { useState } from "react";
import {
  Avatar,
  Button,
  TextField,
  Box,
  Typography,
  Container,
  Paper
} from "@mui/material";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";

function Login({ onLogin }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);

    // Beispiel: später an PHP-Endpunkt schicken
    fetch("http://127.0.0.1:8080/api/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify({ username, password }),
    })
      .then(response => {
        console.log(response.status);
        if (response.status === 200) {
            onLogin();
            // z.B. Weiterleitung zum Login
        } else {
          setErrorMessage("Ihre Anmeldedaten sind falsch!");
        }
        setLoading(false);
      })
      .catch(err => {
        setLoading(false);
        console.error("Login Fehler:", err);
      });
  };

  return (
    <Container component="main" maxWidth="xs">
      <Paper elevation={6} sx={{ mt: 8, p: 4 }}>
        <Box
          sx={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
          }}
        >
          <Avatar sx={{ m: 1, bgcolor: "primary.main" }}>
            <LockOutlinedIcon />
          </Avatar>

          <Typography component="h1" variant="h5">
            Login
          </Typography>

          <Box component="form" onSubmit={handleSubmit} sx={{ mt: 2 }}>
            <TextField
              margin="normal"
              required
              fullWidth
              label="Benutzername"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              autoFocus
            />

            <TextField
              margin="normal"
              required
              fullWidth
              label="Passwort"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />

            <Button
              type="submit"
              fullWidth
              variant="contained"
              sx={{ mt: 3 }}
              disabled={loading}
            >
              {loading ? "Anmelden..." : "Login"}
            </Button>
            <Typography color="error">{errorMessage}</Typography>
          </Box>
        </Box>
      </Paper>
    </Container>
  );
}

export default Login;
