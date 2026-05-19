import { useState } from "react";
import ActivityItem from "./ActivityItem";

function App() {
  const [activities, setActivities] = useState([]);
  const [input, setInput] = useState("");

  // Tambah aktivitas
  const handleAdd = () => {
    if (input.trim() === "") return;
    setActivities([
      ...activities,
      { id: Date.now(), text: input }
    ]);
    setInput("");
  };

  // Hapus aktivitas
  const handleDelete = (id) => {
    setActivities(activities.filter((item) => item.id !== id));
  };

  return (
    <div>
      <h1>Daftar Aktivitas Mahasiswa</h1>

      {/* Input tambah aktivitas */}
      <input
        type="text"
        value={input}
        onChange={(e) => setInput(e.target.value)}
        placeholder="Masukkan aktivitas..."
      />
      <button onClick={handleAdd}>Tambah</button>

      {/* Conditional rendering */}
      {activities.length === 0 ? (
        <p>Belum ada aktivitas</p>
      ) : (
        <ul>
          {activities.map((activity) => (
            <ActivityItem
              key={activity.id}
              activity={activity}
              onDelete={handleDelete}
            />
          ))}
        </ul>
      )}
    </div>
  );
}

export default App;