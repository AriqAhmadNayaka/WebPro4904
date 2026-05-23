import { useState, useEffect, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { postService } from '../services/api';
import PostCard from '../components/PostCard';
import { AuthContext } from '../context/AuthContext';

const PostList = () => {
  const { user } = useContext(AuthContext);
  const navigate = useNavigate();
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchPosts();
  }, []);

  const fetchPosts = async () => {
    try {
      setLoading(true);
      const response = await postService.getPosts();
      setPosts(response.data || []);
    } catch (err) {
      setError('Gagal memuat posts. Silakan coba lagi.');
      console.error('Error fetching posts:', err);
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

  const handleCreatePost = () => {
    navigate('/posts/new');
  };

  return (
    <div className="min-h-screen bg-gray-900 text-white py-12">
      <div className="max-w-6xl mx-auto px-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
          <div>
            <h1 className="text-4xl font-bold mb-2">Blog Posts</h1>
            <p className="text-gray-400">Baca artikel terbaru dari penulis kami</p>
          </div>
          <button
            onClick={handleCreatePost}
            className="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition"
          >
            Buat Post Baru
          </button>
        </div>

        {error && (
          <div className="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded mb-6">
            {error}
          </div>
        )}

        {posts.length === 0 ? (
          <div className="text-center py-12">
            <p className="text-gray-400 text-lg">Belum ada post yang tersedia.</p>
          </div>
        ) : (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
              {posts.map((post) => (
                <PostCard
                  key={post.id}
                  post={post}
                  currentUserEmail={user?.email}
                />
              ))}
            </div>
          </>
        )}
      </div>
    </div>
  );
};

export default PostList;
