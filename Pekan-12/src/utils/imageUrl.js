export const normalizeImageUrl = (imageUrl) => {
    if (!imageUrl) return null;

    return imageUrl.replace('/uploads/posts/posts/', '/uploads/posts/');
};
