import { Link } from 'react-router-dom'

function TidakDitemukanPage() {
  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center bg-cybervault">
      <div className="cybervault-card cybervault-card--light text-center not-found-card">
        <div className="cybervault-card__icon mx-auto">
          <i className="bi bi-exclamation-octagon" />
        </div>
        <h1 className="cybervault-notfound__title">Halaman tidak ditemukan</h1>
        <p className="cybervault-card__text text-secondary">
          Rute yang kamu akses belum tersedia di setup awal CyberVault.
        </p>
        <Link to="/" className="btn cybervault-btn-primary">
          Kembali ke Beranda
        </Link>
      </div>
    </div>
  )
}

export default TidakDitemukanPage
