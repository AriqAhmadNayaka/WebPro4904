<?php

class LocationController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        Location::ensureTable($this->conn);
    }

    public function index(): void
    {
        Response::json(["success" => true, "data" => Location::all($this->conn)]);
    }

    public function store(): void
    {
        Auth::requireAdmin($this->conn);
        $id = Location::create($this->conn, $this->validatePayload(Request::jsonBody()));

        Response::json([
            "success" => true,
            "message" => "Lokasi berhasil ditambahkan.",
            "id" => $id
        ], 201);
    }

    public function destroy(?int $id = null): void
    {
        Auth::requireAdmin($this->conn);
        $data = Request::jsonBody();
        $id = $id ?: (int) ($data["id"] ?? 0);

        if ($id <= 0) {
            Response::json(["success" => false, "message" => "ID lokasi tidak valid."], 422);
        }

        Location::delete($this->conn, $id);
        Response::json(["success" => true, "message" => "Lokasi berhasil dihapus."]);
    }

    private function validatePayload(array $data): array
    {
        $name = trim((string) ($data["name"] ?? ""));
        $category = trim((string) ($data["category"] ?? ""));
        $lat = (float) ($data["lat"] ?? 0);
        $lng = (float) ($data["lng"] ?? 0);
        $desc = trim((string) ($data["desc"] ?? ""));
        $address = trim((string) ($data["address"] ?? ""));

        if ($name === "" || $category === "" || $desc === "" || $address === "" || !$lat || !$lng) {
            Response::json(["success" => false, "message" => "Nama, kategori, alamat, deskripsi, dan koordinat wajib diisi."], 422);
        }

        $isBusiness = $category === "kafe" || $category === "kuliner";

        return [
            "name" => $name,
            "category" => $category,
            "lat" => $lat,
            "lng" => $lng,
            "icon" => trim((string) ($data["icon"] ?? self::defaultIcon($category))),
            "color" => trim((string) ($data["color"] ?? self::defaultColor($category))),
            "image" => trim((string) ($data["image"] ?? "bdg1.jpg")),
            "menuImage" => $isBusiness ? trim((string) ($data["menuImage"] ?? "")) : "",
            "address" => $address,
            "desc" => $desc,
            "ticket" => trim((string) ($data["ticket"] ?? "Gratis")),
            "hours" => trim((string) ($data["hours"] ?? "-")),
            "cost" => trim((string) ($data["cost"] ?? "-")),
            "rating" => trim((string) ($data["rating"] ?? "0")),
            "popular" => trim((string) ($data["popular"] ?? "-")),
            "tags" => trim((string) ($data["tags"] ?? "")),
            "details" => trim((string) ($data["details"] ?? ""))
        ];
    }

    private static function defaultIcon(string $category): string
    {
        switch ($category) {
            case "kafe":
                return "fa-coffee";
            case "kuliner":
                return "fa-utensils";
            case "sejarah":
                return "fa-landmark";
            case "event":
                return "fa-calendar-check";
            case "spotfoto":
                return "fa-camera-retro";
            case "hiddengem":
                return "fa-gem";
            default:
                return "fa-tree";
        }
    }

    private static function defaultColor(string $category): string
    {
        switch ($category) {
            case "kafe":
                return "#3498db";
            case "kuliner":
                return "#e67e22";
            case "sejarah":
                return "#1abc9c";
            case "event":
                return "#9b59b6";
            case "spotfoto":
                return "#e84393";
            case "hiddengem":
                return "#f1c40f";
            default:
                return "#2ecc71";
        }
    }
}
