import { Link, useNavigate } from 'react-router-dom'

function Register() {
  const navigate = useNavigate()

  const handleRegister = (event) => {
    event.preventDefault()
    sessionStorage.setItem('cv-authenticated', 'true')
    navigate('/dashboard')
  }

  return (
    <div>
      <h2 className="cv-form-title">Buat Akun</h2>
      <p className="cv-form-description">
        Daftar untuk memulai perjalanan Anda bersama CyberVault.
      </p>

      <form className="mt-4" onSubmit={handleRegister}>
        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="register-email">
            Alamat Email
          </label>
          <div className="cv-input-group">
            <i className="bi bi-envelope" />
            <input
              id="register-email"
              type="email"
              className="cv-form-control"
              placeholder="name@company.com"
              required
            />
          </div>
        </div>

        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="register-password">
            Kata Sandi
          </label>
          <div className="cv-input-group">
            <i className="bi bi-lock" />
            <input
              id="register-password"
              type="password"
              className="cv-form-control"
              placeholder="••••••••"
              required
            />
            <button type="button" className="cv-input-action" aria-label="Lihat kata sandi">
              <i className="bi bi-eye" />
            </button>
          </div>
        </div>

        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="register-confirm-password">
            Konfirmasi Kata Sandi
          </label>
          <div className="cv-input-group">
            <i className="bi bi-shield-lock" />
            <input
              id="register-confirm-password"
              type="password"
              className="cv-form-control"
              placeholder="••••••••"
              required
            />
          </div>
        </div>

        <label className="cv-check-group cv-check-group--muted mb-4">
          <input type="checkbox" required />
          <span>Saya menyetujui Ketentuan Layanan dan Kebijakan Privasi.</span>
        </label>

        <button type="submit" className="cv-btn-primary">
          Buat Akun
        </button>
      </form>

      <div className="cv-auth-divider">Atau lanjutkan dengan</div>

      <div className="cv-auth-socials">
        <button type="button" className="cv-btn-social">
          <i className="bi bi-google" />
          Google
        </button>
        <button type="button" className="cv-btn-social">
          <i className="bi bi-microsoft" />
          Microsoft
        </button>
      </div>

      <div className="cv-auth-meta">
        <p className="mb-0">
          Sudah memiliki akun?{' '}
          <Link to="/login" className="cv-auth-link">
            Masuk
          </Link>
        </p>
      </div>
    </div>
  )
}

export default Register
