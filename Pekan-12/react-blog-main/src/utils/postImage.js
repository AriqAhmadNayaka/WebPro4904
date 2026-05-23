const API_ROOT = '/Pemrograman-Web/WebPro4904/Pekan-9';

export const getPostImageUrl = (post) => {
    if (!post) return null;

    const normalizePath = (path) => path.replace(/^\/+/, '').replace(/^posts\//, '');

    if (post.image_url) {
        const url = post.image_url
            .replace('http://localhost/ci3_project', API_ROOT)
            .replace('/uploads/posts/posts/', '/uploads/posts/');

        return url;
    }

    if (post.image) {
        return `${API_ROOT}/uploads/posts/${normalizePath(post.image)}`;
    }

    return null;
};
