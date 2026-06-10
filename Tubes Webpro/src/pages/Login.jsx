import { Link, useNavigate } from 'react-router-dom'

function Login() {
  const navigate = useNavigate()

  const handleLogin = (event) => {
    event.preventDefault()
    sessionStorage.setItem('cv-authenticated', 'true')
    navigate('/dashboard')
  }

  return (
    <div>
      <h2 className="cv-form-title">Login</h2>
      <p className="cv-form-description">
        Selamat datang kembali! Silakan masukkan detail Anda.
      </p>

      <form className="mt-4" onSubmit={handleLogin}>
        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="email">
            Alamat Email
          </label>
          <div className="cv-input-group">
            <i className="bi bi-envelope" />
            <input
              id="email"
              type="email"
              className="cv-form-control"
              placeholder="name@company.com"
              required
            />
          </div>
        </div>

        <div className="cv-form-group">
          <div className="d-flex justify-content-between align-items-center mb-2">
            <label className="cv-form-label mb-0" htmlFor="password">
              Kata Sandi
            </label>
            <Link to="/reset-password" className="cv-auth-link small">
              Lupa Kata Sandi?
            </Link>
          </div>
          <div className="cv-input-group">
            <i className="bi bi-lock" />
            <input
              id="password"
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

        <label className="cv-check-group cv-check-group--muted mb-4">
          <input type="checkbox" />
          <span>Ingat saya selama 30 hari</span>
        </label>

        <button type="submit" className="cv-btn-primary">
          Login
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
          Belum punya akun?{' '}
          <Link to="/register" className="cv-auth-link">
            Buat Akun
          </Link>
        </p>
      </div>
    </div>
  )
}

export default Login
