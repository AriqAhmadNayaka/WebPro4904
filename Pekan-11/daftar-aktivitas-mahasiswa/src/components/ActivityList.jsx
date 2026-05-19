import ActivityItem from './ActivityItem.jsx';

// ActivityList menerima data dari App dan menampilkannya sebagai daftar.
export default function ActivityList({ activities, onDeleteActivity }) {
  return (
    <ul className="activity-list">
      {/* map() digunakan untuk mengubah setiap data aktivitas menjadi component ActivityItem. */}
      {activities.map((activity, index) => (
        <ActivityItem
          // key membantu React mengenali setiap item saat daftar berubah.
          key={activity.id}
          // activity berisi data satu aktivitas yang akan ditampilkan.
          activity={activity}
          // index digunakan untuk menampilkan nomor urut aktivitas.
          index={index}
          // Fungsi hapus diteruskan lagi ke ActivityItem.
          onDeleteActivity={onDeleteActivity}
        />
      ))}
    </ul>
  );
}
