<?php

class Location
{
    public static function ensureTable(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS map_locations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            category VARCHAR(50) NOT NULL,
            lat DECIMAL(10, 7) NOT NULL,
            lng DECIMAL(10, 7) NOT NULL,
            icon VARCHAR(80) NOT NULL,
            color VARCHAR(20) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            menu_image VARCHAR(255) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            ticket VARCHAR(100) DEFAULT NULL,
            hours VARCHAR(100) DEFAULT NULL,
            cost VARCHAR(100) DEFAULT NULL,
            rating VARCHAR(20) DEFAULT NULL,
            popular VARCHAR(150) DEFAULT NULL,
            tags TEXT DEFAULT NULL,
            details TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uq_map_locations_name (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        if (!mysqli_query($conn, $sql)) {
            Response::json([
                "success" => false,
                "message" => "Gagal menyiapkan tabel lokasi: " . mysqli_error($conn)
            ], 500);
        }

        self::seedDefaults($conn);
    }

    private static function seedDefaults(mysqli $conn): void
    {
        $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM map_locations");
        $row = $result ? mysqli_fetch_assoc($result) : ["total" => 0];
        if ((int) $row["total"] > 0) {
            return;
        }

        $defaults = [
            [
                "name" => "Gedung Sate",
                "category" => "sejarah",
                "lat" => -6.9025,
                "lng" => 107.6188,
                "icon" => "fa-landmark",
                "color" => "#1abc9c",
                "image" => "bdg5.jpg",
                "desc" => "Ikon sejarah Jawa Barat.",
                "address" => "Jl. Diponegoro No.22, Citarum",
                "ticket" => "Rp5.000 museum",
                "hours" => "08.00 - 16.00",
                "cost" => "Rp25.000 - Rp60.000",
                "rating" => "4.8",
                "tags" => "wisata sejarah budaya gedung sate museum foto keluarga",
                "details" => "Dibangun tahun 1920, gedung ini memiliki ciri khas ornamen tusuk sate pada menara pusatnya."
            ],
            [
                "name" => "Jalan Braga",
                "category" => "spotfoto",
                "lat" => -6.9177,
                "lng" => 107.6098,
                "icon" => "fa-camera-retro",
                "color" => "#e84393",
                "image" => "bdg6.jpg",
                "desc" => "Koridor klasik dengan bangunan art deco, mural, kafe, dan spot foto malam hari.",
                "address" => "Jl. Braga, Sumur Bandung",
                "ticket" => "Gratis",
                "hours" => "24 jam",
                "cost" => "Rp30.000 - Rp80.000",
                "rating" => "4.7",
                "tags" => "spot foto braga wisata sejarah malam murah art deco nongkrong"
            ],
            [
                "name" => "Kopi Mandja Progo",
                "category" => "kafe",
                "lat" => -6.9069,
                "lng" => 107.6157,
                "icon" => "fa-coffee",
                "color" => "#3498db",
                "image" => "KOPI1.jpg",
                "menuImage" => "KOPI2.png",
                "desc" => "Kafe nyaman untuk nongkrong, kerja ringan, dan menikmati suasana Bandung.",
                "address" => "Jl. Progo, Bandung",
                "rating" => "4.6",
                "popular" => "Kopi susu, pastry, area outdoor",
                "ticket" => "Gratis",
                "hours" => "08.00 - 22.00",
                "cost" => "Rp25.000 - Rp75.000",
                "tags" => "kafe cafe nongkrong murah wifi smoking area spot foto kopi"
            ],
            [
                "name" => "Batagor Kingsley",
                "category" => "kuliner",
                "lat" => -6.9141,
                "lng" => 107.6118,
                "icon" => "fa-utensils",
                "color" => "#e67e22",
                "image" => "bdg3.jpg",
                "menuImage" => "bdg3.jpg",
                "desc" => "Kuliner Bandung legendaris dengan menu batagor dan siomay.",
                "address" => "Jl. Veteran No.25, Bandung",
                "rating" => "4.5",
                "popular" => "Batagor, siomay, baso tahu",
                "ticket" => "Gratis",
                "hours" => "09.00 - 20.00",
                "cost" => "Rp35.000 - Rp70.000",
                "tags" => "kuliner makanan harga batagor murah keluarga menu"
            ],
            [
                "name" => "Taman Film Bandung",
                "category" => "ruangpublik",
                "lat" => -6.9004,
                "lng" => 107.6080,
                "icon" => "fa-tree",
                "color" => "#2ecc71",
                "image" => "bdg4.jpg",
                "desc" => "Ruang publik untuk bersantai, menonton layar terbuka, dan berkumpul.",
                "address" => "Jl. Layang Pasupati, Tamansari",
                "ticket" => "Gratis",
                "hours" => "06.00 - 22.00",
                "cost" => "Rp0 - Rp25.000",
                "rating" => "4.4",
                "tags" => "ruang publik gratis taman keluarga nongkrong murah"
            ],
            [
                "name" => "Festival Asia Afrika",
                "category" => "event",
                "lat" => -6.9210,
                "lng" => 107.6096,
                "icon" => "fa-calendar-check",
                "color" => "#9b59b6",
                "image" => "event1.jpg",
                "desc" => "Agenda budaya tahunan berisi parade, musik, kuliner, dan pameran komunitas.",
                "address" => "Kawasan Asia Afrika",
                "ticket" => "Gratis - Rp50.000",
                "hours" => "10.00 - 21.00",
                "cost" => "Rp40.000 - Rp120.000",
                "rating" => "4.7",
                "tags" => "event festival konser pameran budaya jadwal bandung"
            ],
            [
                "name" => "Gang Cibadak Malam",
                "category" => "hiddengem",
                "lat" => -6.9189,
                "lng" => 107.6008,
                "icon" => "fa-gem",
                "color" => "#f1c40f",
                "image" => "bdg1.jpg",
                "desc" => "Hidden gem kuliner malam dengan suasana lokal dan pilihan jajanan beragam.",
                "address" => "Cibadak, Astanaanyar",
                "ticket" => "Gratis",
                "hours" => "18.00 - 23.30",
                "cost" => "Rp20.000 - Rp65.000",
                "rating" => "4.5",
                "tags" => "hidden gem tempat lokal belum viral kuliner malam murah"
            ]
        ];

        foreach ($defaults as $location) {
            self::create($conn, $location);
        }
    }

    public static function normalize(array $row): array
    {
        return [
            "id" => (int) $row["id"],
            "name" => $row["name"],
            "category" => $row["category"],
            "lat" => (float) $row["lat"],
            "lng" => (float) $row["lng"],
            "icon" => $row["icon"],
            "color" => $row["color"],
            "image" => $row["image"],
            "menuImage" => $row["menu_image"],
            "address" => $row["address"],
            "desc" => $row["description"],
            "ticket" => $row["ticket"],
            "hours" => $row["hours"],
            "cost" => $row["cost"],
            "rating" => $row["rating"],
            "popular" => $row["popular"],
            "tags" => $row["tags"],
            "details" => $row["details"]
        ];
    }

    public static function all(mysqli $conn): array
    {
        $result = mysqli_query($conn, "SELECT * FROM map_locations ORDER BY id ASC");
        if (!$result) {
            Response::json(["success" => false, "message" => "Gagal mengambil lokasi: " . mysqli_error($conn)], 500);
        }

        $locations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $locations[] = self::normalize($row);
        }

        return $locations;
    }

