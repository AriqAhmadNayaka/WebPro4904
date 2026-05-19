import { useState } from 'react'
import './App.css'
import ActivityItem from './components/ActivityItem'
import ActivityList from './components/ActivityList'
import supportAmount from './components/SupportAmount'

const initialActivities = [
  'Kuliah Basis Data',
  'Pinjam Buku ke Perpustakaan',
  'Hadiri Sidang bersama Prof Suo',
  'Makan Siang sama Mark Zuckerberg',
]

const supportAmounts = ['10K', '25K', '50K', '100K']

function App() {
  const [activities, setActivities] = useState(
    initialActivities.map((name, index) => ({
      id: index + 1,
      name,
      amount: supportAmounts[index % supportAmounts.length],
    })),
  )
  const [activityName, setActivityName] = useState('')
  const [selectedAmount, setSelectedAmount] = useState('25K')

  const handleSubmit = (event) => {
    event.preventDefault()

    const trimmedName = activityName.trim()

    if (!trimmedName) {
      return
    }

    const newActivity = {
      id: Date.now(),
      name: trimmedName,
      // Menyimpan nominal yang sedang dipilih saat tombol Tambah Aktivitas diklik.
      amount: selectedAmount,
    }

    setActivities((currentActivities) => [...currentActivities, newActivity])
    setActivityName('')
  }

  const handleDelete = (activityId) => {
    setActivities((currentActivities) =>
      currentActivities.filter((activity) => activity.id !== activityId),
    )
  }

  return (
    <main className="saweria-page">
      <nav className="topbar" aria-label="Navigasi utama">
        <a className="brand" href="/">
          <span className="brand-mark">D</span>
          Dryhussria
        </a>
      </nav>

      <section className="hero-section">
        <div className="creator-card">
          <div className="mascot" aria-hidden="true">
            <span></span>
          </div>
          <p className="eyebrow">Tugas Praktikum React</p>
          <h1>Daftar Aktivitas Mahasiswa</h1>
          <p>
            Halaman ini dibuat dengan nuansa Saweria untuk mencatat aktivitas
            mahasiswa dalam bentuk pesan dukungan.
          </p>
          <div className="creator-meta">
            <span>{activities.length} aktivitas</span>
            <span>React + Vite</span>
          </div>
        </div>

        <section className="support-card" aria-labelledby="form-title">
          <div className="panel-heading">
            <div>
              <p className="section-kicker">Beri Aktivitas</p>
              <h2 id="form-title">Kirim pesan aktivitas</h2>
            </div>
            <span className="status-pill">Online</span>
          </div>

          <div className="amount-grid" aria-label="Pilihan nominal">
            {supportAmounts.map((amount) => (
              <button
                className={selectedAmount === amount ? 'active' : ''}
                key={amount}
                type="button"
                onClick={() => setSelectedAmount(amount)}
              >
                Rp{amount}
              </button>
            ))}
          </div>

          <form className="activity-form" onSubmit={handleSubmit}>
            <label htmlFor="activityName">Nama aktivitas</label>
            <input
              id="activityName"
              type="text"
              value={activityName}
              onChange={(event) => setActivityName(event.target.value)}
              placeholder="Contoh: Diskusi kelompok"
            />
            <button type="submit">Tambah Aktivitas</button>
          </form>
        </section>
      </section>

      <section className="activity-board" id="aktivitas">
        <div className="board-heading">
          <div>
            <p className="section-kicker">Riwayat</p>
            <h2>Pesan Aktivitas</h2>
          </div>
          <span>Total: {activities.length}</span>
        </div>

        <ActivityList activities={activities} onDelete={handleDelete} />
      </section>
    </main>
  )
}

export default App
