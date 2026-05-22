//import yang berfungsi menyambungkan file App.jsx dengan file lainnya yang dibutuhkan.
import React, { useState } from "react";
import "./App.css";
import ActivityItem from "./Component/ActivityItem";

//fungsi utama yang berfungsi untuk menampilkan daftar aktivitas mahasiswa,
//menambahkan aktivitas baru, dan menghapus aktivitas yang sudah ada. 
function App() {
  const [activities, setActivities] = useState([
    // "Belajar React",
    // "Mengerjakan Tugas",
  ]);

  const [newActivity, setNewActivity] = useState(""); // State untuk menyimpan input aktivitas baru

  // Fungsi untuk menambahkan aktivitas baru ke dalam daftar
  const addActivity = () => {
    if (newActivity.trim() === "") return;

    setActivities([...activities, newActivity]);
    setNewActivity("");
  };

  // Fungsi untuk menghapus aktivitas berdasarkan indeksnya
  const deleteActivity = (index) => {
    const updatedActivities = activities.filter(
      (_, i) => i !== index
    );

    setActivities(updatedActivities);
  };
  
  // Render komponen utama yang menampilkan daftar aktivitas, 
  // input untuk menambahkan aktivitas baru, dan tombol untuk menghapus aktivitas.
  return (
    <div className="container">
      <div className="card">
        <h1>Daftar Aktivitas Mahasiswa</h1>

        <div className="input-group">
          <input
            type="text"
            placeholder="Masukkan aktivitas"
            value={newActivity}
            onChange={(e) => setNewActivity(e.target.value)}
          />

          <button onClick={addActivity}> menambahkan aktivitas baru
            Tambah
          </button>
        </div>

        {activities.length === 0 ? (
          <p className="empty-text">
            Belum ada aktivitas
          </p>
        ) : (
          <ul className="activity-list">
            {activities.map((activity, index) => (
              <ActivityItem
                key={index}
                activity={activity}
                onDelete={() => deleteActivity(index)}
              />
            ))}
          </ul>
        )}
      </div>
    </div>
  );
}

export default App;