import React, { useEffect, useState } from "react";
import { DataGrid } from "@mui/x-data-grid";
import { Button, Stack } from "@mui/material";
import EditIcon from "@mui/icons-material/Edit";
import DeleteIcon from "@mui/icons-material/Delete";

function Inventory() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch("/api/inventory.php")
      .then(res => res.json())
      .then(data => {
        setItems(data);
        setLoading(false);
      })
      .catch(err => {
        console.error("Fehler beim Laden:", err);
        setLoading(false);
      });
  }, []);

  const handleEdit = (row) => {
    console.log("Bearbeiten:", row);
    // z.B. Dialog öffnen
  };

  const handleDelete = (id) => {
    if (!window.confirm("Wirklich löschen?")) return;

    console.log("Löschen:", id);
    // API DELETE → danach setItems(...)
  };

  const columns = [
    { field: "inventarID", headerName: "ID", width: 90 },
    { field: "name", headerName: "Name", flex: 1 },
    { field: "abteilung", headerName: "Abteilung", flex: 1 },
    { field: "gruppe", headerName: "Gruppe", flex: 1 },
    { field: "fach", headerName: "Fach", flex: 1 },
    { field: "ort", headerName: "Ort", flex: 1 },
    {
      field: "actions",
      headerName: "Aktionen",
      width: 180,
      sortable: false,
      renderCell: (params) => (
        <Stack direction="row" spacing={1}>
          <Button
            size="small"
            variant="contained"
            color="primary"
            startIcon={<EditIcon />}
            onClick={() => handleEdit(params.row)}
          >
            Bearbeiten
          </Button>
          <Button
            size="small"
            variant="outlined"
            color="error"
            startIcon={<DeleteIcon />}
            onClick={() => handleDelete(params.row.inventarID)}
          >
            Löschen
          </Button>
        </Stack>
      )
    }
  ];

  return (
    <div style={{ padding: 24 }}>
      <h1 style={{ fontSize: 24, fontWeight: 600, marginBottom: 16 }}>
        Inventar
      </h1>

      <div style={{ height: 600, width: "100%" }}>
        <DataGrid
          rows={items}
          columns={columns}
          loading={loading}
          pageSize={10}
          rowsPerPageOptions={[10, 25, 50]}
          disableRowSelectionOnClick
          getRowId={(row) => row.inventarID}
          sx={{
            backgroundColor: "#fff",
            borderRadius: 2,
            boxShadow: 2,
          }}
        />
      </div>
    </div>
  );
}

export default Inventory;
