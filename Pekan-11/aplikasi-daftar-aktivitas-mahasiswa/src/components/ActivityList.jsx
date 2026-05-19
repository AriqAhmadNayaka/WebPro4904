import ActivityItem from "./ActivityItem";

// Komponen ini menerima daftar aktivitas, lalu mengubah setiap data menjadi komponen ActivityItem.
function ActivityList({ activities, onDeleteActivity }) {
  return (
    // Container untuk menampung seluruh item aktivitas yang akan ditampilkan.
    <div className="a ctivity-list">
      {/* map digunakan untuk menampilkan setiap aktivitas sebagai satu ActivityItem. */}
      {activities.map((activity, index) => (
        // Setiap ActivityItem menerima data aktivitas dan fungsi hapus sesuai indexnya.
        <ActivityItem
          key={`${activity}-${index}`}
          activity={activity}
          onDelete={() => onDeleteActivity(index)}
        />
      ))}
    </div>
  );
}

export default ActivityList;
