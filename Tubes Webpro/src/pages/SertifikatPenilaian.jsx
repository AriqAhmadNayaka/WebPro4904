import FeaturePageShell from '../components/FeaturePageShell.jsx'

function SertifikatPenilaian() {
  return (
    <FeaturePageShell
      eyebrow="Sertifikat & Penilaian"
      title="Sertifikat dan Penilaian"
      description="Pantau badge, skor kuis, dan sertifikat yang telah Anda raih dari seluruh perjalanan belajar di CyberVault."
      stats={[
        { icon: 'bi-patch-check', title: 'Sertifikat Aktif', value: '6', note: '3 kategori' },
        { icon: 'bi-ui-checks-grid', title: 'Kuis Selesai', value: '14', note: 'Rata-rata 82' },
        { icon: 'bi-trophy', title: 'Badge Berikutnya', value: '2', note: 'Hampir terbuka' },
      ]}
      highlights={[
        {
          icon: 'bi-award',
          title: 'Koleksi Badge',
          description: 'Lihat seluruh lencana yang diperoleh dari modul, kuis, dan konsistensi belajar.',
        },
        {
          icon: 'bi-graph-up-arrow',
          title: 'Skor Penilaian',
          description: 'Bandingkan hasil evaluasi per topik untuk mengetahui area yang perlu ditingkatkan.',
        },
        {
          icon: 'bi-printer',
          title: 'Sertifikat Siap Unduh',
          description: 'Akses sertifikat yang sudah lolos penilaian akhir dan validasi progres.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-patch-plus',
          title: 'Badge baru diperoleh',
          description: 'Badge Waspada Phishing berhasil dibuka setelah menyelesaikan kuis lanjutan.',
          time: 'Hari ini, 12:10',
        },
        {
          icon: 'bi-bar-chart',
          title: 'Nilai kuis diperbarui',
          description: 'Skor materi Keamanan Akun naik setelah pengulangan sesi evaluasi.',
          time: 'Kemarin, 17:00',
        },
        {
          icon: 'bi-file-earmark-medical',
          title: 'Sertifikat siap diverifikasi',
          description: 'Sertifikat Privasi Data masuk tahap finalisasi untuk diunduh.',
          time: '2 hari lalu',
        },
      ]}
      tips={[
        'Ulangi kuis dengan skor rendah untuk membuka badge tambahan lebih cepat.',
        'Simpan sertifikat yang sudah final sebagai portofolio pembelajaran.',
        'Gunakan ringkasan hasil penilaian untuk menentukan modul berikutnya.',
      ]}
    />
  )
}

export default SertifikatPenilaian
