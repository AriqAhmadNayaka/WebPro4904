import React from 'react';

// Komponen DefaultAvatar kustom.
// Menampilkan placeholder gambar profil (avatar) berupa lingkaran abu-abu berisi ikon siluet pengguna (SVG).
// Menerima prop className agar ukurannya atau kelas Tailwind lainnya dapat diatur secara dinamis.
const DefaultAvatar = ({ className = "w-7 h-7" }) => (
  <div className={`${className} rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0`}>
    {/* Ikon siluet pengguna berupa SVG */}
    <svg className="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd"></path>
    </svg>
  </div>
);

export default DefaultAvatar;
