import FeaturePageShell from '../components/FeaturePageShell.jsx'

function InformasiPeringatan() {
  return (
    <FeaturePageShell
      eyebrow="Informasi & Peringatan"
      title="Pusat Informasi dan Peringatan"
      description="Kumpulkan berita keamanan digital, alert penting, dan notifikasi ancaman terbaru agar Anda bisa mengambil langkah cepat."
      stats={[
        { icon: 'bi-bell', title: 'Alert Aktif', value: '5', note: '2 prioritas tinggi' },
        { icon: 'bi-newspaper', title: 'Update Hari Ini', value: '8', note: 'Kurasi terbaru' },
        { icon: 'bi-lightning', title: 'Ancaman Populer', value: 'Phishing', note: 'Paling sering muncul' },
      ]}
      highlights={[
        {
          icon: 'bi-megaphone',
          title: 'Peringatan Prioritas',
          description: 'Sorotan cepat untuk ancaman yang sedang meningkat di kanal populer.',
        },
        {
          icon: 'bi-journal-text',
          title: 'Ringkasan Mingguan',
          description: 'Lihat tren ancaman dan pola serangan dalam format yang lebih ringkas.',
        },
        {
          icon: 'bi-filter-circle',
          title: 'Kategori Alert',
          description: 'Pisahkan alert berdasarkan phishing, malware, kebocoran data, dan transaksi digital.',
        },
      ]}
      timeline={[
        {
          icon: 'bi-exclamation-triangle',
          title: 'Peringatan QR palsu diterbitkan',
          description: 'Modus pembayaran palsu meningkat di area perkotaan dan merchant kecil.',
          time: 'Hari ini, 07:45',
        },
        {
          icon: 'bi-lock',
          title: 'Update kebocoran data ditambahkan',
          description: 'Ringkasan vendor terdampak dan langkah mitigasi sudah diperbarui.',
          time: 'Kemarin, 18:00',
        },
        {
          icon: 'bi-broadcast',
          title: 'Notifikasi push terkirim',
          description: 'Pengguna menerima alert untuk perubahan pola penipuan berbasis OTP.',
          time: '2 hari lalu',
        },
      ]}
      tips={[
        'Beri prioritas pada alert yang berkaitan dengan akun finansial dan identitas utama.',
        'Bandingkan berita dari beberapa sumber sebelum mengambil tindakan besar.',
        'Simpan alert penting ke daftar tindak lanjut agar tidak terlewat.',
      ]}
    />
  )
}

export default InformasiPeringatan
