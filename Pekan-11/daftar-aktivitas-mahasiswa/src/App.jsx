import { useState } from 'react';
import ActivityList from './components/ActivityList.jsx';

// Data awal yang langsung tampil saat aplikasi pertama kali dibuka.
const initialActivities = [
  { id: 1, name: 'Mengikuti praktikum React' },
  { id: 2, name: 'Mengerjakan tugas pemrograman web' },
  { id: 3, name: 'Diskusi kelompok proyek akhir' }
];

// App berperan sebagai parent component yang menyimpan state utama aplikasi.
export default function App() {
  // State untuk menyimpan seluruh data aktivitas mahasiswa.
  const [activities, setActivities] = useState(initialActivities);

  // State untuk menyimpan teks yang sedang diketik pada input.
  const [activityName, setActivityName] = useState('');

  // Fungsi ini dijalankan saat form tambah aktivitas dikirim.
  function handleSubmit(event) {
    // Mencegah browser melakukan refresh halaman saat form submit.
    event.preventDefault();

    // trim() digunakan agar input berisi spasi saja tidak ikut ditambahkan.
    const trimmedName = activityName.trim();
    if (trimmedName === '') {
      return;
    }

    // Membuat object aktivitas baru dengan id unik dan nama dari input.
    const newActivity = {
      id: Date.now(),
      name: trimmedName
    };

    // Menambahkan aktivitas baru tanpa mengubah array state lama secara langsung.
    setActivities([...activities, newActivity]);

    // Mengosongkan kembali input setelah aktivitas berhasil ditambahkan.
    setActivityName('');
  }

  // Fungsi ini menghapus aktivitas berdasarkan id yang dikirim dari child component.
  function handleDeleteActivity(activityId) {
    // filter() menghasilkan array baru tanpa aktivitas yang id-nya dipilih.
    setActivities(activities.filter((activity) => activity.id !== activityId));
  }

  // Nilai boolean untuk menentukan apakah daftar aktivitas perlu ditampilkan.
  const hasActivities = activities.length > 0;

  return (
    <main className="app-shell">
      <div className="workspace">
        {/* Bagian samping hanya untuk identitas visual praktikum. */}
        <aside className="course-strip" aria-label="Info praktikum">
          <span>Pekan 11</span>
          <strong>React</strong>
          <small>state + props</small>
        </aside>

        <section className="activity-panel" aria-labelledby="app-title">
          {/* Elemen dekoratif agar panel terlihat seperti kertas binder. */}
          <div className="paper-holes" aria-hidden="true">
            <span />
            <span />
            <span />
          </div>

          {/* Header menampilkan judul aplikasi dan jumlah aktivitas saat ini. */}
          <div className="header">
            <p className="eyebrow">Catatan Praktikum</p>
            <h1 id="app-title">Daftar Aktivitas Mahasiswa</h1>
            <p className="summary">
              <span>{activities.length}</span> aktivitas tercatat
            </p>
          </div>

          {/* Form untuk menerima input aktivitas baru dari pengguna. */}
          <form className="activity-form" onSubmit={handleSubmit}>
            <label htmlFor="activityName">Nama aktivitas</label>
            <div className="input-row">
              <input
                id="activityName"
                type="text"
                // value membuat input dikontrol oleh state React.
                value={activityName}
                // Setiap perubahan input langsung disimpan ke state activityName.
                onChange={(event) => setActivityName(event.target.value)}
                placeholder="Contoh: Membaca materi React"
              />
              <button type="submit">
                <span aria-hidden="true">+</span>
                Tambah
              </button>
            </div>
          </form>

          {/* Conditional rendering: tampilkan list jika ada data, pesan kosong jika tidak ada. */}
          {hasActivities ? (
            <ActivityList
              // Data aktivitas dikirim ke child component melalui props.
              activities={activities}
              // Fungsi hapus juga dikirim sebagai props agar item bisa memicu perubahan state parent.
              onDeleteActivity={handleDeleteActivity}
            />
          ) : (
            <p className="empty-message">Belum ada aktivitas</p>
          )}
        </section>
      </div>
    </main>
  );
}
