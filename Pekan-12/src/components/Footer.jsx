// Footer digunakan ulang di halaman list dan detail agar tampilan konsisten.
const Footer = () => {
  return (
    <footer className="bg-white border-t border-gray-200">
      <div className="max-w-screen-xl mx-auto px-4 py-8">
        <div className="md:flex md:justify-between">
          {/* Identitas aplikasi praktikum. */}
          <div className="mb-6 md:mb-0">
            <a href="#top" className="flex items-center">
              <svg className="w-8 h-8 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
              </svg>
              <span className="text-xl font-bold text-gray-900">Blog Posts</span>
            </a>
            <p className="mt-2 text-sm text-gray-500">Aplikasi React sederhana yang memakai REST API CodeIgniter 3.</p>
          </div>

          {/* Link tambahan dibuat statis seperti contoh modul. */}
          <div className="grid grid-cols-2 gap-8 sm:gap-16">
            <div>
              <h3 className="mb-4 text-sm font-semibold text-gray-900 uppercase">Resources</h3>
              <ul className="text-gray-500 space-y-2">
                <li>
                  <a href="https://react.dev" className="hover:text-gray-900 transition-colors">
                    React
                  </a>
                </li>
                <li>
                  <a href="https://codeigniter.com" className="hover:text-gray-900 transition-colors">
                    CodeIgniter
                  </a>
                </li>
                <li>
                  <a href="https://tailwindcss.com" className="hover:text-gray-900 transition-colors">
                    Tailwind CSS
                  </a>
                </li>
              </ul>
            </div>

            <div>
              <h3 className="mb-4 text-sm font-semibold text-gray-900 uppercase">Follow us</h3>
              <ul className="text-gray-500 space-y-2">
                <li>
                  <a href="#github" className="hover:text-gray-900 transition-colors">
                    Github
                  </a>
                </li>
                <li>
                  <a href="#twitter" className="hover:text-gray-900 transition-colors">
                    Twitter
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        {/* Garis pemisah dan copyright sederhana. */}
        <hr className="my-6 border-gray-200" />
        <p className="text-center text-sm text-gray-500">© 2026 Blog Posts. All rights reserved.</p>
      </div>
    </footer>
  )
}

// Export default agar Footer bisa dipanggil dari beberapa halaman.
export default Footer
