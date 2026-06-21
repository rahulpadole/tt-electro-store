<?php
declare(strict_types=1);

class OtpModel {
    private PDO $db;

    public function __construct() { $this->db = Database::getConnection(); }

    public function generate(string $phone, string $purpose): string {
        // Clean old OTPs for this phone+purpose
        $this->db->prepare(
            'DELETE FROM otp_verifications WHERE phone=? AND purpose=? AND expires_at < NOW()'
        )->execute([$phone, $purpose]);

        $otp     = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expires = date('Y-m-d H:i:s', time() + 600); // 10 minutes

        $this->db->prepare(
            'INSERT INTO otp_verifications (phone, otp, purpose, expires_at)
             VALUES (?, ?, ?, ?)'
        )->execute([$phone, $otp, $purpose, $expires]);

        return $otp;
    }

    public function verify(string $phone, string $otp, string $purpose): bool {
        $st = $this->db->prepare(
            'SELECT id, attempts FROM otp_verifications
             WHERE phone=? AND purpose=? AND is_verified=0 AND expires_at > NOW()
             ORDER BY created_at DESC LIMIT 1'
        );
        $st->execute([$phone, $purpose]);
        $row = $st->fetch();

        if (!$row) return false;

        // Track attempts (max 5)
        $this->db->prepare(
            'UPDATE otp_verifications SET attempts=attempts+1 WHERE id=?'
        )->execute([$row['id']]);

        if ((int)$row['attempts'] >= 5) {
            $this->db->prepare(
                'DELETE FROM otp_verifications WHERE id=?'
            )->execute([$row['id']]);
            return false;
        }

        // Verify OTP
        $st2 = $this->db->prepare(
            'SELECT id FROM otp_verifications
             WHERE phone=? AND otp=? AND purpose=? AND is_verified=0 AND expires_at > NOW()
             ORDER BY created_at DESC LIMIT 1'
        );
        $st2->execute([$phone, $otp, $purpose]);
        $match = $st2->fetch();

        if (!$match) return false;

        // Mark verified
        $this->db->prepare(
            'UPDATE otp_verifications SET is_verified=1 WHERE id=?'
        )->execute([$match['id']]);

        return true;
    }

    public function isRecentlySent(string $phone, string $purpose, int $cooldownSeconds = 30): bool {
        $st = $this->db->prepare(
            'SELECT COUNT(*) FROM otp_verifications
             WHERE phone=? AND purpose=? AND created_at > ?'
        );
        $since = date('Y-m-d H:i:s', time() - $cooldownSeconds);
        $st->execute([$phone, $purpose, $since]);
        return (int)$st->fetchColumn() > 0;
    }

    public function cleanExpired(): void {
        $this->db->exec('DELETE FROM otp_verifications WHERE expires_at < NOW()');
    }
}
