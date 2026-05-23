import { Link, useNavigate } from 'react-router-dom';
import { useContext } from 'react';
import DefaultAvatar from './DefaultAvatar';
import { AuthContext } from '../context/AuthContext';

const PostCard = ({ post, currentUserEmail }) => {
  const navigate = useNavigate();
  const { isAdmin } = useContext(AuthContext);
  const truncateText = (text, maxLength) => {
    if (text.length > maxLength) {
      return text.substring(0, maxLength) + '...';
    }
    return text;
  };

  return (
    <div className="bg-gray-800 rounded-lg overflow-hidden hover:shadow-xl transition border border-gray-700">
      {post.file_url && (
        <div className="overflow-hidden h-52 bg-black">
          <img
            src={post.file_url}
            alt={post.title}
            className="w-full h-full object-cover"
          />
        </div>
      )}
      <div className="p-6">
        <h2 className="text-2xl font-bold text-white mb-2 hover:text-blue-400 transition">
          <Link to={`/posts/${post.id}`}>
            {truncateText(post.title, 50)}
          </Link>
        </h2>
        
        <div className="flex items-center gap-3 mb-4">
          <DefaultAvatar name={post.ownerEmail || post.author_name || 'Author'} size="sm" />
          <div>
            <p className="text-gray-300 text-sm font-medium">
              {post.ownerEmail || post.author_name || 'Anonymous'}
            </p>
            <p className="text-gray-500 text-xs">
              {new Date(post.created_at).toLocaleDateString('id-ID')}
            </p>
          </div>
        </div>

        <p className="text-gray-400 mb-4">
          {truncateText(post.content, 150)}
        </p>

        <div className="flex flex-wrap gap-3 items-center">
          <Link
            to={`/posts/${post.id}`}
            className="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition"
          >
            Baca Selengkapnya
          </Link>
          {(post.ownerEmail === currentUserEmail || isAdmin) && (
            <button
              onClick={() => navigate(`/posts/${post.id}/edit`)}
              className="inline-block bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition"
            >
              Edit
            </button>
          )}
        </div>
      </div>
    </div>
  );
};

export default PostCard;
