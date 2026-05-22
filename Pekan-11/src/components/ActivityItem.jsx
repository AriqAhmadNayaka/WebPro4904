// Child Component yang merepresentasikan satu item aktivitas
// Component ini menerima data dari ActivityList (parent-nya) via props

// Props yang diterima:
// - activity: object { id, text } data satu aktivitas
// - onDelete: fungsi untuk menghapus item (berasal dari App.jsx)
function ActivityItem({ activity, onDelete }) {
  return (
    <li className="activity-item">
      <div className="item-left">
        {/* Bullet dekoratif */}
        <span className="item-dot" />
        <span className="item-text">{activity.text}</span>
      </div>

      {/* Tombol hapus: memanggil onDelete dengan id item ini */}
      {/* Arrow function dipakai agar onDelete tidak langsung dipanggil saat render */}
      <button
        className="btn-delete"
        onClick={() => onDelete(activity.id)}
        title="Hapus aktivitas"
      >
        ✕
      </button>
    </li>
  );
}

export default ActivityItem;