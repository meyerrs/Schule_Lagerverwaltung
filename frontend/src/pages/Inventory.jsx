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

function Inventory(props) {
  const {isAdmin, isInventoryAdmin} = props;
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [users, setUsers] = useState([]);
  const [status, setStatus] = useState([]);
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
    fetch('http://127.0.0.1:8080/api/user', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          setUsers(data);
      })
    fetch('http://127.0.0.1:8080/api/status', {
      method: "GET",
      credentials: "include",
    })
      .then(response => response.json()) // Extrahiert den Body
      .then(data => {
          setStatus(data);
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

 
  const handleSaveClick = (params) => {
    setRowModesModel(prev => ({
      ...prev,
      [params.id]: { mode: GridRowModes.View }
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

    fetch('http://127.0.0.1:8080/api/inventory', {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify({ "id": id })
    })

    setItems(prev => prev.filter(row => row.id !== id));
  };


  const processRowUpdate = (newRow) => {
    if (!newRow.isNew) {
      fetch('http://127.0.0.1:8080/api/inventory', {
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

    fetch('http://127.0.0.1:8080/api/inventory', {
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

    { field: "name", headerName: "Name", flex: 1, editable: isAdmin || isInventoryAdmin },

    { field: "abteilung", headerName: "Abteilung", flex: 1, editable: isAdmin || isInventoryAdmin },

    { field: "gruppe", headerName: "Gruppe", flex: 1, editable: isAdmin || isInventoryAdmin },

    { field: "fach", headerName: "Fach", flex: 1, editable: isAdmin || isInventoryAdmin },

    { field: "ort", headerName: "Ort", flex: 1, editable: isAdmin || isInventoryAdmin },

    {
      field: "verantwortlicher",
      headerName: "Verantwortlicher",
      flex: 1,
      editable: isAdmin || isInventoryAdmin,
      type: "singleSelect",
      valueOptions: users.map(u => ({
        value: u.id,
        label: `${u.firstname} ${u.lastname}`
      })),
      valueFormatter: (params) => {
              // 1. Initialisierung: Wir schauen erst mal, was wir überhaupt haben
        let val = params?.value !== undefined ? params.value : params;

        // 2. Sicherheits-Check: Wenn gar nichts da ist
        if (val === null || val === undefined) return "";

        // 3. Fall: Es ist das fertige User-Objekt (z.B. vom PHP-Join)
        if (typeof val === 'object' && val.firstname) {
            return `${val.firstname} ${val.lastname}`;
        }

        // 4. Fall: Es ist nur eine ID (z.B. nach dem Editieren oder direkt übergeben)
        // Wir suchen in der users-Liste (die du oben per useState hast)
        const foundUser = users.find(u => u.id == val);

        if (foundUser) {
            return `${foundUser.firstname} ${foundUser.lastname}`;
        }

        // Fallback: Wenn wir gar nichts finden, gib den Rohwert zurück (oder leer)
        return val;
      }
    },

    {
      field: "status",
      headerName: "Status",
      flex: 1,
      editable: isAdmin || isInventoryAdmin,
      type: "singleSelect",
      valueOptions: status.map(s => ({
        value: s.id,
        label: s.name,
      })),
      valueFormatter: (params) => {
              // 1. Initialisierung: Wir schauen erst mal, was wir überhaupt haben
        let val = params?.value !== undefined ? params.value : params;

        // 2. Sicherheits-Check: Wenn gar nichts da ist
        if (val === null || val === undefined) return "";

        // 3. Fall: Es ist das fertige User-Objekt (z.B. vom PHP-Join)
        if (typeof val === 'object' && val.name) {
            return val.name;
        }

        // 4. Fall: Es ist nur eine ID (z.B. nach dem Editieren oder direkt übergeben)
        // Wir suchen in der users-Liste (die du oben per useState hast)
        const foundStatus = status.find(s => s.id == val);

        if (foundStatus) {
            return foundStatus.name;
        }

        // Fallback: Wenn wir gar nichts finden, gib den Rohwert zurück (oder leer)
        return val;
      }
    },

    (isAdmin || isInventoryAdmin) && {
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
              onClick={() => handleSaveClick(params)}
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
      
      {isAdmin || isInventoryAdmin && (
        <Box sx={{ mt: 2 }}>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={handleAddClick}
          >
            Hinzufügen
          </Button>
        </Box>
      )}

    </div>
  );
}
//fuß

export default Inventory;