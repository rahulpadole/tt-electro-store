<?php
declare(strict_types=1);

class FaqModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array {
        return $this->db->query('SELECT * FROM faq ORDER BY created_at ASC')->fetchAll();
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM faq WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function create(array $d): array {
        $st = $this->db->prepare('INSERT INTO faq (question,answer,category) VALUES (?,?,?)');
        $st->execute([$d['question'], $d['answer'], $d['category'] ?? null]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['question','answer','category'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE faq SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM faq WHERE id=?');
        return $st->execute([$id]);
    }
}
