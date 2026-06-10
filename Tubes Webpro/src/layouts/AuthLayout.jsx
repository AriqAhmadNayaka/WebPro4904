import { Outlet } from 'react-router-dom'
import illustrationShield from '../../Screenshot 2026-06-10 141418.png'

function AuthLayout() {
  return (
    <div className="cv-auth-page">
      <div className="cv-auth-bg-layer cv-auth-bg-layer--cyan" />
      <div className="cv-auth-bg-layer cv-auth-bg-layer--deep" />

      <div className="cv-auth-bg">
        <div className="cv-auth-wrapper">
          <section className="cv-auth-hero-card">
            <div className="cv-auth-copy">
              <h1 className="cv-auth-title">
                Keamanan Siber
                <span className="accent">Untuk Semua Orang</span>
              </h1>
              <p className="cv-auth-subtitle">
                Lindungi data pribadi, laporkan insiden siber, dan pantau keamanan
                digital Anda secara real-time.
              </p>
            </div>

            <div className="cv-auth-illustration-card">
              <img src={illustrationShield} alt="Ilustrasi keamanan CyberVault" />
            </div>
          </section>

          <section className="cv-auth-form-card">
            <Outlet />
          </section>
        </div>
      </div>

      <footer className="cv-auth-footer">
        <span>&copy; 2026 CyberVault dilindungi Hak Cipta</span>
        <div className="cv-auth-footer__links">
          <a href="/">Kebijakan Privasi</a>
          <a href="/">Syarat & Ketentuan</a>
          <a href="/">Bantuan</a>
        </div>
      </footer>
    </div>
  )
}

export default AuthLayout
