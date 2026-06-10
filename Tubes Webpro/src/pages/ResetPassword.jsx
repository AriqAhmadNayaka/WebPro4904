import { Link, useNavigate } from 'react-router-dom'

function ResetPassword() {
  const navigate = useNavigate()

  const handleResetPassword = (event) => {
    event.preventDefault()
    navigate('/login')
  }

  return (
    <div>
      <h2 className="cv-form-title">Atur Ulang Sandi</h2>
      <p className="cv-form-description">
        Atur ulang kata sandi untuk mengamankan akun Anda dengan CyberVault.
      </p>

      <form className="mt-4" onSubmit={handleResetPassword}>
        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="reset-email">
            Alamat Email
          </label>
          <div className="cv-input-group">
            <i className="bi bi-envelope" />
            <input
              id="reset-email"
              type="email"
              className="cv-form-control"
              placeholder="name@company.com"
              required
            />
          </div>
        </div>

        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="reset-otp">
            Code OTP
          </label>
          <div className="cv-auth-inline-group">
            <div className="cv-input-group flex-grow-1">
              <i className="bi bi-patch-check" />
              <input
                id="reset-otp"
                type="text"
                className="cv-form-control"
                placeholder="Masukkan OTP"
                required
              />
            </div>
            <button type="button" className="cv-btn-light cv-btn-inline">
              Get Code
            </button>
          </div>
        </div>

        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="new-password">
            Kata Sandi Baru
          </label>
          <div className="cv-input-group">
            <i className="bi bi-lock" />
            <input
              id="new-password"
              type="password"
              className="cv-form-control"
              placeholder="••••••••"
              required
            />
          </div>
        </div>

        <div className="cv-form-group">
          <label className="cv-form-label" htmlFor="confirm-new-password">
            Konfirmasi Kata Sandi
          </label>
          <div className="cv-input-group">
            <i className="bi bi-shield-lock" />
            <input
              id="confirm-new-password"
              type="password"
              className="cv-form-control"
              placeholder="••••••••"
              required
            />
          </div>
        </div>

        <button type="submit" className="cv-btn-primary">
          Atur Ulang Kata Sandi
        </button>
      </form>

      <div className="cv-auth-meta cv-auth-meta--single">
        <Link to="/login" className="cv-auth-link">
          Kembali ke Login
        </Link>
      </div>
    </div>
  )
}

export default ResetPassword
