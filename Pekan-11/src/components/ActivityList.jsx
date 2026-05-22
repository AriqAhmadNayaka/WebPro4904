// Child Component yang menerima data dari App (parent) 
import ActivityItem from "./ActivityItem";
// Destructuring props langsung di parameter fungsi
// 'activities' = array data dari parent (App.jsx),'onDelete' = fungsi hapus yang didefinisikan di parent
function ActivityList({ activities, onDelete }) {
  // Jika array activities kosong, tampilkan pesan "Belum ada aktivitas"
  if (activities.length === 0) {
    return (
      <div className="empty-state">
        <span className="empty-icon">🗂️</span>
        <p>Belum ada aktivitas</p>
        <span className="empty-hint">Tambahkan aktivitas di atas</span>
      </div>
    );
  }
  // Jika tidak kosong, render daftar menggunakan map()
  return (
    <ul className="activity-list">
      {/* map() mengubah setiap item array menjadi elemen JSX, 'key' wajib ada agar React bisa melacak perubahan item */}
      {activities.map((activity) => (
        <ActivityItem
          key={activity.id}
          activity={activity} // Teruskan fungsi onDelete ke child berikutnya (ActivityItem)
          onDelete={onDelete}
        />
      ))}
    </ul>
  );
}
export default ActivityList;