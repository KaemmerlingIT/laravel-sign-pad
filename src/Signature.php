<?php

namespace Kaemmerlingit\LaravelSignPad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $uuid
 * @property ?string $document_filename
 * @property bool $certified
 * @property string $filename
 * @property string $model_id
 * @property string $model_type
 * @property array $from_ips
 * @property ?string $part
 */
class Signature extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            Storage::disk(config('sign-pad.disk_name'))->delete($model->getSignatureImagePath());
        });
    }

    protected $fillable = [
        'model_type',
        'model_id',
        'uuid',
        'filename',
        'document_filename',
        'from_ips',
        'part'
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'from_ips' => 'array',
    ];

    /**
     * @return MorphTo<Model, Signature>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePart($query, $part)
    {
        return $query->where('part', $part);
    }

    public function getSignatureImagePath(): string
    {
        return config('sign-pad.signatures_path') . '/' . $this->attributes['filename'];
    }

    public function getSignatureImageAbsolutePath(): string
    {
        return Storage::disk(config('sign-pad.disk_name'))->path(config('sign-pad.signatures_path') . '/' . $this->attributes['filename']);
    }

    public function getSignatureImageContent(): string
    {
        $content = Storage::disk(config('sign-pad.disk_name'))->get($this->getSignatureImagePath());
        if (config('sign-pad.encrypt_files') === true) {
            return Crypt::decryptString($content);
        }
        return $content;
    }
}
