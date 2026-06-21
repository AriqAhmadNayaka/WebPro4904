import FeaturePageShell from '../components/FeaturePageShell.jsx'

function PembelajaranCSIRT() {
  return (
    <FeaturePageShell
      eyebrow="Pembelajaran CSIRT"
      title="Pusat Pembelajaran CSIRT"
      description="Dalami alur respons insiden, koordinasi tim, dan pola eskalasi melalui materi CSIRT yang lebih praktis dan terstruktur."
      stats={[
        { icon: 'bi-diagram-3', title: 'Modul Aktif', value: '9', note: 'Fokus operasional' },
        { icon: 'bi-clipboard-data', title: 'Simulasi', value: '4', note: 'Siap dipelajari' },
        { icon: 'bi-people', title: 'Role Tim', value: '6', note: 'Materi tersedia' },
      ]}
      highlights={[
        {
          icon: 'bi-arrow-repeat',
          title: 'Lifecycle Insiden',
          description: 'Pahami tahapan identifikasi, containment, eradication, dan recovery.',
        },
        {
          icon: 'bi-chat-left-text',
          title: 'Koordinasi Tim',
          description: 'Pelajari pembagian peran, komunikasi internal, dan pelaporan pihak ketiga.',
        },
        {
          icon: 'bi-file-earmark-break',
          title: 'Post-Incident Review',
          description: 'Susun evaluasi pasca-insiden untuk menutup celah dan meningkatkan kesiapan.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-play-circle',
          title: 'Sesi tabletop baru dibuka',
          description: 'Simulasi respons ransomware tersedia untuk latihan mandiri.',
          time: 'Hari ini, 13:10',
        },
        {
          icon: 'bi-journal-code',
          title: 'Modul triage diperbarui',
          description: 'Konten prioritas insiden kini disertai template klasifikasi cepat.',
          time: 'Kemarin, 10:30',
        },
        {
          icon: 'bi-award',
          title: 'Badge kesiapan tim terbuka',
          description: 'Selesaikan dua simulasi untuk membuka penilaian kesiapan berikutnya.',
          time: '3 hari lalu',
        },
      ]}
      tips={[
        'Gunakan checklist triage yang sama agar prioritas insiden lebih konsisten.',
        'Dokumentasikan keputusan containment untuk mempermudah review pasca-insiden.',
        'Pastikan daftar kontak darurat selalu diperbarui sebelum menjalankan simulasi.',
      ]}
    />
  )
}

export default PembelajaranCSIRT
