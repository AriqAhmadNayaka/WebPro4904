import FeaturePageShell from '../components/FeaturePageShell.jsx'

function Asesmen() {
  return (
    <FeaturePageShell
      eyebrow="Asesmen"
      title="Asesmen Keamanan Digital"
      description="Ukur kesiapan digital Anda melalui penilaian bertahap, rekomendasi personal, dan insight yang mudah dipahami."
      stats={[
        { icon: 'bi-clipboard2-pulse', title: 'Skor Saat Ini', value: '80/100', note: 'Kategori baik' },
        { icon: 'bi-list-check', title: 'Asesmen Selesai', value: '5', note: '2 tersedia lagi' },
        { icon: 'bi-arrow-up-right', title: 'Perkembangan', value: '+14', note: 'Dari bulan lalu' },
      ]}
      highlights={[
        {
          icon: 'bi-ui-checks-grid',
          title: 'Kuesioner Bertahap',
          description: 'Jawab penilaian singkat untuk akun, perangkat, privasi, dan kebiasaan digital.',
        },
        {
          icon: 'bi-lightbulb',
          title: 'Rekomendasi Personal',
          description: 'Sistem menampilkan langkah prioritas berdasarkan hasil asesmen terbaru.',
        },
        {
          icon: 'bi-speedometer2',
          title: 'Skor Terukur',
          description: 'Bandingkan hasil Anda dari waktu ke waktu untuk melihat peningkatan nyata.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-play-circle',
          title: 'Asesmen mingguan dibuka',
          description: 'Sesi baru tersedia untuk mengevaluasi keamanan perangkat mobile.',
          time: 'Hari ini, 06:50',
        },
        {
          icon: 'bi-check-circle',
          title: 'Penilaian akun selesai',
          description: 'Skor meningkat setelah MFA diaktifkan pada akun utama.',
          time: 'Kemarin, 21:30',
        },
        {
          icon: 'bi-graph-up',
          title: 'Insight baru ditambahkan',
          description: 'Analisis menunjukkan area privasi masih bisa diperkuat.',
          time: '2 hari lalu',
        },
      ]}
      tips={[
        'Jalankan asesmen ulang setelah melakukan perubahan besar pada akun atau perangkat.',
        'Fokus pada rekomendasi dengan dampak tinggi namun langkah penerapan singkat.',
        'Bandingkan skor per kategori untuk menemukan area terlemah lebih cepat.',
      ]}
    />
  )
}

export default Asesmen
