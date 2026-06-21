import FeaturePageShell from '../components/FeaturePageShell.jsx'

function Pengaturan() {
  return (
    <FeaturePageShell
      eyebrow="Pengaturan"
      title="Pengaturan CyberVault"
      description="Kelola preferensi akun, notifikasi, tampilan, dan kontrol keamanan dasar dari satu halaman pengaturan yang terpusat."
      stats={[
        { icon: 'bi-sliders', title: 'Preferensi Aktif', value: '12', note: 'Siap diatur' },
        { icon: 'bi-bell', title: 'Notifikasi', value: '3 Kanal', note: 'Email, in-app, mobile' },
        { icon: 'bi-shield-lock', title: 'Keamanan Akun', value: 'Optimal', note: 'MFA aktif' },
      ]}
      highlights={[
        {
          icon: 'bi-person-gear',
          title: 'Profil & Preferensi',
          description: 'Atur identitas dasar, bahasa, dan kebiasaan tampilan yang paling nyaman.',
        },
        {
          icon: 'bi-bell-slash',
          title: 'Kontrol Notifikasi',
          description: 'Pilih alert yang ingin diterima agar tetap relevan dan tidak berlebihan.',
        },
        {
          icon: 'bi-key',
          title: 'Akses & Pemulihan',
          description: 'Kelola password, perangkat tepercaya, dan metode pemulihan akun.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-check2-circle',
          title: 'Preferensi notifikasi diperbarui',
          description: 'Ringkasan mingguan kini dikirim melalui email setiap Senin pagi.',
          time: 'Hari ini, 08:40',
        },
        {
          icon: 'bi-phone',
          title: 'Perangkat baru ditambahkan',
          description: 'Satu perangkat mobile berhasil diverifikasi sebagai perangkat tepercaya.',
          time: 'Kemarin, 22:15',
        },
        {
          icon: 'bi-shield-check',
          title: 'Keamanan akun ditinjau',
          description: 'Metode pemulihan dan verifikasi cadangan sudah diperbarui.',
          time: '3 hari lalu',
        },
      ]}
      tips={[
        'Tinjau ulang daftar perangkat tepercaya setiap beberapa minggu.',
        'Aktifkan notifikasi penting agar perubahan akun tidak terlewat.',
        'Simpan metode pemulihan cadangan yang tidak sama dengan akun utama.',
      ]}
    />
  )
}

export default Pengaturan
