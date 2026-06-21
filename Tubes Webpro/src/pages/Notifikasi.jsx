import FeaturePageShell from '../components/FeaturePageShell.jsx'

function Notifikasi() {
  return (
    <FeaturePageShell
      eyebrow="Notifikasi"
      title="Pusat Notifikasi"
      description="Lihat pembaruan aplikasi, alert keamanan, dan reminder pembelajaran dalam satu feed yang mudah dipantau."
      stats={[
        { icon: 'bi-bell', title: 'Notifikasi Baru', value: '3', note: 'Belum dibaca' },
        { icon: 'bi-shield-exclamation', title: 'Alert Keamanan', value: '2', note: 'Perlu perhatian' },
        { icon: 'bi-book', title: 'Reminder Belajar', value: '4', note: 'Minggu ini' },
      ]}
      highlights={[
        {
          icon: 'bi-filter',
          title: 'Filter Prioritas',
          description: 'Pisahkan notifikasi penting dari pembaruan rutin agar lebih fokus.',
        },
        {
          icon: 'bi-clock',
          title: 'Riwayat Reminder',
          description: 'Lihat kembali pengingat belajar dan alert yang sudah pernah diterima.',
        },
        {
          icon: 'bi-toggle-on',
          title: 'Kanal Penerimaan',
          description: 'Kelola notifikasi in-app, email, dan mobile sesuai preferensi akun.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-bell-fill',
          title: 'Alert keamanan baru masuk',
          description: 'Terdapat peringatan phishing yang relevan dengan aktivitas terbaru Anda.',
          time: 'Hari ini, 07:05',
        },
        {
          icon: 'bi-journal-arrow-up',
          title: 'Pengingat modul dikirim',
          description: 'Sistem mengingatkan Anda untuk melanjutkan materi Mobile Banking.',
          time: 'Kemarin, 18:15',
        },
        {
          icon: 'bi-check-circle',
          title: 'Notifikasi dibersihkan',
          description: 'Feed berhasil diperbarui setelah dua alert lama ditandai selesai.',
          time: '2 hari lalu',
        },
      ]}
      tips={[
        'Simpan hanya alert penting agar feed tetap relevan.',
        'Gunakan pengaturan kanal notifikasi untuk mengurangi distraksi.',
        'Tindak lanjuti reminder belajar saat beban notifikasi sedang rendah.',
      ]}
    />
  )
}

export default Notifikasi
