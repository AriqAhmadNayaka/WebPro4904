import { useEffect, useState, useContext } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { postService } from '../services/api';
import { AuthContext } from '../context/AuthContext';

const PostForm = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user } = useContext(AuthContext);
  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [file, setFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const isEdit = Boolean(id);

  useEffect(() => {
    if (isEdit) {
      fetchPost();
    }
  }, [id]);

  const fetchPost = async () => {
    try {
      setLoading(true);
      const response = await postService.getPostById(id);
      const post = response.data;
      if (post && post.ownerEmail && post.ownerEmail !== user.email && user.role !== 'admin') {
        alert('Anda tidak memiliki izin untuk mengedit post ini.');
        navigate('/posts');
        return;
      }
      setTitle(post.title || '');
      setContent(post.content || '');
    } catch (err) {
      setError('Gagal memuat data post.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    try {
      if (isEdit) {
        await postService.updatePost(id, {
          title,
          content,
          file,
          ownerEmail: user.email
        });
        setSuccess('Post berhasil diperbarui.');
      } else {
        await postService.createPost({
          title,
          content,
          file,
          ownerEmail: user.email
        });
        setSuccess('Post berhasil dibuat.');
      }
      navigate('/posts');
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menyimpan post.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-900 text-white py-12">
      <div className="max-w-3xl mx-auto px-4">
        <div className="bg-gray-800 border border-gray-700 rounded-3xl p-8 shadow-xl">
          <h1 className="text-4xl font-bold mb-4">{isEdit ? 'Edit Post' : 'Buat Post Baru'}</h1>
          <p className="text-gray-400 mb-6">{isEdit ? 'Perbarui informasi post dan upload gambar jika perlu.' : 'Tambahkan judul, isi, dan gambar untuk post baru.'}</p>

          {error && <div className="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded mb-4">{error}</div>}
          {success && <div className="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded mb-4">{success}</div>}

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label className="block text-gray-300 mb-2">Judul Post</label>
              <input
                type="text"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
                placeholder="Contoh: Cara Membuat Blog"
                className="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-400"
                required
              />
            </div>

            <div>
              <label className="block text-gray-300 mb-2">Konten Post</label>
              <textarea
                value={content}
                onChange={(e) => setContent(e.target.value)}
                placeholder="Tulis konten post di sini..."
                className="w-full min-h-[220px] bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-400"
                required
              />
            </div>

            <div>
              <label className="block text-gray-300 mb-2">Upload Gambar</label>
              <input
                type="file"
                accept="image/*"
                onChange={(e) => setFile(e.target.files[0] || null)}
                className="w-full text-sm text-gray-300"
              />
            </div>

            <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <button
                type="submit"
                disabled={loading}
                className="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition disabled:bg-gray-600"
              >
                {loading ? 'Menyimpan...' : isEdit ? 'Perbarui Post' : 'Buat Post'}
              </button>
              <button
                type="button"
                onClick={() => navigate('/posts')}
                className="w-full sm:w-auto bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl transition"
              >
                Batal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default PostForm;
