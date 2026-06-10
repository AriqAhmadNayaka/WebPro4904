import { useContext, useEffect, useState } from 'react';
import PortalLayout from '../components/PortalLayout';
import { portalService } from '../services/api';
import { AuthContext } from '../context/AuthContext';

function Account() {
  const { setUser } = useContext(AuthContext);
  const [notificationCount, setNotificationCount] = useState(0);
  const [form, setForm] = useState({ name: '', email: '' });
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    const loadData = async () => {
      try {
        setLoading(true);
        const [account, notifications] = await Promise.all([portalService.getAccount(), portalService.getNotifications()]);
        setForm({
          name: account.user.name || '',
          email: account.user.email || '',
        });
        setUser(account.user);
        setNotificationCount(notifications.unread_count || 0);
      } catch (err) {
        setError(err.response?.data?.message || 'Gagal memuat profil.');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, [setUser]);

  const handleSubmit = async (event) => {
    event.preventDefault();
    setMessage('');
    setError('');
    setSaving(true);
    try {
      const result = await portalService.updateAccount(form);
      setUser(result.user);
      setMessage('Profil berhasil diperbarui.');
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal memperbarui profil.');
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="screen-loader"><div className="loader-orb" /><p>Memuat akun...</p></div>;
  }

  return (
    <PortalLayout notificationCount={notificationCount}>
      <div className="portal-page">
        <section className="feature-panel">
          <div className="panel-header">
            <h3>Akun Saya</h3>
          </div>

          {error ? <div className="form-alert form-alert--error">{error}</div> : null}
          {message ? <div className="form-alert form-alert--success">{message}</div> : null}

          <form className="account-form" onSubmit={handleSubmit}>
            <div className="account-avatar">CV</div>
            <label className="account-field">
              <span>Nama Lengkap</span>
              <input type="text" value={form.name} onChange={(event) => setForm((prev) => ({ ...prev, name: event.target.value }))} required />
            </label>
            <label className="account-field">
              <span>Alamat Email</span>
              <input type="email" value={form.email} onChange={(event) => setForm((prev) => ({ ...prev, email: event.target.value }))} required />
            </label>
            <button type="submit" className="primary-auth-button account-form__submit" disabled={saving}>
              {saving ? 'Menyimpan...' : 'Simpan Perubahan'}
            </button>
          </form>
        </section>
      </div>
    </PortalLayout>
  );
}

export default Account;
