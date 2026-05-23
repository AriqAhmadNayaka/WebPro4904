import { Link, useNavigate } from 'react-router-dom';
import { useContext } from 'react';
import { AuthContext } from '../context/AuthContext';
import DefaultAvatar from './DefaultAvatar';

const Navbar = () => {
  const { user, logout } = useContext(AuthContext);
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <nav className="bg-gray-900 text-white shadow-lg border-b border-gray-700">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          <div className="flex items-center gap-8">
            <Link to="/dashboard" className="text-2xl font-bold text-blue-400">
              BlogPost
            </Link>
            {user && (
              <>
                <Link to="/dashboard" className="hover:text-blue-400 transition">
                  Dashboard
                </Link>
                <Link to="/posts" className="hover:text-blue-400 transition">
                  Posts
                </Link>
                <Link to="/posts/new" className="hover:text-blue-400 transition">
                  Buat Post
                </Link>
                {user.role === 'admin' && (
                  <Link to="/users" className="hover:text-blue-400 transition">
                    Pengguna
                  </Link>
                )}
              </>
            )}
          </div>

          <div className="flex items-center gap-4">
            {user ? (
              <>
                <div className="flex items-center gap-2">
                  <DefaultAvatar name={user.name} size="sm" />
                  <span className="text-sm">{user.name}</span>
                </div>
                <button
                  onClick={handleLogout}
                  className="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition"
                >
                  Logout
                </button>
              </>
            ) : (
              <>
                <Link
                  to="/login"
                  className="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded transition"
                >
                  Login
                </Link>
                <Link
                  to="/register"
                  className="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded transition"
                >
                  Register
                </Link>
              </>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
