<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Document
 * @package App\Models
 *
 * @property int $id
 * @property string $number
 * @property string $content
 * @property int $type_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DocumentType $type
 * @property User $user
 * @property DocumentSignature[] $signatures
 * @property DocumentSignature $lastSignature
 */
class Document extends Model
{
    use HasFactory;

    /**
     * Get user
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'type_id');
    }

    /**
     * Get user
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get signatures
     * @return HasMany
     */
    public function signatures(): HasMany
    {
        return $this->hasMany(DocumentSignature::class, 'document_id');
    }

    /**
     * Get latest signature
     * @return HasOne
     */
    public function lastSignature(): HasOne
    {
        return $this->hasOne(DocumentSignature::class, 'document_id', 'id')
            ->orderBy('created_at', 'DESC');
    }

    /**
     * Generate unique document number
     *
     * @param int $type
     * @return string
     * @throws \Exception
     */
    public static function generateNumber(int $type = 1): ?string
    {
        $prefix = 'KZENBKSKLS';
        $format = '%s%d%s%s';

        switch ($type) {
            case 2:
                $typePrefix = 'A';
                break;
            default:
                $typePrefix = 'C';
                break;
        }

        try {

            $random = strtoupper(bin2hex(random_bytes(10)));
            $number = sprintf($format, $prefix, date('Y'), $typePrefix, $random);

            if (Document::where('number', $number)->exists()) {
                return self::generateNumber($type);
            }

            return $number;

        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::error('Random generation fails: ' . $exception->getMessage());
        }

        return null;
    }
}
