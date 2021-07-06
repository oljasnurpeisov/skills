<?php

namespace App\Models;

use App\Libraries\Kalkan\Certificate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DocumentSignature
 * @package App\Models
 *
 * @property int $id
 * @property string $sign
 * @property string $hash
 * @property string $data
 * @property string $cert
 * @property string $result
 * @property int $document_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Document $document
 * @property User $user
 */
class DocumentSignature extends Model
{
    use HasFactory;

    /**
     * Get docment
     * @return BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
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
     * Parse public key
     * @return Certificate
     */
    public function getCertificate(): Certificate
    {
        return new Certificate($this->cert);
    }
}
