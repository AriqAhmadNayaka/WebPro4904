import { useState, useEffect, useContext } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { postService } from '../services/api';
import DefaultAvatar from '../components/DefaultAvatar';
import { AuthContext } from '../context/AuthContext';

const PostDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user, isAdmin } = useContext(AuthContext);
  const [post, setPost] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchPost();
  }, [id]);

  const fetchPost = async () => {
    try {
      setLoading(true);
      const response = await postService.getPostById(id);
      setPost(response.data.data || response.data);
    } catch (err) {
      setError('Gagal memuat post. Silakan coba lagi.');
      console.error('Error fetching post:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-900 flex items-center justify-center">
        <div className="text-white text-xl">Loading...</div>
      </div>
    );
  }

  if (error || !post) {
    return (
      <div className="min-h-screen bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <div className="text-red-400 text-xl mb-4">{error || 'Post tidak ditemukan'}</div>
          <button
            onClick={() => navigate('/posts')}
            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition"
          >
            Kembali ke Posts
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-900 text-white py-12">
      <div className="max-w-3xl mx-auto px-4">
        <button
          onClick={() => navigate('/posts')}
          className="mb-6 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded transition"
        >
          ← Kembali
        </button>

        <article className="bg-gray-800 rounded-3xl border border-gray-700 p-8 shadow-xl">
          <h1 className="text-4xl font-bold mb-4">{post.title}</h1>

          <div className="flex flex-col lg:flex-row justify-between gap-4 mb-8 pb-8 border-b border-gray-700">
            <div className="flex items-center gap-3">
              <DefaultAvatar name={post.ownerEmail || post.author_name || 'Author'} size="md" />
              <div>
                <p className="font-medium">{post.ownerEmail || post.author_name || 'Anonymous'}</p>
                <p className="text-gray-400 text-sm">
                  {new Date(post.created_at).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                  })}
                </p>
              </div>
            </div>
            <span className="text-sm text-gray-400">ID Post: {post.id}</span>
          </div>

          {post.file_url && (
            <div className="mb-8 overflow-hidden rounded-3xl border border-gray-700">
              <img
                src={post.file_url}
                alt={post.title}
                className="w-full h-96 object-cover"
              />
            </div>
          )}

          <div className="prose prose-invert max-w-none text-gray-300 leading-relaxed whitespace-pre-wrap">
            {post.content}
          </div>

          <div className="mt-8 flex flex-wrap gap-3">
            {(post.ownerEmail === user?.email || isAdmin) && (
              <>
                <button
                  onClick={() => navigate(`/posts/${post.id}/edit`)}
                  className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition"
                >
                  Edit Post
                </button>
                <button
                  onClick={async () => {
                    if (!window.confirm('Hapus post ini?')) return;
                    try {
                      await postService.deletePost(post.id);
                      navigate('/posts');
                    } catch (err) {
                      console.error(err);
                      alert('Gagal menghapus post.');
                    }
                  }}
                  className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
                >
                  Hapus Post
                </button>
              </>
            )}
          </div>
          {post.updated_at && (
            <div className="mt-8 pt-8 border-t border-gray-700 text-sm text-gray-400">
              Terakhir diperbarui: {new Date(post.updated_at).toLocaleDateString('id-ID')}
            </div>
          )}
        </article>
      </div>
    </div>
  );
};

export default PostDetail;
