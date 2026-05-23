//child component yang menerima data aktivitas dan fungsi hapus melalui props
function ActivityList({ activities, onDelete }) {
  return (
    <div className="activity-list">
      {/* setiap aktivitas ditampilkan menggunakan map */}
      {activities.map((activity, index) => (
        <div
          key={index}
          className="activity-item"
        >
          <span>{activity}</span>

          <button
            className="delete-btn"
            //saat tombol di klik, fungsi hapus dari parent dipanggil
            onClick={() => onDelete(index)}
          >
            Hapus
          </button>
        </div>
      ))}
    </div>
  );
}

export default ActivityList;
