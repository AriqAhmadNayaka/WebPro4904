import FeaturePageShell from '../components/FeaturePageShell.jsx'

function PusatBantuan() {
  return (
    <FeaturePageShell
      eyebrow="Pusat Bantuan"
      title="Pusat Bantuan CyberVault"
      description="Temukan jawaban cepat, panduan penggunaan fitur, dan langkah pemecahan masalah umum tanpa harus keluar dari aplikasi."
      stats={[
        { icon: 'bi-life-preserver', title: 'Artikel Bantuan', value: '26', note: 'Terorganisir' },
        { icon: 'bi-question-circle', title: 'FAQ Populer', value: '12', note: 'Sering dibuka' },
        { icon: 'bi-chat-right-text', title: 'Panduan Baru', value: '4', note: 'Minggu ini' },
      ]}
      highlights={[
        {
          icon: 'bi-search',
          title: 'Panduan Fitur',
          description: 'Cari langkah penggunaan dashboard, edukasi, asesmen, dan pelaporan insiden.',
        },
        {
          icon: 'bi-journal-bookmark',
          title: 'FAQ Cepat',
          description: 'Akses daftar pertanyaan umum yang sering dibutuhkan pengguna baru maupun aktif.',
        },
        {
          icon: 'bi-tools',
          title: 'Troubleshooting',
          description: 'Ikuti checklist saat mengalami kendala login, sinkronisasi, atau notifikasi.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-book',
          title: 'Panduan pusat edukasi diperbarui',
          description: 'Instruksi penggunaan kategori, progress, dan modul baru sudah ditambahkan.',
          time: 'Hari ini, 09:10',
        },
        {
          icon: 'bi-info-circle',
          title: 'FAQ login ditinjau',
          description: 'Bagian reset password kini memiliki langkah yang lebih ringkas.',
          time: 'Kemarin, 14:30',
        },
        {
          icon: 'bi-wrench',
          title: 'Checklist troubleshooting dipublikasikan',
          description: 'Dokumen untuk menangani masalah navigasi dan cache tersedia.',
          time: '2 hari lalu',
        },
      ]}
      tips={[
        'Mulai dari FAQ populer sebelum masuk ke panduan yang lebih panjang.',
        'Catat langkah yang sudah dicoba agar troubleshooting lebih cepat.',
        'Jika masalah terkait cache, lakukan hard refresh setelah perubahan besar.',
      ]}
    />
  )
}

export default PusatBantuan
