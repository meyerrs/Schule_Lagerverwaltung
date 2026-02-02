import React, { useState } from "react";
import Inventory from "./pages/Inventory";
import Login from "./pages/Login";

function App() {
  const [loggedIn, setLoggedIn] = useState(false);

  return (
    <div>
      {loggedIn && (
        <header>
          <button onClick={() => setLoggedIn(false)}>Logout</button>
        </header>
      )}

      <main>
        {!loggedIn && <Login onLogin={() => setLoggedIn(true)} />}
        {loggedIn && <Inventory />}
      </main>
    </div>
  );
}

export default App;
