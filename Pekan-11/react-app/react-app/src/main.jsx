//mengimpor React dan ReactDOM untuk membuat komponen dan merendernya ke DOM

import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App";
import "./index.css";

// Render komponen App ke dalam elemen dengan id "root" di dalam DOM
ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);