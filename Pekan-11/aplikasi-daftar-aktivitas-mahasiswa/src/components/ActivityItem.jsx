// Komponen ini bertugas menampilkan satu aktivitas beserta tombol untuk menghapusnya.
function ActivityItem({ activity, onDelete }) {
  return (
    // Wrapper untuk satu item aktivitas agar teks dan tombol hapus berada dalam satu baris/card.
    <div className="activity-item">
      {/* Menampilkan nama aktivitas yang diterima dari komponen induk melalui props. */}
      <p>{activity}</p>

      {/* Tombol ini menjalankan fungsi onDelete dari komponen induk saat aktivitas ingin dihapus. */}
      <button
        className="delete-btn"
        onClick={onDelete}
      >
        Hapus
      </button>
    </div>
  );
}

export default ActivityItem;
