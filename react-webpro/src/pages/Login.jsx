import { useContext, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import AuthShell from '../components/AuthShell';
import AppIcon from '../components/AppIcon';

function Login() {
  const { login } = useContext(AuthContext);
  const navigate = useNavigate();
  const [form, setForm] = useState({ email: '', password: '', remember: false });
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError('');
    setLoading(true);

    try {
      await login(form.email, form.password);
      navigate('/dashboard');
    } catch (err) {
      setError(err.response?.data?.message || 'Login gagal. Silakan periksa kembali akun Anda.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthShell
      title="Login"
      subtitle="Selamat datang kembali! Silakan masukkan detail Anda."
      footerPrompt="Belum punya akun?"
      footerLink="/register"
      footerLabel="Create account"
    >
      {error ? <div className="form-alert form-alert--error">{error}</div> : null}
      <form className="auth-form" onSubmit={handleSubmit}>
        <label className="auth-field">
          <span>Alamat Email</span>
          <div className="auth-input">
            <AppIcon name="article" className="icon-svg" />
            <input type="email" placeholder="name@company.com" value={form.email} onChange={(event) => setForm((prev) => ({ ...prev, email: event.target.value }))} required />
          </div>
        </label>

        <label className="auth-field">
          <div className="auth-field__row">
            <span>Kata Sandi</span>
            <Link to="/reset-password">Lupa Kata Sandi?</Link>
          </div>
          <div className="auth-input">
            <AppIcon name="lock" className="icon-svg" />
            <input type={showPassword ? 'text' : 'password'} placeholder="••••••••" value={form.password} onChange={(event) => setForm((prev) => ({ ...prev, password: event.target.value }))} required />
            <button type="button" className="ghost-eye" onClick={() => setShowPassword((value) => !value)}>
              {showPassword ? 'Hide' : 'Show'}
            </button>
          </div>
        </label>

        <label className="auth-checkbox">
          <input type="checkbox" checked={form.remember} onChange={(event) => setForm((prev) => ({ ...prev, remember: event.target.checked }))} />
          <span>Ingat saya selama 30 hari</span>
        </label>

        <button type="submit" className="primary-auth-button" disabled={loading}>
          {loading ? 'Memproses...' : 'Login'}
        </button>

        <div className="auth-divider">Atau lanjutkan dengan</div>
        <div className="auth-socials">
          <button type="button" className="social-auth-button">
            <span className="social-dot social-dot--google" />
            Google
          </button>
          <button type="button" className="social-auth-button">
            <span className="social-dot social-dot--microsoft" />
            Microsoft
          </button>
        </div>

        <div className="auth-linkstack">
          <Link to="/reset-password">Lupa Kata Sandi?</Link>
          <p>
            Belum punya akun? <Link to="/register">Create account</Link>
          </p>
        </div>
      </form>
    </AuthShell>
  );
}

export default Login;
