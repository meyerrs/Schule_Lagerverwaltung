import React, { useEffect, useState } from "react";
import {
  DataGrid,
  GridActionsCellItem,
  GridRowModes,
  useGridNativeEventListener
} from "@mui/x-data-grid";

import {
  Typography,
  Button,
  Box,
  Select,
  OutlinedInput,
  MenuItem,
  Checkbox,
  ListItemText
} from "@mui/material";

import AddIcon from "@mui/icons-material/Add";
import EditIcon from "@mui/icons-material/Edit";
import DeleteIcon from "@mui/icons-material/Delete";
import SaveIcon from "@mui/icons-material/Save";
import CloseIcon from "@mui/icons-material/Close";

function User() {

  const [items, setItems] = useState([]);
  const [roles, setRoles] = useState([]);
  const [loading, setLoading] = useState(true);
  const [rowModesModel, setRowModesModel] = useState({});


  useEffect(() => {
    fetch('http://127.0.0.1:8080/api/user', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          setItems(data);
      })

    fetch('http://127.0.0.1:8080/api/role', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          setRoles(data);
      })
    
   

    setLoading(false);
  }, []);

 
  const handleAddClick = () => {
    const id = Date.now();

    setItems(prev => [
      ...prev,
      {
        id: id,
        firstname : "",
        lastname : "",
        username : "",
        password : "",
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

    fetch('http://127.0.0.1:8080/api/user', {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify({ "id": id })
    })

    setItems(prev => prev.filter(row => row.id !== id));
  };


  const processRowUpdate = (newRow) => {
if (!newRow.isNew) {
      fetch('http://127.0.0.1:8080/api/user', {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify(newRow)
      })

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
    }

    fetch('http://127.0.0.1:8080/api/user', {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify(newRow)
    })

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

    { field: "firstname", headerName: "Firstname", flex: 1, editable: true },

    { field: "lastname", headerName: "Lastname", flex: 1, editable: true },

    { field: "username", headerName: "Username", flex: 1, editable: true },

    { field: "password", headerName: "Password", flex: 1, editable: true },

    {
      field: "roles",
      headerName: "Rollen",
      flex: 1,
      editable: true,

      valueFormatter: (params) => {
        if (!params || !Array.isArray(params)) return "";
        
        // Extrahiert den 'name' aus jedem Objekt und trennt sie mit Komma
        return params.map(obj => obj.name).join(", ");
      },

      renderEditCell: (params) => {
        // 1. Daten aus params extrahieren
        const { id, field, value, api } = params;

        // 2. Anzeige-Logik (Array von Objekten -> Array von Strings)
        const selectedNames = Array.isArray(value) 
          ? value.map((role) => role.name) 
          : [];

        const handleChange = (event) => {
          const newNames = event.target.value;

          // 3. Zurückwandeln in die Objekt-Struktur deiner Daten
          const updatedRoles = newNames.map(name => 
            roles.find(r => r.name === name)
          );

          // 4. Den Wert im Grid aktualisieren (Free-Version Syntax)
          api.setEditCellValue({ id, field, value: updatedRoles });
        };

        return (
          <Select
            multiple
            value={selectedNames}
            onChange={handleChange}
            fullWidth
            size="small"
            // Wichtig: Verhindert, dass das Grid die Zelle schließt, wenn man klickt
            onMouseDown={(e) => e.stopPropagation()} 
          >
            {roles.map((role) => (
              <MenuItem key={role.id} value={role.name}>
                {role.name}
              </MenuItem>
            ))}
          </Select>
        );
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
        Users
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

export default User;