// ActivityItem.jsx
// Child component untuk menampilkan satu item aktivitas
// Menerima props: nomor, nama aktivitas, dan fungsi hapus dari parent (App)

function ActivityItem({ nomor, aktivitas, onHapus }) {
  return (
    <li className="activity-item">
      <div className="activity-info">
        <span className="activity-number">{nomor}</span>
        <span className="activity-name">{aktivitas}</span>
      </div>
      <button className="btn-hapus" onClick={onHapus}>
        🗑 Hapus
      </button>
    </li>
  );
}

export default ActivityItem;
