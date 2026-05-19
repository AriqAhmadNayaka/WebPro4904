// ActivityList.jsx
// Child component untuk menampilkan seluruh daftar aktivitas
// Menerima props: daftar aktivitas (array) dan fungsi hapus dari parent (App)
// Menerapkan conditional rendering:
//   - Jika daftar kosong → tampilkan pesan "Belum ada aktivitas"
//   - Jika daftar tidak kosong → tampilkan list menggunakan map()

import ActivityItem from "./ActivityItem";

function ActivityList({ daftarAktivitas, onHapus }) {
  // ===== CONDITIONAL RENDERING =====
  // Jika array kosong, tampilkan pesan
  if (daftarAktivitas.length === 0) {
    return (
      <div className="empty-state">
        <span className="empty-icon">🔍</span>
        <p className="empty-message">Belum ada aktivitas</p>
        <p className="empty-hint">Tambahkan aktivitas pertamamu di atas!📝</p>
      </div>
    );
  }

  // Jika array tidak kosong, tampilkan daftar menggunakan rendering list (map)
  return (
    <ul className="activity-list">
      {daftarAktivitas.map((item, index) => (
        // Setiap item menggunakan key unik (id) untuk optimasi React
        <ActivityItem
          key={item.id}
          nomor={index + 1}
          aktivitas={item.nama}
          onHapus={() => onHapus(item.id)}
        />
      ))}
    </ul>
  );
}

export default ActivityList;
