# SmartTrash Monitor 2

Lanjutan dari project `smarttrash_monitor` dengan struktur terpisah untuk:

- `codeigniter/` API backend CRUD monitoring.
- `database/` file SQL untuk import database.
- `react/` frontend React untuk konsumsi API.
- `postman/` koleksi Postman siap import.

## Endpoint API

- `GET /api/monitoring`
- `GET /api/monitoring/{id}`
- `POST /api/monitoring`
- `PUT /api/monitoring/{id}`
- `DELETE /api/monitoring/{id}`

## Langkah pakai singkat

1. Import [database/smarttrash_monitor2.sql](/d:/xampp/htdocs/Pemrograman_Web/WebPro4904/smarttrash_monitor2/database/smarttrash_monitor2.sql).
2. Simpan folder `codeigniter` di dalam `htdocs`.
3. Import [postman/smarttrash_monitor2.postman_collection.json](/d:/xampp/htdocs/Pemrograman_Web/WebPro4904/smarttrash_monitor2/postman/smarttrash_monitor2.postman_collection.json) ke Postman.
4. Di folder `react`, install dependency lalu jalankan `npm run dev`.

Login admin bawaan untuk dashboard CodeIgniter: `admin@cybervault.com` / `admin123`.
