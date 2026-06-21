import FeaturePageShell from '../components/FeaturePageShell.jsx'

function PemantauPrivasi() {
  return (
    <FeaturePageShell
      eyebrow="Pemantau Privasi"
      title="Pemantau Privasi Digital"
      description="Lihat kondisi privasi akun Anda, pantau eksposur data pribadi, dan dapatkan rekomendasi penguatan kontrol privasi harian."
      stats={[
        { icon: 'bi-eye-slash', title: 'Skor Privasi', value: '84/100', note: 'Status baik' },
        { icon: 'bi-person-badge', title: 'Akun Dipantau', value: '7', note: 'Sinkron aktif' },
        { icon: 'bi-exclamation-circle', title: 'Temuan Baru', value: '2', note: 'Perlu ditinjau' },
      ]}
      highlights={[
        {
          icon: 'bi-lock',
          title: 'Audit Izin Aplikasi',
          description: 'Periksa ulang izin akses aplikasi terhadap kontak, kamera, dan lokasi.',
        },
        {
          icon: 'bi-person-vcard',
          title: 'Jejak Identitas',
          description: 'Pantau data pribadi yang sering dipakai untuk verifikasi dan pemulihan akun.',
        },
        {
          icon: 'bi-globe2',
          title: 'Paparan Publik',
          description: 'Identifikasi informasi sensitif yang terlalu mudah ditemukan di internet.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-check2-circle',
          title: 'Izin lokasi berhasil ditinjau',
          description: 'Tiga aplikasi dibatasi agar tidak mengakses lokasi secara permanen.',
          time: 'Hari ini, 08:15',
        },
        {
          icon: 'bi-shield-lock',
          title: 'Email pemulihan diperbarui',
          description: 'Akun utama kini menggunakan alamat pemulihan yang lebih aman.',
          time: 'Kemarin, 19:20',
        },
        {
          icon: 'bi-info-circle',
          title: 'Saran privasi baru tersedia',
          description: 'Sistem menyarankan audit pada aplikasi yang jarang digunakan.',
          time: '2 hari lalu',
        },
      ]}
      tips={[
        'Matikan izin background location untuk aplikasi yang tidak membutuhkannya.',
        'Periksa kembali data publik pada profil media sosial dan marketplace.',
        'Gunakan email berbeda untuk login utama dan pemulihan akun penting.',
      ]}
    />
  )
}

export default PemantauPrivasi
