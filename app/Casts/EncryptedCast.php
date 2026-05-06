<?php

namespace App\Casts;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Custom Eloquent Cast untuk enkripsi kolom menggunakan Defuse PHP-Encryption.
 * Memberikan enkripsi dua-arah yang aman untuk data sensitif seperti NIK.
 */
class EncryptedCast implements CastsAttributes
{
    protected static ?Key $staticKey = null;

    public function __construct()
    {
        if (static::$staticKey === null) {
            $keyString = config('app.encryption_key');
            if ($keyString) {
                try {
                    static::$staticKey = Key::loadFromAsciiSafeString($keyString);
                } catch (\Exception $e) {
                    Log::error('EncryptedCast: Gagal memuat kunci enkripsi. '.$e->getMessage());
                }
            }
        }
    }

    protected function getKey(): ?Key
    {
        return static::$staticKey;
    }

    /**
     * Cast the given value (Decrypt when getting from DB).
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $encryptionKey = $this->getKey();
        if (empty($value) || ! $encryptionKey) {
            return $value;
        }

        try {
            $decrypted = Crypto::decrypt($value, $encryptionKey);

            return (string) $decrypted;
        } catch (\Exception $e) {
            // Jika gagal dekripsi, mungkin data belum terenkripsi (plain text)
            return (string) $value;
        }
    }

    /**
     * Prepare the given value for storage (Encrypt when saving to DB).
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $encryptionKey = $this->getKey();
        if (empty($value) || ! $encryptionKey) {
            return $value;
        }

        try {
            return Crypto::encrypt((string) $value, $encryptionKey);
        } catch (\Exception $e) {
            Log::error("EncryptedCast: Gagal mengenkripsi kolom {$key}. ".$e->getMessage());

            return (string) $value;
        }
    }
}
