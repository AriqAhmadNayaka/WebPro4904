-- Alter tabel posts untuk menambahkan kolom yang diperlukan
USE ci3_project;

-- Tambahkan kolom author jika belum ada
ALTER TABLE posts ADD COLUMN IF NOT EXISTS author VARCHAR(255) NOT NULL DEFAULT 'Anonymous';

-- Tambahkan kolom article jika belum ada
ALTER TABLE posts ADD COLUMN IF NOT EXISTS article TEXT;

-- Tambahkan kolom image jika belum ada
ALTER TABLE posts ADD COLUMN IF NOT EXISTS image VARCHAR(255) NULL;

-- Tambahkan kolom updated_at jika belum ada
ALTER TABLE posts ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update data existing: pindahkan content ke article jika article kosong
UPDATE posts SET article = content WHERE article IS NULL OR article = '';

-- Update data existing: set author ke 'Anonymous' jika kosong
UPDATE posts SET author = 'Anonymous' WHERE author IS NULL OR author = '';

-- Hapus kolom content lama jika masih ada
ALTER TABLE posts DROP COLUMN IF EXISTS content;