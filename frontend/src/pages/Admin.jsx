import React, { useState } from "react";
import {
  Box,
  Button,
  Stack,
  Typography
} from "@mui/material";
import { DataGrid } from "@mui/x-data-grid";
import AddIcon from "@mui/icons-material/Add";
import EditIcon from "@mui/icons-material/Edit";
import DeleteIcon from "@mui/icons-material/Delete";

const mockData = [
  { id: 1, name: "Informatik" },
  { id: 2, name: "Mathematik" },
];

export default function AdminTable({ title }) {
  const [rows, setRows] = useState(mockData);

  const handleAdd = () => {
    console.log("Neu anlegen:", title);
  };

  const handleEdit = (row) => {
    console.log("Bearbeiten:", row);
  };

  const handleDelete = (id) => {
    if (!window.confirm("Wirklich löschen?")) return;
    setRows(prev => prev.filter(r => r.id !== id));
  };

  const columns = [
    {
      field: "name",
      headerName: "Bezeichnung",
      flex: 1
    },
    {
  field: "actions",
  headerName: "Aktionen",
  minWidth: 260, 
  flex: 1,
  sortable: false,
  renderCell: (params) => (
    <Stack
      direction="row"
      spacing={1}
      sx={{ width: "100%" }}
    >
      <Button
        fullWidth
        size="small"
        variant="contained"
        startIcon={<EditIcon />}
        onClick={() => handleEdit(params.row)}
      >
        Bearbeiten
      </Button>

      <Button
        fullWidth
        size="small"
        variant="outlined"
        color="error"
        startIcon={<DeleteIcon />}
        onClick={() => handleDelete(params.row.id)}
      >
        Löschen
      </Button>
    </Stack>
  )
}

  ];

  return (
    <Box>
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        sx={{ mb: 2 }}
      >
        <Typography variant="h6">{title}</Typography>

        <Button
          variant="contained"
          startIcon={<AddIcon />}
          onClick={handleAdd}
        >
          Neu anlegen
        </Button>
      </Stack>

      <div style={{ height: 400, width: "100%" }}>
        <DataGrid
          rows={rows}
          columns={columns}
          pageSize={5}
          rowsPerPageOptions={[5, 10]}
          disableRowSelectionOnClick
          sx={{
            backgroundColor: "#fff",
            borderRadius: 2,
            boxShadow: 1
          }}
        />
      </div>
    </Box>
  );
}
