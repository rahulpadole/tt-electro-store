<?php
declare(strict_types=1);

class BrandModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $st = $this->db->query(
            "SELECT * FROM brands ORDER BY name ASC"
        );

        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $st = $this->db->prepare(
            "SELECT * FROM brands WHERE id=?"
        );

        $st->execute([$id]);

        $row = $st->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Insert a brand and return the newly created row.
     */
    public function createAndReturn(array $d): array
    {
        $st = $this->db->prepare(
            "INSERT INTO brands(name,logo) VALUES(?,?)"
        );

        $st->execute([
            trim($d['name']),
            isset($d['logo']) && $d['logo'] !== '' ? $d['logo'] : null,
        ]);

        $newId = (int)$this->db->lastInsertId();

        return $this->findById($newId) ?? [
            'id'   => $newId,
            'name' => trim($d['name']),
            'logo' => $d['logo'] ?? null,
        ];
    }

    /**
     * Legacy alias kept so any existing code calling create() still works.
     * Returns the created row, not an internal status array.
     */
    public function create(array $d): array
    {
        return $this->createAndReturn($d);
    }

    public function update(int $id, array $d): ?array
    {
        $fields = [];
        $vals   = [];

        if (array_key_exists('name', $d)) {
            $fields[] = "name=?";
            $vals[]   = trim($d['name']);
        }

        if (array_key_exists('logo', $d)) {
            $fields[] = "logo=?";
            $vals[]   = ($d['logo'] !== '' ? $d['logo'] : null);
        }

        if (empty($fields)) {
            return $this->findById($id);
        }

        $vals[] = $id;

        $sql = "UPDATE brands SET " . implode(',', $fields) . " WHERE id=?";

        $st = $this->db->prepare($sql);
        $st->execute($vals);

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare(
            "DELETE FROM brands WHERE id=?"
        );

        return $st->execute([$id]);
    }
}