    public static function create(mysqli $conn, array $payload): int
    {
        $payload = array_merge([
            "image" => "",
            "menuImage" => "",
            "address" => "",
            "desc" => "",
            "ticket" => "",
            "hours" => "",
            "cost" => "",
            "rating" => "",
            "popular" => "",
            "tags" => "",
            "details" => ""
        ], $payload);

        $stmt = mysqli_prepare($conn, "INSERT INTO map_locations (name, category, lat, lng, icon, color, image, menu_image, address, description, ticket, hours, cost, rating, popular, tags, details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param(
            $stmt,
            "ssddsssssssssssss",
            $payload["name"],
            $payload["category"],
            $payload["lat"],
            $payload["lng"],
            $payload["icon"],
            $payload["color"],
            $payload["image"],
            $payload["menuImage"],
            $payload["address"],
            $payload["desc"],
            $payload["ticket"],
            $payload["hours"],
            $payload["cost"],
            $payload["rating"],
            $payload["popular"],
            $payload["tags"],
            $payload["details"]
        );

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Gagal menyimpan lokasi: " . mysqli_error($conn)], 500);
        }

        return mysqli_insert_id($conn);
    }

    public static function delete(mysqli $conn, int $id): void
    {
        $stmt = mysqli_prepare($conn, "DELETE FROM map_locations WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Gagal menghapus lokasi: " . mysqli_error($conn)], 500);
        }
    }
}
