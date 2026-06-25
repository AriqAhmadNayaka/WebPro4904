<?php

class ContentController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        ContentBlock::ensureTable($this->conn);
    }

    public function index(): void
    {
        $page = trim((string) ($_GET["page"] ?? "all"));
        Response::json(["success" => true, "data" => ContentBlock::byPage($this->conn, $page)]);
    }

    public function store(): void
    {
        Auth::requireAdmin($this->conn);
        $id = ContentBlock::create($this->conn, $this->validatePayload(Request::jsonBody()));
        Response::json(["success" => true, "message" => "Konten berhasil ditambahkan.", "id" => $id], 201);
    }

    public function destroy(?int $id = null): void
    {
        Auth::requireAdmin($this->conn);
        $data = Request::jsonBody();
        $id = $id ?: (int) ($data["id"] ?? 0);

        if ($id <= 0) {
            Response::json(["success" => false, "message" => "ID konten tidak valid."], 422);
        }

        ContentBlock::delete($this->conn, $id);
        Response::json(["success" => true, "message" => "Konten berhasil dihapus."]);
    }

    private function validatePayload(array $data): array
    {
        $page = trim((string) ($data["page"] ?? "all"));
        $title = trim((string) ($data["title"] ?? ""));
        $body = trim((string) ($data["body"] ?? ""));

        if ($page === "" || $title === "" || $body === "") {
            Response::json(["success" => false, "message" => "Halaman, judul, dan isi konten wajib diisi."], 422);
        }

        return [
            "page" => $page,
            "title" => $title,
            "body" => $body,
            "image" => trim((string) ($data["image"] ?? "")),
            "linkUrl" => trim((string) ($data["linkUrl"] ?? "")),
            "linkLabel" => trim((string) ($data["linkLabel"] ?? "Buka"))
        ];
    }
}
