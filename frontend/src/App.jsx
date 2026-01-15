import React, { useState } from "react";
import Inventory from "./pages/Inventory";
import { Box, AppBar, Container, Toolbar, Typography, Grid } from "@mui/material";

function App() {
  const [page, setPage] = useState("inventory");

  return (
    <Box sx={{ display: "flex", flexDirection: "column" }}>
      <AppBar position="static">
        <Container maxWidth="xl">
          <Toolbar disableGutters>
            <Typography variant="h6" sx={{ flexGrow: 1 }}>
              Inventar-Plattform
            </Typography>
            <Grid container spacing={2} sx={{ width: "auto" }}>
              <Grid item>
                <Typography variant="h6">Inventar</Typography>
              </Grid>
              <Grid item>
                <Typography variant="h6">Benutzer</Typography>
              </Grid>
            </Grid>
          </Toolbar>
        </Container>
      </AppBar>

      <Box
        component="main"
        sx={{
          flexGrow: 1,
          p: 3,
        }}
      >
        <Inventory />
      </Box>
    </Box>
  );
}

export default App;
