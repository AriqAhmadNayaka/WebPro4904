import { useEffect, useState, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import { authService } from '../services/api';

const UserManagement = () => {
  const { user, isAdmin } = useContext(AuthContext);
  const navigate = useNavigate();
  const [users, setUsers] = useState([]);
  const [selectedUser, setSelectedUser] = useState(null);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('user');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  useEffect(() => {
    if (!user || !isAdmin) {
      navigate('/dashboard');
      return;
    }
    loadUsers();
  }, [user, isAdmin, navigate]);

  const loadUsers = async () => {
    const response = await authService.getUsers();
    setUsers(response.data);
  };

  const resetForm = () => {
    setSelectedUser(null);
    setName('');
    setEmail('');
    setRole('user');
    setPassword('');
    setError('');
    setSuccess('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    try {
      if (selectedUser) {
        const payload = { name, email, role };
        if (password) {
          payload.password = password;
        }
        await authService.updateUser(selectedUser.id, payload);
        setSuccess('User berhasil diperbarui.');
      } else {
        if (!password) {
          setError('Password wajib diisi untuk user baru.');
          return;
        }
        await authService.createUser({ name, email, password, role });
        setSuccess('User baru berhasil dibuat.');
      }
      loadUsers();
      resetForm();
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menyimpan user.');
    }
  };

  const handleEdit = (userData) => {
    setSelectedUser(userData);
    setName(userData.name);
    setEmail(userData.email);
    setRole(userData.role || 'user');
    setPassword('');
    setError('');
    setSuccess('');
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Hapus user ini?')) {
      return;
    }
    try {
      await authService.deleteUser(id);
      if (selectedUser?.id === id) {
        resetForm();
      }
      loadUsers();
      setSuccess('User berhasil dihapus.');
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus user.');
    }
  };

  return (
    <div className="min-h-screen bg-gray-900 text-white py-12">
      <div className="container mx-auto px-4">
        <div className="bg-gray-800 rounded-3xl border border-blue-400/10 p-8 shadow-2xl mb-10">
          <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <p className="eyebrow">Manajemen User</p>
              <h1 className="text-4xl font-bold">Dashboard Admin</h1>
              <p className="hero__text text-gray-300">Kelola semua user, atur role, dan buat user baru.</p>
            </div>
            <div className="rounded-3xl bg-slate-900 border border-blue-400/10 p-6">
              <p className="text-sm text-gray-400">Akun aktif</p>
              <p className="text-xl font-semibold">{user.name}</p>
              <p className="text-gray-400">{user.email}</p>
            </div>
          </div>
        </div>

        <div className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
          <div className="bg-slate-950 rounded-3xl border border-blue-400/10 p-8">
            <h2 className="text-2xl font-bold mb-4">Daftar User</h2>
            <div className="overflow-x-auto">
              <table className="min-w-full text-left text-gray-300">
                <thead>
                  <tr className="border-b border-gray-700 text-sm text-gray-400">
                    <th className="py-3 px-4">ID</th>
                    <th className="py-3 px-4">Nama</th>
                    <th className="py-3 px-4">Email</th>
                    <th className="py-3 px-4">Role</th>
                    <th className="py-3 px-4">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {users.map((item) => (
                    <tr key={item.id} className="border-b border-gray-800 hover:bg-gray-900/60">
                      <td className="py-3 px-4">{item.id}</td>
                      <td className="py-3 px-4">{item.name}</td>
                      <td className="py-3 px-4">{item.email}</td>
                      <td className="py-3 px-4">{item.role}</td>
                      <td className="py-3 px-4 flex gap-2">
                        <button
                          onClick={() => handleEdit(item)}
                          className="table-button"
                        >
                          Edit
                        </button>
                        {item.email !== user.email && (
                          <button
                            onClick={() => handleDelete(item.id)}
                            className="table-button table-button--danger"
                          >
                            Hapus
                          </button>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          <div className="bg-slate-950 rounded-3xl border border-blue-400/10 p-8">
            <h2 className="text-2xl font-bold mb-4">{selectedUser ? 'Perbarui User' : 'Tambah User Baru'}</h2>
            {error && <div className="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded mb-4">{error}</div>}
            {success && <div className="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded mb-4">{success}</div>}
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-gray-300 mb-2">Nama</label>
                <input
                  className="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  required
                />
              </div>
              <div>
                <label className="block text-gray-300 mb-2">Email</label>
                <input
                  type="email"
                  className="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>
              <div>
                <label className="block text-gray-300 mb-2">Role</label>
                <select
                  className="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3"
                  value={role}
                  onChange={(e) => setRole(e.target.value)}
                >
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div>
                <label className="block text-gray-300 mb-2">Password</label>
                <input
                  type="password"
                  className="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder={selectedUser ? 'Kosongkan jika tidak ingin mengganti' : 'Password baru'}
                  required={!selectedUser}
                />
              </div>
              <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition">
                {selectedUser ? 'Perbarui User' : 'Buat User'}
              </button>
              {selectedUser && (
                <button
                  type="button"
                  onClick={resetForm}
                  className="w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl transition"
                >
                  Batal
                </button>
              )}
            </form>
          </div>
        </div>
      </div>
    </div>
  );
};

export default UserManagement;
