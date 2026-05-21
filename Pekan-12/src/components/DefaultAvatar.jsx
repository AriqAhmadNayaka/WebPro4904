// DefaultAvatar menjadi gambar profil cadangan karena backend tidak menyediakan foto user.
const DefaultAvatar = ({ className = 'w-7 h-7' }) => (
  // Ukuran avatar dapat diubah lewat prop className dari komponen pemanggil.
  <div className={`${className} rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0`}>
    {/* Icon user sederhana dibuat dengan SVG agar tidak perlu menambah library icon. */}
    <svg className="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
      <path
        fillRule="evenodd"
        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
        clipRule="evenodd"
      ></path>
    </svg>
  </div>
)

// Export default agar avatar bisa dipakai di Navbar, PostCard, dan PostDetail.
export default DefaultAvatar
