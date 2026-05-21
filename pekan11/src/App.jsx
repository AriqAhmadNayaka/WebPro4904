import { useState } from 'react'
import './App.css'

// Komponen ini dipakai untuk menampilkan satu data aktivitas.
// Di dalamnya ada ikon, nama aktivitas, catatan, dan tombol hapus.
function ActivityItem({ activity, onDelete }) {
  return (
    <li className="activity-item">
      <div className="activity-icon">{activity.icon}</div>
      <div className="activity-content">
        <h3>{activity.name}</h3>
        <p>{activity.note}</p>
      </div>
      <div className="activity-actions">
        <button
          type="button"
          className="delete-button"
          onClick={() => onDelete(activity.id)}
        >
          Hapus
        </button>
      </div>
    </li>
  )
}

// ini bertugas menampilkan semua aktivitas dalam bentuk daftar.
// Data aktivitas diulang memakai map, lalu setiap data dikirim ke ActivityItem.
function ActivityList({ activities, onDelete }) {
  return (
    <ul className="activity-list">
      {activities.map((activity) => (
        <ActivityItem
          key={activity.id}
          activity={activity}
          onDelete={onDelete}
        />
      ))}
    </ul>
  )
}


// Di sini data aktivitas disimpan, dihapus, lalu ditampilkan ke layar.
function App() {
  // useState ini dipakai supaya daftar aktivitas bisa berubah saat data ditambah atau dihapus.
  // Data awalnya masih ditulis langsung di dalam kode sebagai contoh aktivitas.
  const [activities, setActivities] = useState([
    {
      id: 1,
      name: 'Mengikuti kuliah React JS',
      note: '08.30 - 10.00 | Ruang Lab 3',
      icon: 'JS',
    },
    {
      id: 2,
      name: 'Mengerjakan tugas pemrograman',
      note: 'Tenggat: Besok, 23:59',
      icon: '<>',
    },
    {
      id: 3,
      name: 'Diskusi kelompok',
      note: 'Kantin Gedung C',
      icon: 'Tim',
    },
  ])

  // Dua state ini dipakai untuk menyimpan isi form sementara.
  // activityName untuk nama kegiatan, sedangkan activityNote untuk catatan, waktu, atau lokasi.
  const [activityName, setActivityName] = useState('')
  const [activityNote, setActivityNote] = useState('')

  // Fungsi ini dipakai untuk mengosongkan kembali form setelah data berhasil disimpan.
  // Jadi input terlihat bersih lagi dan siap diisi dengan aktivitas berikutnya.
  const resetForm = () => {
    setActivityName('')
    setActivityNote('')
  }

  // Fungsi ini berjalan saat tombol Simpan ditekan.
  // Data dari input dibuat menjadi aktivitas baru, lalu dimasukkan ke daftar paling atas.
  const handleSaveActivity = (event) => {
    event.preventDefault()

    if (activityName.trim() === '') {
      return
    }

    const newActivity = {
      id: Date.now(),
      name: activityName,
      note: activityNote || 'Tidak ada catatan tambahan',
      icon: 'New',
    }

    setActivities([newActivity, ...activities])
    resetForm()
  }

  // Fungsi ini berjalan saat pengguna menekan tombol Hapus.
  // Cara kerjanya adalah menyaring data, lalu menyimpan lagi aktivitas yang id-nya tidak sama.
  const handleDeleteActivity = (id) => {
    const filteredActivities = activities.filter((activity) => activity.id !== id)
    setActivities(filteredActivities)
  }

  // Bagian return berisi tampilan yang akan muncul di browser.
  // Header ditampilkan di atas, lalu daftar aktivitas ditampilkan di bawahnya.
  return (
    <main className="page">
      <section className="hero-card">
        <div>
          <h1>Hallo, Sarah Sakirah 707012500049</h1>
          <p>Pantau progres akademikmu dan selesaikan aktivitas harianmu dengan teratur.</p>
        </div>
      </section>

      <section className="form-card">
        <h2>Tambah Aktivitas Baru</h2>

        {/* Form ini dipakai untuk menambah aktivitas baru beserta catatannya. */}
        {/* Setelah disimpan, datanya akan langsung muncul di bagian daftar aktivitas. */}
        <form className="activity-form" onSubmit={handleSaveActivity}>
          <input
            type="text"
            placeholder="Apa yang ingin kamu kerjakan hari ini?"
            value={activityName}
            onChange={(event) => setActivityName(event.target.value)}
          />
          <input
            type="text"
            placeholder="Catatan, waktu, atau lokasi"
            value={activityNote}
            onChange={(event) => setActivityNote(event.target.value)}
          />
          <button type="submit">Simpan</button>
        </form>
      </section>

      <section className="list-section">
        <div className="section-heading">
          <h2>Daftar Aktivitas</h2>
          <span>{activities.length} aktivitas</span>
        </div>

        {/* Kalau semua aktivitas sudah dihapus, pesan kosong akan muncul. */}
        {/* Kalau masih ada data, komponen ActivityList akan menampilkan daftarnya. */}
        {activities.length === 0 ? (
          <p className="empty-message">Belum ada aktivitas</p>
        ) : (
          <ActivityList
            activities={activities}
            onDelete={handleDeleteActivity}
          />
        )}
      </section>
    </main>
  )
}

// App diekspor supaya bisa dipanggil dan ditampilkan lewat file main.jsx.
export default App
