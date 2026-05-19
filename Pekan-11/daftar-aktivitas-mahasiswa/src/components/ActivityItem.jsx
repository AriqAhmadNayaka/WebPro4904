// ActivityItem bertugas menampilkan satu aktivitas beserta tombol hapusnya.
export default function ActivityItem({ activity, index, onDeleteActivity }) {
  return (
    <li className="activity-item">
      {/* Menampilkan nomor urut dengan format dua digit, misalnya 01, 02, 03. */}
      <span className="activity-number">{String(index + 1).padStart(2, '0')}</span>

      {/* Menampilkan nama aktivitas dari props activity. */}
      <span className="activity-name">{activity.name}</span>

      <button
        type="button"
        className="delete-button"
        // aria-label membuat tombol lebih jelas untuk pembaca layar.
        aria-label={`Hapus aktivitas ${activity.name}`}
        // Saat diklik, component mengirim id aktivitas ke parent untuk dihapus dari state.
        onClick={() => onDeleteActivity(activity.id)}
      >
        <span aria-hidden="true">x</span>
        Hapus
      </button>
    </li>
  );
}
