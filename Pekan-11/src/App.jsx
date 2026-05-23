import { useState } from "react";
import ActivityList from "./activityList";
import "./App.css";

function App() {
  //state untuk menyimpan daftar aktivitas mahasiswa
  const [activities, setActivities] = useState([
    "Belajar React",
    "Mengerjakan Tugas",
    "Membaca Buku"
  ]);

  //state untuk menyimpan isi input sebelum ditambahkan ke daftar
  const [newActivity, setNewActivity] = useState("");

  //menambahkan aktivitas baru jika input tidak kosong
  const addActivity = () => {
    const activityName = newActivity.trim();

    if (activityName !== "") {
      setActivities([...activities, activityName]);
      setNewActivity("");
    }
  };

  //menghapus aktivitas berdasarkan posisi item di dalam array
  const deleteActivity = (index) => {
    const updatedActivities = activities.filter(
      (_, i) => i !== index
    );

    setActivities(updatedActivities);
  };

  return (
    <main className="page">
      <section className="container">
        <div className="header">
          <h1>Daftar Aktivitas Mahasiswa</h1>
          <p>Catat aktivitas harian dan hapus aktivitas yang sudah selesai.</p>
        </div>

        <div className="input-group">
          <input
            type="text"
            placeholder="Masukkan nama aktivitas"
            value={newActivity}
            onChange={(e) => setNewActivity(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === "Enter") {
                addActivity();
              }
            }}
          />

          <button type="button" onClick={addActivity}>
            Tambah
          </button>
        </div>

        {/*menampilkan pesan jika daftar kosong*/}
        {activities.length === 0 ? (
          <p className="empty-text">
            Belum ada aktivitas
          </p>
        ) : (
          <ActivityList
            activities={activities}
            onDelete={deleteActivity}
          />
        )}
      </section>
    </main>
  );
}

export default App;
