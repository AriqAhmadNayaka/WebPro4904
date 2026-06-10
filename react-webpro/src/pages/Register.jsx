import { useContext, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import AuthShell from '../components/AuthShell';
import AppIcon from '../components/AppIcon';

function Register() {
  const { register } = useContext(AuthContext);
  const navigate = useNavigate();
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    agree: false,
  });
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (!form.agree) {
      setError('Anda perlu menyetujui ketentuan layanan terlebih dahulu.');
      return;
    }

    setError('');
    setLoading(true);
    try {
      await register(form);
      navigate('/dashboard');
    } catch (err) {
      setError(err.response?.data?.message || 'Registrasi gagal. Silakan coba lagi.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthShell
      title="Buat Akun"
      subtitle="Daftar untuk memulai perjalanan Anda bersama CyberVault."
      footerPrompt="Sudah memiliki akun?"
      footerLink="/login"
      footerLabel="Masuk"
    >
      {error ? <div className="form-alert form-alert--error">{error}</div> : null}
      <form className="auth-form" onSubmit={handleSubmit}>
        <label className="auth-field">
          <span>Nama Lengkap</span>
          <div className="auth-input">
            <AppIcon name="user" className="icon-svg" />
            <input type="text" placeholder="Nama Anda" value={form.name} onChange={(event) => setForm((prev) => ({ ...prev, name: event.target.value }))} required />
          </div>
        </label>

        <label className="auth-field">
          <span>Alamat Email</span>
          <div className="auth-input">
            <AppIcon name="article" className="icon-svg" />
            <input type="email" placeholder="name@company.com" value={form.email} onChange={(event) => setForm((prev) => ({ ...prev, email: event.target.value }))} required />
          </div>
        </label>

        <label className="auth-field">
          <span>Kata Sandi</span>
          <div className="auth-input">
            <AppIcon name="lock" className="icon-svg" />
            <input type={showPassword ? 'text' : 'password'} placeholder="••••••••" value={form.password} onChange={(event) => setForm((prev) => ({ ...prev, password: event.target.value }))} required />
            <button type="button" className="ghost-eye" onClick={() => setShowPassword((value) => !value)}>
              {showPassword ? 'Hide' : 'Show'}
            </button>
          </div>
        </label>

        <label className="auth-field">
          <span>Konfirmasi Kata Sandi</span>
          <div className="auth-input">
            <AppIcon name="shield" className="icon-svg" />
            <input type={showConfirm ? 'text' : 'password'} placeholder="••••••••" value={form.confirm_password} onChange={(event) => setForm((prev) => ({ ...prev, confirm_password: event.target.value }))} required />
            <button type="button" className="ghost-eye" onClick={() => setShowConfirm((value) => !value)}>
              {showConfirm ? 'Hide' : 'Show'}
            </button>
          </div>
        </label>

        <label className="auth-checkbox">
          <input type="checkbox" checked={form.agree} onChange={(event) => setForm((prev) => ({ ...prev, agree: event.target.checked }))} />
          <span>Saya menyetujui Ketentuan Layanan dan Kebijakan Privasi.</span>
        </label>

        <button type="submit" className="primary-auth-button" disabled={loading}>
          {loading ? 'Membuat akun...' : 'Buat Akun'}
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
      </form>
    </AuthShell>
  );
}

export default Register;
