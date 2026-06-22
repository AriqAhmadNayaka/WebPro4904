import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { postsAPI } from '../services/api';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';

const PostCreate = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    title: '',
    author: '',
    article: '',
    image: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.title || !formData.author || !formData.article) {
      setError('Title, author, dan article wajib diisi.');
      return;
    }

    try {
      setLoading(true);
      setError('');
      await postsAPI.create(formData);
      navigate('/posts');
    } catch (err) {
      setError('Gagal membuat post. Silakan coba lagi.');
      console.error('Error creating post:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col">
      <Navbar />
      <main className="flex-1">
        <div className="py-8 px-4 mx-auto max-w-screen-md lg:py-16 lg:px-6">
          <div className="mb-6">
            <Link to="/posts" className="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
              <svg className="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd"></path>
              </svg>
              Back to Posts
            </Link>
          </div>

          <div className="bg-white rounded-lg border border-gray-200 shadow-md p-6 lg:p-8">
            <h2 className="mb-6 text-2xl font-bold text-gray-900">Create New Post</h2>
            
            {error && (
              <div className="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit}>
              <div className="mb-6">
                <label htmlFor="title" className="block mb-2 text-sm font-medium text-gray-900">Post Title</label>
                <input
                  type="text"
                  id="title"
                  name="title"
                  value={formData.title}
                  onChange={handleChange}
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  placeholder="Enter post title"
                  required
                />
              </div>

              <div className="mb-6">
                <label htmlFor="author" className="block mb-2 text-sm font-medium text-gray-900">Author Name</label>
                <input
                  type="text"
                  id="author"
                  name="author"
                  value={formData.author}
                  onChange={handleChange}
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  placeholder="Enter author name"
                  required
                />
              </div>

              <div className="mb-6">
                <label htmlFor="article" className="block mb-2 text-sm font-medium text-gray-900">Article Content</label>
                <textarea
                  id="article"
                  name="article"
                  rows="8"
                  value={formData.article}
                  onChange={handleChange}
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  placeholder="Write your article here..."
                  required
                ></textarea>
              </div>

              <div className="mb-6">
                <label htmlFor="image" className="block mb-2 text-sm font-medium text-gray-900">Image Filename (Optional)</label>
                <input
                  type="text"
                  id="image"
                  name="image"
                  value={formData.image}
                  onChange={handleChange}
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  placeholder="e.g., example.jpg"
                />
                <p className="mt-1 text-xs text-gray-500">Note: For now, enter the filename manually. Image upload not yet implemented.</p>
              </div>

              <div className="flex items-center space-x-4">
                <button
                  type="submit"
                  disabled={loading}
                  className={`text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors ${loading ? 'opacity-50 cursor-not-allowed' : ''}`}
                >
                  {loading ? 'Creating...' : 'Create Post'}
                </button>
                <Link
                  to="/posts"
                  className="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 transition-colors"
                >
                  Cancel
                </Link>
              </div>
            </form>
          </div>
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default PostCreate;
