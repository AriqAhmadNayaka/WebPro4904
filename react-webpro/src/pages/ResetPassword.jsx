import { useState } from 'react';
import AuthShell from '../components/AuthShell';
import AppIcon from '../components/AppIcon';
import { authService } from '../services/api';

function ResetPassword() {
  const [form, setForm] = useState({
    email: '',
    otp: '',
    password: '',
    confirm_password: '',
  });
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [otpInfo, setOtpInfo] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [loadingOtp, setLoadingOtp] = useState(false);
  const [loadingSubmit, setLoadingSubmit] = useState(false);

  const requestOtp = async () => {
    setError('');
    setMessage('');
    setLoadingOtp(true);
    try {
      const result = await authService.requestReset({ email: form.email });
      setOtpInfo(`OTP dev: ${result.otp_debug} (aktif ${result.expires_in_minutes} menit)`);
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal mengambil OTP.');
    } finally {
      setLoadingOtp(false);
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError('');
    setMessage('');
    setLoadingSubmit(true);
    try {
      await authService.resetPassword(form);
      setMessage('Kata sandi berhasil diperbarui. Silakan login dengan password baru.');
      setForm({ email: form.email, otp: '', password: '', confirm_password: '' });
    } catch (err) {
      setError(err.response?.data?.message || 'Reset password gagal.');
    } finally {
      setLoadingSubmit(false);
    }
  };

  return (
    <AuthShell title="Atur Ulang Sandi" subtitle="Atur ulang kata sandi untuk mengamankan akun Anda dengan CyberVault.">
      {error ? <div className="form-alert form-alert--error">{error}</div> : null}
      {message ? <div className="form-alert form-alert--success">{message}</div> : null}
      {otpInfo ? <div className="form-alert form-alert--info">{otpInfo}</div> : null}

      <form className="auth-form" onSubmit={handleSubmit}>
        <label className="auth-field">
          <span>Alamat Email</span>
          <div className="auth-input">
            <AppIcon name="article" className="icon-svg" />
            <input type="email" placeholder="name@company.com" value={form.email} onChange={(event) => setForm((prev) => ({ ...prev, email: event.target.value }))} required />
          </div>
        </label>

        <label className="auth-field">
          <span>Kode OTP</span>
          <div className="auth-input">
            <AppIcon name="shield" className="icon-svg" />
            <input type="text" placeholder="Code OTP" value={form.otp} onChange={(event) => setForm((prev) => ({ ...prev, otp: event.target.value }))} required />
          </div>
        </label>

        <div className="auth-inline-button">
          <span>Verifikasi Email</span>
          <button type="button" className="secondary-auth-button" onClick={requestOtp} disabled={loadingOtp || !form.email}>
            {loadingOtp ? 'Mengambil...' : 'Get Code'}
          </button>
        </div>

        <label className="auth-field">
          <span>Kata Sandi Baru</span>
          <div className="auth-input">
            <AppIcon name="lock" className="icon-svg" />
            <input type={showPassword ? 'text' : 'password'} placeholder="********" value={form.password} onChange={(event) => setForm((prev) => ({ ...prev, password: event.target.value }))} required />
            <button type="button" className="ghost-eye" onClick={() => setShowPassword((value) => !value)}>
              {showPassword ? 'Hide' : 'Show'}
            </button>
          </div>
        </label>

        <label className="auth-field">
          <span>Konfirmasi Kata Sandi</span>
          <div className="auth-input">
            <AppIcon name="shield" className="icon-svg" />
            <input type={showConfirm ? 'text' : 'password'} placeholder="********" value={form.confirm_password} onChange={(event) => setForm((prev) => ({ ...prev, confirm_password: event.target.value }))} required />
            <button type="button" className="ghost-eye" onClick={() => setShowConfirm((value) => !value)}>
              {showConfirm ? 'Hide' : 'Show'}
            </button>
          </div>
        </label>

        <button type="submit" className="primary-auth-button" disabled={loadingSubmit}>
          {loadingSubmit ? 'Menyimpan...' : 'Atur Ulang Kata Sandi'}
        </button>
      </form>
    </AuthShell>
  );
}

export default ResetPassword;
