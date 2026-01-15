import React, { useState } from "react";
import Inventory from "./pages/Inventory";

function App() {
  const [page, setPage] = useState("inventory");

  return (
    <div>
      <header>
        <button onClick={() => setPage("inventory")}>Inventar</button>
      </header>

      <main>
        {page === "inventory" && <Inventory />}
      </main>
    </div>
  );
}

export default App;
