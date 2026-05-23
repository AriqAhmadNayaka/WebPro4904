export const DefaultAvatar = ({ name, size = "md" }) => {
  const initials = name
    ?.split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2) || 'U';

  const sizes = {
    sm: 'w-8 h-8 text-xs',
    md: 'w-12 h-12 text-sm',
    lg: 'w-16 h-16 text-lg',
    xl: 'w-20 h-20 text-2xl'
  };

  const bgColors = [
    'bg-red-500',
    'bg-blue-500',
    'bg-green-500',
    'bg-yellow-500',
    'bg-purple-500',
    'bg-pink-500',
    'bg-indigo-500'
  ];

  const bgColor = bgColors[name?.charCodeAt(0) % bgColors.length] || 'bg-gray-500';

  return (
    <div className={`${sizes[size]} ${bgColor} rounded-full flex items-center justify-center text-white font-bold`}>
      {initials}
    </div>
  );
};

export default DefaultAvatar;
