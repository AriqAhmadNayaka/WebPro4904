import { useState } from 'preact/hooks'
import { ActivityList } from './ActivityList.jsx'
import './app.css'

export function App() {
  // Menyimpan daftar aktivitas yang akan ditampilkan.
  const [activities, setActivities] = useState([
    { id: 1, name: 'Mengikuti kelas pemrograman web' },
    { id: 2, name: 'Mengerjakan tugas kelompok' },
  ])
  // Menyimpan isi input sebelum aktivitas ditambahkan.
  const [activityName, setActivityName] = useState('')

  function handleSubmit(event) {
    // Mencegah form me-reload halaman saat tombol Tambah ditekan.
    event.preventDefault()

    const trimmedName = activityName.trim()
    if (!trimmedName) {
      return
    }

    const newActivity = {
      id: Date.now(),
      name: trimmedName,
    }

    // Membuat array baru agar Preact tahu state berubah.
    setActivities([...activities, newActivity])
    setActivityName('')
  }

  function handleDeleteActivity(id) {
    // Menyisakan aktivitas yang id-nya berbeda dari item yang dihapus.
    setActivities(activities.filter((activity) => activity.id !== id))
  }

  return (
    <main className="app-shell">
      <section className="activity-panel" aria-labelledby="page-title">
        <div className="panel-header">
          <p className="eyebrow">Daftar Aktivitas Mahasiswa</p>
          <h1 id="page-title">Kelola Aktivitas Harian</h1>
        </div>

        <form className="activity-form" onSubmit={handleSubmit}>
          <label htmlFor="activity-name">Nama aktivitas</label>
          <div className="form-row">
            <input
              id="activity-name"
              type="text"
              value={activityName}
              onInput={(event) => setActivityName(event.currentTarget.value)}
              placeholder="Contoh: Rapat organisasi"
            />
            <button type="submit">Tambah</button>
          </div>
        </form>

        {/* Dikirim ke komponen anak agar tombol Hapus bisa mengubah state di App. */}
        <ActivityList
          activities={activities}
          onDeleteActivity={handleDeleteActivity}
        />
      </section>
    </main>
  )
}
