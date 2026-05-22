import { Link } from 'react-router-dom';
import DefaultAvatar from './DefaultAvatar';

const truncateText = (text = '', maxLength = 150) => {
  if (text.length <= maxLength) return text;
  return `${text.substring(0, maxLength)}...`;
};

const formatDate = (dateString) => {
  if (!dateString) return '14 days ago';

  const date = new Date(dateString);
  const now = new Date();
  const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));

  if (diffDays === 0) return 'Today';
  if (diffDays === 1) return 'Yesterday';
  return `${diffDays} days ago`;
};

const PostCard = ({ post }) => (
  <article className="rounded-lg border border-gray-200 bg-white p-6 shadow-md transition-shadow hover:shadow-lg">
    <div className="mb-5 flex items-center justify-between text-gray-500">
      <span className="inline-flex items-center rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
        <svg className="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path
            fillRule="evenodd"
            d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
            clipRule="evenodd"
          />
          <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
        </svg>
        Article
      </span>
      <span className="flex items-center text-sm">
        <svg className="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path
            fillRule="evenodd"
            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
            clipRule="evenodd"
          />
        </svg>
        {formatDate(post.created_at)}
      </span>
    </div>

    <h2 className="mb-2 text-2xl font-bold tracking-tight text-gray-900">
      <Link to={`/posts/${post.id}`} className="transition-colors hover:text-blue-600">
        {post.title}
      </Link>
    </h2>

    <p className="mb-5 font-light text-gray-500">{truncateText(post.article || post.content || post.body)}</p>

    <div className="flex items-center justify-between">
      <div className="flex items-center space-x-3">
        <DefaultAvatar />
        <span className="font-medium text-gray-900">{post.author || post.user?.name || 'Admin'}</span>
      </div>
      <Link to={`/posts/${post.id}`} className="inline-flex items-center font-medium text-blue-600 transition-colors hover:text-blue-500">
        Read more
        <svg className="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path
            fillRule="evenodd"
            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
            clipRule="evenodd"
          />
        </svg>
      </Link>
    </div>
  </article>
);

export default PostCard;
