// Komponen kecil ini dipakai sebagai avatar bawaan saat data user tidak memiliki foto profil.
const DefaultAvatar = ({ className = "w-7 h-7" }) => (
    // Wrapper membentuk lingkaran fleksibel agar ukuran avatar bisa diatur dari props className.
    <div className={`${className} rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0`}>
        {/* Ikon SVG menampilkan siluet user sederhana di tengah lingkaran avatar. */}
        <svg className="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd"></path>
        </svg>
    </div>
);

// Export default membuat komponen mudah dipakai di Navbar, kartu post, dan detail post.
export default DefaultAvatar;
