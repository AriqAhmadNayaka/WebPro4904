import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/auth';
import DefaultAvatar from './DefaultAvatar';

const Navbar = () => {
  const { user, logout } = useAuth();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <nav className="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">
      <div className="mx-auto max-w-screen-xl px-4">
        <div className="flex h-16 items-center justify-between">
          <Link to="/posts" className="flex flex-shrink-0 items-center text-xl font-bold text-gray-900">
            <svg className="mr-2 h-8 w-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
            </svg>
            Blog Posts
          </Link>

          <div className="hidden items-center space-x-8 md:flex">
            <Link to="/posts" className="font-medium text-blue-600 transition-colors hover:text-blue-500">
              Home
            </Link>
            <a href="#" className="font-medium text-gray-600 transition-colors hover:text-gray-900">
              About
            </a>
            <a href="#" className="font-medium text-gray-600 transition-colors hover:text-gray-900">
              Contact
            </a>
          </div>

          <div className="flex items-center space-x-4">
            <div className="hidden items-center space-x-3 sm:flex">
              <DefaultAvatar className="h-8 w-8" />
              <span className="text-sm font-medium text-gray-900">{user?.name || user?.email || 'User'}</span>
            </div>
            <button
              type="button"
              onClick={logout}
              className="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-600"
            >
              Logout
            </button>
            <button
              type="button"
              onClick={() => setMobileMenuOpen((open) => !open)}
              className="p-2 text-gray-600 hover:text-gray-900 md:hidden"
              aria-label="Toggle menu"
            >
              <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        {mobileMenuOpen && (
          <div className="mt-2 border-t border-gray-200 pb-4 pt-4 md:hidden">
            <div className="flex flex-col space-y-3">
              <Link to="/posts" className="font-medium text-blue-600">
                Home
              </Link>
              <a href="#" className="text-gray-600 hover:text-gray-900">
                About
              </a>
              <a href="#" className="text-gray-600 hover:text-gray-900">
                Contact
              </a>
            </div>
          </div>
        )}
      </div>
    </nav>
  );
};

export default Navbar;
