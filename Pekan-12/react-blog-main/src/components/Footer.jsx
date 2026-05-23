const Footer = () => {
    return (
        <footer className="bg-white border-t border-gray-200">
            <div className="max-w-screen-xl mx-auto px-4 py-8">
                <div className="md:flex md:justify-between">
                    <div className="mb-6 md:mb-0">
                        <a href="#" className="flex items-center">
                            <svg className="w-8 h-8 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                            </svg>
                            <span className="text-xl font-bold text-gray-900">Blog Posts</span>
                        </a>
                        <p className="mt-2 text-sm text-gray-500">
                            A simple blog platform built with React & Laravel.
                        </p>
                    </div>
                    <div className="grid grid-cols-2 gap-8 sm:gap-16">
                        <div>
                            <h3 className="mb-4 text-sm font-semibold text-gray-900 uppercase">Resources</h3>
                            <ul className="text-gray-500 space-y-2">
                                <li><a href="#" className="hover:text-gray-900 transition-colors">React</a></li>
                                <li><a href="#" className="hover:text-gray-900 transition-colors">Laravel</a></li>
                                <li><a href="#" className="hover:text-gray-900 transition-colors">Tailwind CSS</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 className="mb-4 text-sm font-semibold text-gray-900 uppercase">Follow us</h3>
                            <ul className="text-gray-500 space-y-2">
                                <li><a href="#" className="hover:text-gray-900 transition-colors">Github</a></li>
                                <li><a href="#" className="hover:text-gray-900 transition-colors">Twitter</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr className="my-6 border-gray-200" />
                <p className="text-center text-sm text-gray-500">
                    © 2024 Blog Posts. All rights reserved.
                </p>
            </div>
        </footer>
    );
};

export default Footer;
