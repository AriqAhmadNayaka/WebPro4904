import { useState } from "react";
import ActivityItem from "./Component/Activityitem";
import "./App.css";
function App() {
  // Menyimpan daftar aktivitas dan teks yang sedang diketik pengguna.
  const [activities, setActivities] = useState([]);
  const [input, setInput] = useState("");
  // Menambahkan aktivitas baru jika input tidak kosong.
  const handleAdd = () => {
    if (input.trim() === "") return;
    setActivities([
      ...activities,
      { id: Date.now(), text: input }
    ]);
    setInput("");
  };
  // Menghapus aktivitas berdasarkan id yang dipilih.
  const handleDelete = (id) => {
    setActivities(activities.filter((item) => item.id !== id));
  };
  return (
    <main className="app">
      <section className="activity-card">
        <div className="app-header">
          <p className="eyebrow">Aktivitas Mahasiswa</p>
          <h1>Daftar Aktivitas Mahasiswa</h1>
          <p className="subtitle">
            Catat aktivitas harian agar daftar kegiatan tetap tertata.
          </p>
        </div>
        {/* Form utama untuk mengetik dan menambahkan aktivitas. */}
        <div className="activity-form">
          <input
            type="text"
            value={input}
            onChange={(e) => setInput(e.target.value)}
            placeholder="Masukkan aktivitas..."
          />
          <button className="add-button" onClick={handleAdd}>
            Tambah
          </button>
        </div>
        {/* Menampilkan pesan kosong atau daftar aktivitas yang sudah dibuat. */}
        {activities.length === 0 ? (
          <p className="empty-state">Belum ada aktivitas</p>
        ) : (
          <ul className="activity-list">
            {activities.map((activity) => (
              <ActivityItem
                key={activity.id}
                activity={activity}
                onDelete={handleDelete}
              />
            ))}
          </ul>
        )}
      </section>
    </main>
  );
}
export default App;
