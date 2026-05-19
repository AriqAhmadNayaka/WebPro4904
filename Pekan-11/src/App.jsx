import { useState } from 'react'
import ActivityList from './components/ActivityList'
import './App.css'

// Data awal aktivitas disimpan dalam bentuk array.
const initialActivities = [
  { id: 1, name: 'Mengikuti praktikum pemrograman web' },
]

function App() {
  // State untuk menyimpan daftar aktivitas mahasiswa.
  const [activities, setActivities] = useState(initialActivities)

  // State untuk menyimpan isi input aktivitas baru.
  const [activityName, setActivityName] = useState('')

  // Fungsi untuk menambahkan aktivitas baru ke dalam state.
  const handleSubmit = (event) => {
    event.preventDefault()

    // Jika input kosong, aktivitas tidak akan ditambahkan.
    if (activityName.trim() === '') {
      return
    }

    // Membuat object aktivitas baru.
    const newActivity = {
      id: Date.now(),
      name: activityName.trim(),
    }

    // Menambahkan aktivitas baru ke array activities.
    setActivities([...activities, newActivity])

    // Mengosongkan input setelah aktivitas berhasil ditambahkan.
    setActivityName('')
  }

  // Fungsi untuk menghapus aktivitas berdasarkan id.
  const handleDelete = (activityId) => {
    const updatedActivities = activities.filter(
      (activity) => activity.id !== activityId,
    )

    // Memperbarui state dengan daftar aktivitas yang sudah difilter.
    setActivities(updatedActivities)
  }

  return (
    <main className="app">
      <section className="activity-panel">
        <div className="activity-header">
          <p className="eyebrow">Praktikum React</p>
          <h1>Daftar Aktivitas Mahasiswa</h1>
          <p className="description">
            Kelola aktivitas harian mahasiswa menggunakan component, props,
            state, rendering list, dan conditional rendering.
          </p>
        </div>

        <form className="activity-form" onSubmit={handleSubmit}>
          <label htmlFor="activityName">Nama Aktivitas</label>
          <div className="form-row">
            <input
              id="activityName"
              type="text"
              value={activityName}
              // Menyimpan setiap perubahan input ke dalam state.
              onChange={(event) => setActivityName(event.target.value)}
              placeholder="Contoh: Mengikuti seminar kampus"
            />
            <button type="submit">Tambah</button>
          </div>
        </form>

        {/* Mengirim data aktivitas dan fungsi hapus ke child component melalui props. */}
        <ActivityList activities={activities} onDelete={handleDelete} />
      </section>
    </main>
  )
}

export default App
