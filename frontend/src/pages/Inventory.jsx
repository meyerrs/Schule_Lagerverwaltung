import React, { useEffect, useState } from "react";
import Button from "@mui/material/Button";

function Inventory() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [sortConfig, setSortConfig] = useState({ key: "inventarID", direction: "asc" });

  // Daten vom PHP-Endpunkt laden
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

  // Sortierfunktion
  const sortedItems = React.useMemo(() => {
    let sortableItems = [...items];
    const { key, direction } = sortConfig;

    sortableItems.sort((a, b) => {
      if (a[key] < b[key]) return direction === "asc" ? -1 : 1;
      if (a[key] > b[key]) return direction === "asc" ? 1 : -1;
      return 0;
    });

    return sortableItems;
  }, [items, sortConfig]);

  const requestSort = (key) => {
    let direction = "asc";
    if (sortConfig.key === key && sortConfig.direction === "asc") {
      direction = "desc";
    }
    setSortConfig({ key, direction });
  };

  if (loading) return <p className="p-4">Lade Inventar...</p>;
  
  return (
    <>
    <div style={{ padding: 20 }}>
      <Button variant="contained" color="primary">
        MUI funktioniert 🚀
      </Button>
    </div>
      <div className="p-4">
        <h1 className="text-2xl font-bold mb-4">Inventar</h1>
        <div className="overflow-x-auto">
          <table className="min-w-full border border-gray-300 rounded-lg">
            <thead className="bg-gray-100">
              <tr>
                {["inventarID", "name", "abteilung", "gruppe", "fach", "ort"].map((col) => (
                  <th
                    key={col}
                    onClick={() => requestSort(col)}
                    className="px-4 py-2 text-left cursor-pointer select-none"
                  >
                    {col.toUpperCase()}
                    {sortConfig.key === col ? (
                      sortConfig.direction === "asc" ? " ▲" : " ▼"
                    ) : null}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {sortedItems.length === 0 ? (
                <tr>
                  <td colSpan="6" className="text-center py-4">Keine Einträge</td>
                </tr>
              ) : (
                sortedItems.map((item) => (
                  <tr key={item.inventarID} className="hover:bg-gray-50">
                    <td className="px-4 py-2">{item.inventarID}</td>
                    <td className="px-4 py-2">{item.name}</td>
                    <td className="px-4 py-2">{item.abteilung}</td>
                    <td className="px-4 py-2">{item.gruppe}</td>
                    <td className="px-4 py-2">{item.fach}</td>
                    <td className="px-4 py-2">{item.ort}</td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </>
  );
}

export default Inventory;
