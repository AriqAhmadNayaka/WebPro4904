// Definisi komponen ActivityList yang menerima props activity (teks aktivitas) dan onDelete (fungsi untuk menghapus aktivitas). 
// Komponen ini akan menampilkan teks aktivitas dan tombol "Hapus" yang akan memanggil fungsi onDelete saat diklik.
function ActivityList({ activity, onDelete }) {
  return (
    // Elemen list item yang menampilkan teks aktivitas dan tombol "Hapus". Ketika tombol "Hapus" diklik, 
    // fungsi onDelete akan dipanggil untuk menghapus aktivitas tersebut dari daftar.
    <li className="activity-item">
      <span className="activity-text">{activity}</span>
      <button className="btn-delete" onClick={onDelete}>
        Hapus
      </button>
    </li>
  );
}

// Mengekspor komponen ActivityList sebagai default export, sehingga dapat diimpor dan digunakan di file lain dalam aplikasi.
export default ActivityList;