import { useEffect, useState } from 'react';
import Footer from '../components/Footer';
import Navbar from '../components/Navbar';
import PostCard from '../components/PostCard';
import { postsAPI } from '../service/api';

const normalizePosts = (payload) => {
  if (Array.isArray(payload)) return payload;
  if (Array.isArray(payload?.data)) return payload.data;
  return [];
};

const PostList = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const fetchPosts = async () => {
    try {
      setLoading(true);
      const response = await postsAPI.getAll();
      setPosts(normalizePosts(response.data));
      setError('');
    } catch (err) {
      console.error('Error fetching posts:', err);
      setError(err.request ? 'Gagal memuat posts. Pastikan Apache dan MySQL di XAMPP sudah berjalan.' : 'Gagal memuat posts. Silakan coba lagi.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    let isMounted = true;

    postsAPI
      .getAll()
      .then((response) => {
        if (!isMounted) return;
        setPosts(normalizePosts(response.data));
        setError('');
      })
      .catch((err) => {
        if (!isMounted) return;
        console.error('Error fetching posts:', err);
        setError(err.request ? 'Gagal memuat posts. Pastikan Apache dan MySQL di XAMPP sudah berjalan.' : 'Gagal memuat posts. Silakan coba lagi.');
      })
      .finally(() => {
        if (isMounted) {
          setLoading(false);
        }
      });

    return () => {
      isMounted = false;
    };
  }, []);

  return (
    <div className="flex min-h-screen flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1">
        <div className="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
          <div className="mx-auto mb-8 max-w-screen-sm text-center lg:mb-16">
            <h2 className="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 lg:text-4xl">Our Blog</h2>
            <p className="font-light text-gray-500 sm:text-xl">
              Explore our collection of articles about React, web development, and more.
            </p>
          </div>

          {loading ? (
            <div className="flex items-center justify-center py-20">
              <div className="h-12 w-12 animate-spin rounded-full border-b-2 border-blue-600" />
            </div>
          ) : error ? (
            <div className="py-20 text-center">
              <p className="mb-4 text-red-500">{error}</p>
              <button
                type="button"
                onClick={fetchPosts}
                className="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
              >
                Coba Lagi
              </button>
            </div>
          ) : posts.length === 0 ? (
            <div className="py-20 text-center">
              <p className="text-gray-500">Belum ada posts.</p>
            </div>
          ) : (
            <div className="grid gap-8 lg:grid-cols-2">
              {posts.map((post) => (
                <PostCard key={post.id} post={post} />
              ))}
            </div>
          )}
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default PostList;
