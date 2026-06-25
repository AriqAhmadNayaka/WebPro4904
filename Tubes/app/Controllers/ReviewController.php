<?php

class ReviewController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        Review::ensureTable($this->conn);
    }

    public function index(): void
    {
        Response::json(["success" => true, "data" => Review::all($this->conn)]);
    }

    public function show(int $id): void
    {
        $review = Review::find($this->conn, $id);
        if (!$review) {
            Response::json(["success" => false, "message" => "Review tidak ditemukan."], 404);
        }

        Response::json(["success" => true, "data" => $review]);
    }

    public function mine(): void
    {
        $userId = Auth::requireLogin();
        Response::json(["success" => true, "data" => Review::byUser($this->conn, $userId)]);
    }

    public function store(): void
    {
        $userId = Auth::requireLogin();
        $payload = $this->validatePayload(Request::jsonBody());
        $id = Review::create($this->conn, $userId, $payload);

        Response::json([
            "success" => true,
            "message" => "Review berhasil ditambahkan.",
            "id" => (string) $id
        ], 201);
    }

    public function update(?int $id = null): void
    {
        $data = Request::jsonBody();
        $id = $id ?: (int) ($data["id"] ?? 0);

        if ($id <= 0) {
            Response::json(["success" => false, "message" => "ID review tidak valid."], 422);
        }

        $this->authorizeReviewMutation($id);
        Review::update($this->conn, $id, $this->validatePayload($data));
        Response::json(["success" => true, "message" => "Review berhasil diperbarui."]);
    }

    public function destroy(?int $id = null): void
    {
        $data = Request::jsonBody();
        $id = $id ?: (int) ($data["id"] ?? 0);

        if ($id <= 0) {
            Response::json(["success" => false, "message" => "ID review tidak valid."], 422);
        }

        $this->authorizeReviewMutation($id);
        Review::delete($this->conn, $id);
        Response::json(["success" => true, "message" => "Review berhasil dihapus."]);
    }

    private function authorizeReviewMutation(int $reviewId): void
    {
        $userId = Auth::requireLogin();
        $user = User::findById($this->conn, $userId);
        $review = Review::find($this->conn, $reviewId);

        if (!$review) {
            Response::json(["success" => false, "message" => "Review tidak ditemukan."], 404);
        }

        if (($user["role"] ?? "user") === "admin") {
            return;
        }

        if ((int) ($review["userId"] ?? 0) !== $userId) {
            Response::json([
                "success" => false,
                "message" => "Kamu hanya bisa mengubah atau menghapus review milik akunmu sendiri."
            ], 403);
        }
    }

    private function validatePayload(array $data): array
    {
        $place = trim((string) ($data["place"] ?? ""));
        $name = trim((string) ($data["name"] ?? ""));
        $rating = (int) ($data["rating"] ?? 0);
        $visitDate = trim((string) ($data["visitDate"] ?? ""));
        $comment = trim((string) ($data["comment"] ?? ""));

        if ($place === "" || $name === "" || $comment === "") {
            Response::json(["success" => false, "message" => "Tempat, nama reviewer, dan komentar wajib diisi."], 422);
        }

        if ($rating < 1 || $rating > 5) {
            Response::json(["success" => false, "message" => "Rating harus di antara 1 sampai 5."], 422);
        }

        if ($visitDate !== "" && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $visitDate)) {
            Response::json(["success" => false, "message" => "Format tanggal kunjungan tidak valid."], 422);
        }

        return [
            "place" => $place,
            "name" => $name,
            "rating" => $rating,
            "visitDate" => $visitDate === "" ? null : $visitDate,
            "comment" => $comment
        ];
    }
}
