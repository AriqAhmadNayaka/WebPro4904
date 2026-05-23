import { useState, useEffect } from 'react';
import axios from 'axios';
import reactLogo from './assets/react.svg';
import './App.css';

export default function App() {
  const [token, setToken] = useState(localStorage.getItem('token') || '');
  const [selectedId, setSelectedId] = useState(null);

  if (!token) {
    return (
      <div className="login-wrapper">
        <Login setToken={setToken} />
      </div>
    );
  }

  return (
    <div className="app-container">
      <header className="app-header">
        <div className="header-logo-section">
          <img src={reactLogo} className="header-logo" alt="React logo" />
          <h3 style={{ margin: 0 }}>Portal Kuliah</h3>
        </div>
        <button
          className="btn btn-danger"
          onClick={() => {
            localStorage.removeItem('token');
            setToken('');
            setSelectedId(null);
          }}>
          Logout
        </button>
      </header>

      {selectedId ? (
        <DetailArtikel id={selectedId} token={token} kembali={() => setSelectedId(null)} />
      ) : (
        <DaftarArtikel token={token} bukaDetail={(id) => setSelectedId(id)} />
      )}
    </div>
  );
}

function Login({ setToken }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const prosesLogin = (e) => {
    e.preventDefault();
    setLoading(true);

    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    axios.post('https://pekan12-webpro.nets.web.id/api_login.php', formData)
      .then((res) => {
        localStorage.setItem('token', res.data.token);
        setToken(res.data.token);
      })
      .catch(() => {
        alert('Login Gagal! Periksa email dan password Anda.');
        setLoading(false);
      });
  };

  return (
    <div className="card-ui card-login">
      <img src={reactLogo} className="login-logo" alt="React logo" />
      <h2 className="card-title">Selamat Datang</h2>
      <p className="card-subtitle">Silakan masuk ke akun Anda</p>
      
      <form onSubmit={prosesLogin} className="form-layout">
        <input
          type="email"
          placeholder="Masukkan Email..."
          className="form-input"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="Masukkan Password..."
          className="form-input"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button type="submit" disabled={loading} className="btn btn-primary btn-block">
          {loading ? 'Memproses...' : 'Login Sekarang'}
        </button>
      </form>
    </div>
  );
}

function DaftarArtikel({ token, bukaDetail }) {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    axios.get('https://pekan12-webpro.nets.web.id/api_artikel.php', {
      headers: { Authorization: `Bearer ${token}` }
    })
    .then((res) => setPosts(res.data.data))
    .catch(() => alert('Akses Ditolak! Token tidak valid.'));
  }, [token]);

  return (
    <div className="card-ui">
      <h2 className="section-title">Berita Kampus Terbaru</h2>
      {posts.map((item) => (
        <div key={item.id} className="article-item">
          <h3 className="article-title">{item.title}</h3>
          <button onClick={() => bukaDetail(item.id)} className="btn btn-outline">
            Baca Selengkapnya &rarr;
          </button>
        </div>
      ))}
    </div>
  );
}

function DetailArtikel({ id, token, kembali }) {
  const [post, setPost] = useState(null);

  useEffect(() => {
    axios.get(`https://pekan12-webpro.nets.web.id/api_detail.php?id=${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    .then((res) => setPost(res.data.data))
    .catch(() => alert('Gagal memuat detail artikel.'));
  }, [id, token]);

  if (!post) {
    return <h2 style={{ textAlign: 'center', marginTop: '50px' }}>Memuat Rincian...</h2>;
  }

  return (
    <div className="card-ui">
      <button onClick={kembali} className="btn btn-secondary">
        &larr; Kembali
      </button>
      <h1 className="section-title" style={{ border: 'none', padding: 0 }}>{post.title}</h1>
      <p className="detail-meta"><strong>Ditulis oleh:</strong> {post.author}</p>
      <div className="detail-body">
        <p style={{ margin: 0 }}>{post.content}</p>
      </div>
    </div>
  );
}