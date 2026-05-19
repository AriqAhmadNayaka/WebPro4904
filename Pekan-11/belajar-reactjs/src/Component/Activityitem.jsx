// Komponen untuk menampilkan satu aktivitas di dalam daftar.
function ActivityItem({ activity, onDelete }) {
  return (
    <li className="activity-item">
      <span>{activity.text}</span>
      {/* Tombol ini memanggil fungsi hapus dari App.jsx sesuai id aktivitas. */}
      <button className="delete-button" onClick={() => onDelete(activity.id)}>
        Hapus
      </button>
    </li>
  );
}

export default ActivityItem;
