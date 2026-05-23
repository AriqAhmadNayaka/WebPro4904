// Child component — menerima data aktivitas lewat props
function ActivityItem({ activity, onDelete }) { // Destructuring props untuk akses lebih mudah
  return (
    <li className="activity-item">
      <div className="activity-dot" aria-hidden="true"></div>
      <span className="activity-name">{activity.name}</span>
      <button
        className="btn-delete" // Menggunakan className untuk styling tombol
        onClick={() => onDelete(activity.id)} // Memanggil fungsi onDelete dengan id aktivitas saat tombol diklik
        aria-label={`Hapus ${activity.name}`} // Menambahkan label aksesibilitas untuk tombol hapus
      >
        Hapus
      </button>
    </li>
  );
}

export default ActivityItem; // Mengekspor komponen agar bisa digunakan di App.jsx
