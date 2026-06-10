import { useEffect, useState } from 'react';
import { monitoringService } from './services/api';

const initialForm = {
  bin_code: '',
  location_name: '',
  waste_level: 0,
  status: 'AMAN',
  last_collection: '',
  notes: ''
};

const toDateTimeLocal = (value) => {
  if (!value) {
    return '';
  }

  return value.replace(' ', 'T').slice(0, 16);
};

function App() {
  const [bins, setBins] = useState([]);
  const [form, setForm] = useState(initialForm);
  const [editingId, setEditingId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    loadBins();
  }, []);

  const loadBins = async () => {
    try {
      setLoading(true);
      setError('');
      const data = await monitoringService.getAll();
      setBins(data);
    } catch (err) {
      setError(err?.response?.data?.message || 'Gagal memuat data monitoring.');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setForm((current) => ({
      ...current,
      [name]: name === 'waste_level' ? Number(value) : value
    }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError('');
    setMessage('');

    try {
      if (editingId) {
        await monitoringService.update(editingId, form);
        setMessage('Data monitoring berhasil diperbarui.');
      } else {
        await monitoringService.create(form);
        setMessage('Data monitoring berhasil ditambahkan.');
      }

      resetForm();
      loadBins();
    } catch (err) {
      setError(err?.response?.data?.message || 'Proses simpan gagal.');
    }
  };

  const handleEdit = (bin) => {
    setEditingId(bin.id);
    setForm({
      bin_code: bin.bin_code,
      location_name: bin.location_name,
      waste_level: Number(bin.waste_level),
      status: bin.status,
      last_collection: toDateTimeLocal(bin.last_collection),
      notes: bin.notes || ''
    });
    setMessage('');
    setError('');
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Hapus data monitoring ini?')) {
      return;
    }

    try {
      await monitoringService.remove(id);
      setMessage('Data monitoring berhasil dihapus.');
      if (editingId === id) {
        resetForm();
      }
      loadBins();
    } catch (err) {
      setError(err?.response?.data?.message || 'Gagal menghapus data.');
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setForm(initialForm);
  };

  return (
    <div className="app-shell">
      <section className="hero">
        <div>
          <p className="eyebrow">SmartTrash Monitor 2</p>
          <h1>Monitoring tempat sampah cerdas berbasis React + CodeIgniter</h1>
          <p className="hero-copy">
            Kelola lokasi bin, level sampah, status pengangkutan, dan catatan lapangan dalam satu dashboard.
          </p>
        </div>
        <div className="stats-card">
          <span>Total Bin</span>
          <strong>{loading ? '...' : bins.length}</strong>
          <small>API endpoint siap diuji dari Postman.</small>
        </div>
      </section>

      <main className="content-grid">
        <section className="panel">
          <div className="panel-header">
            <h2>{editingId ? 'Edit Monitoring' : 'Tambah Monitoring'}</h2>
            {editingId && (
              <button type="button" className="secondary-btn" onClick={resetForm}>
                Batal Edit
              </button>
            )}
          </div>

          <form className="monitoring-form" onSubmit={handleSubmit}>
            <label>
              Kode Bin
              <input name="bin_code" value={form.bin_code} onChange={handleChange} placeholder="BIN-004" required />
            </label>
            <label>
              Lokasi
              <input
                name="location_name"
                value={form.location_name}
                onChange={handleChange}
                placeholder="Gedung A Lantai 1"
                required
              />
            </label>
            <label>
              Level Sampah (%)
              <input
                name="waste_level"
                type="number"
                min="0"
                max="100"
                value={form.waste_level}
                onChange={handleChange}
                required
              />
            </label>
            <label>
              Status
              <select name="status" value={form.status} onChange={handleChange}>
                <option value="AMAN">AMAN</option>
                <option value="WASPADA">WASPADA</option>
                <option value="PENUH">PENUH</option>
                <option value="DIANGKUT">DIANGKUT</option>
              </select>
            </label>
            <label>
              Tanggal Pengangkutan
              <input
                name="last_collection"
                type="datetime-local"
                value={form.last_collection}
                onChange={handleChange}
              />
            </label>
            <label className="full-span">
              Catatan
              <textarea
                name="notes"
                value={form.notes}
                onChange={handleChange}
                rows="4"
                placeholder="Tulis catatan petugas atau kondisi bin..."
              />
            </label>

            {message ? <p className="feedback success">{message}</p> : null}
            {error ? <p className="feedback error">{error}</p> : null}

            <button className="primary-btn" type="submit">
              {editingId ? 'Update Data' : 'Simpan Data'}
            </button>
          </form>
        </section>

        <section className="panel">
          <div className="panel-header">
            <h2>Daftar Monitoring</h2>
            <button type="button" className="secondary-btn" onClick={loadBins}>
              Refresh
            </button>
          </div>

          {loading ? (
            <p className="placeholder">Memuat data monitoring...</p>
          ) : bins.length === 0 ? (
            <p className="placeholder">Belum ada data monitoring.</p>
          ) : (
            <div className="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Lokasi</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {bins.map((bin) => (
                    <tr key={bin.id}>
                      <td>{bin.bin_code}</td>
                      <td>{bin.location_name}</td>
                      <td>{bin.waste_level}%</td>
                      <td>
                        <span className={`status-pill status-${bin.status.toLowerCase()}`}>{bin.status}</span>
                      </td>
                      <td>{bin.notes || '-'}</td>
                      <td className="action-group">
                        <button type="button" className="secondary-btn" onClick={() => handleEdit(bin)}>
                          Edit
                        </button>
                        <button type="button" className="danger-btn" onClick={() => handleDelete(bin.id)}>
                          Hapus
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </section>
      </main>
    </div>
  );
}

export default App;
