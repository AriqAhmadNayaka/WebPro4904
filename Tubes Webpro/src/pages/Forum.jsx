import FeaturePageShell from '../components/FeaturePageShell.jsx'

function Forum() {
  return (
    <FeaturePageShell
      eyebrow="Forum"
      title="Forum Kesadaran Digital"
      description="Tempat berdiskusi, bertanya, dan berbagi praktik terbaik keamanan digital bersama komunitas pengguna CyberVault."
      stats={[
        { icon: 'bi-chat-left-dots', title: 'Diskusi Aktif', value: '18', note: 'Minggu ini' },
        { icon: 'bi-people', title: 'Anggota Online', value: '42', note: 'Saat ini' },
        { icon: 'bi-bookmark-check', title: 'Topik Tersimpan', value: '7', note: 'Siap dibaca ulang' },
      ]}
      highlights={[
        {
          icon: 'bi-question-circle',
          title: 'Tanya Jawab Cepat',
          description: 'Ajukan pertanyaan seputar phishing, privasi data, atau keamanan perangkat.',
        },
        {
          icon: 'bi-person-hearts',
          title: 'Belajar dari Komunitas',
          description: 'Temukan pengalaman nyata pengguna lain saat menghadapi ancaman digital.',
        },
        {
          icon: 'bi-pin-angle',
          title: 'Topik Prioritas',
          description: 'Diskusi penting dapat disematkan agar mudah diakses oleh semua peserta.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-chat-left-text',
          title: 'Diskusi baru ditambahkan',
          description: 'Topik keamanan transaksi marketplace ramai dibahas komunitas.',
          time: 'Hari ini, 14:25',
        },
        {
          icon: 'bi-hand-thumbs-up',
          title: 'Jawaban Anda disukai',
          description: 'Tiga pengguna menandai jawaban Anda tentang MFA sebagai membantu.',
          time: 'Kemarin, 20:10',
        },
        {
          icon: 'bi-bookmark-star',
          title: 'Thread ditandai penting',
          description: 'Pembahasan kebocoran data publik masuk ke daftar bacaan utama.',
          time: '3 hari lalu',
        },
      ]}
      tips={[
        'Gunakan judul diskusi yang spesifik agar lebih mudah ditemukan anggota lain.',
        'Hindari membagikan data sensitif saat bertanya di forum terbuka.',
        'Ringkas solusi yang berhasil agar topik tetap bernilai untuk pembaca berikutnya.',
      ]}
    />
  )
}

export default Forum
