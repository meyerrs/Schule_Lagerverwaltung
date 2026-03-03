import React, { useEffect, useState } from "react";
import {
  DataGrid,
  GridActionsCellItem,
  GridRowModes
} from "@mui/x-data-grid";

import {
  Typography,
  Button,
  Box
} from "@mui/material";

import AddIcon from "@mui/icons-material/Add";
import EditIcon from "@mui/icons-material/Edit";
import DeleteIcon from "@mui/icons-material/Delete";
import SaveIcon from "@mui/icons-material/Save";
import CloseIcon from "@mui/icons-material/Close";

function Inventory() {

  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [users, setUsers] = useState([]);
  const [rowModesModel, setRowModesModel] = useState({});


  useEffect(() => {
    fetch('http://127.0.0.1:8080/api/inventory', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          setItems(data);
      })
    fetch('http://127.0.0.1:8080/api/inventory', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          console.log(data);
          setUsers(data);
      })
    // setItems([
    //   {
    //     inventarID: 1,
    //     name: "Laptop Dell XPS",
    //     abteilung: "IT",
    //     gruppe: "Hardware",
    //     fach: "A1",
    //     ort: "Büro 101",
    //     verantwortlicher: "max"
    //   }
    // ]);

    setLoading(false);
  }, []);

 
  const handleAddClick = () => {
    const id = Date.now();

    setItems(prev => [
      ...prev,
      {
        id: id,
        name: "",
        abteilung: "",
        gruppe: "",
        fach: "",
        ort: "",
        verantwortlicher: "",
        isNew: true
      }
    ]);

    setRowModesModel(prev => ({
      ...prev,
      [id]: { mode: GridRowModes.Edit }
    }));
  };


  const handleEditClick = (id) => {
    setRowModesModel(prev => ({
      ...prev,
      [id]: { mode: GridRowModes.Edit }
    }));
  };

 
  const handleSaveClick = (id) => {
    setRowModesModel(prev => ({
      ...prev,
      [id]: { mode: GridRowModes.View }
    }));
  };

  // ❌ Abbrechen
  const handleCancelClick = (id) => {

    const row = items.find(r => r.id === id);

    if (row?.isNew) {
      setItems(prev => prev.filter(r => r.id !== id));
    }

    setRowModesModel(prev => ({
      ...prev,
      [id]: {
        mode: GridRowModes.View,
        ignoreModifications: true
      }
    }));
  };


  const handleDeleteClick = (id) => {
    if (!window.confirm("Wirklich löschen?")) return;

    setItems(prev => prev.filter(row => row.id !== id));
  };


  const processRowUpdate = (newRow) => {

    const updatedRow = {
      ...newRow,
      isNew: false
    };

    setItems(prev =>
      prev.map(row =>
        row.id === newRow.id
          ? updatedRow
          : row
      )
    );

    return updatedRow;
  };

 
  const columns = [

    { field: "inventarID", headerName: "ID", width: 90 },

    { field: "name", headerName: "Name", flex: 1, editable: true },

    { field: "abteilung", headerName: "Abteilung", flex: 1, editable: true },

    { field: "gruppe", headerName: "Gruppe", flex: 1, editable: true },

    { field: "fach", headerName: "Fach", flex: 1, editable: true },

    { field: "ort", headerName: "Ort", flex: 1, editable: true },

    {
      field: "verantwortlicher",
      headerName: "Verantwortlicher",
      flex: 1,
      editable: true,
      type: "singleSelect",
      valueOptions: users,
      valueFormatter: (params) => {
        console.log('params: ', params);
        const user = users.find(u => u.id === params.verantwortlicher.id);
        return user ? (user.firstname . user.lastname) : "";
      }
    },

    {
      field: "actions",
      type: "actions",
      headerName: "Aktionen",
      width: 130,

      getActions: (params) => {

        const isInEditMode =
          rowModesModel[params.id]?.mode === GridRowModes.Edit;

        if (isInEditMode) {
          return [

            <GridActionsCellItem
              icon={<SaveIcon />}
              label="Speichern"
              onClick={() => handleSaveClick(params.id)}
            />,

            <GridActionsCellItem
              icon={<CloseIcon />}
              label="Abbrechen"
              onClick={() => handleCancelClick(params.id)}
            />

          ];
        }

        return [

          <GridActionsCellItem
            icon={<EditIcon />}
            label="Bearbeiten"
            onClick={() => handleEditClick(params.id)}
          />,

          <GridActionsCellItem
            icon={<DeleteIcon />}
            label="Löschen"
            onClick={() => handleDeleteClick(params.id)}
          />

        ];
      }
    }
  ];

  return (
    <div style={{ padding: 24 }}>

      <Typography variant="h4" sx={{ mb: 2 }}>
        Inventar
      </Typography>

      <div style={{ height: 500, width: "100%" }}>

        <DataGrid
          rows={items}
          columns={columns}
          loading={loading}
          getRowId={(row) => row.id}
          editMode="row"
          rowModesModel={rowModesModel}
          onRowModesModelChange={setRowModesModel}

          processRowUpdate={processRowUpdate}

          onRowEditStop={(params, event) => {
            event.defaultMuiPrevented = true;
          }}

          disableRowSelectionOnClick

          experimentalFeatures={{ newEditingApi: true }}

          sx={{
            backgroundColor: "#fff",
            borderRadius: 2,
            boxShadow: 2
          }}
        />
      </div>

      <Box sx={{ mt: 2 }}>
        <Button
          variant="contained"
          startIcon={<AddIcon />}
          onClick={handleAddClick}
        >
          Hinzufügen
        </Button>
      </Box>

    </div>
  );
}
//fuß

export default Inventory;