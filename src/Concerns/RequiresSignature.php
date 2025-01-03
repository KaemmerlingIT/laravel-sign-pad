<?php

namespace Kaemmerlingit\LaravelSignPad\Concerns;

use Kaemmerlingit\LaravelSignPad\Signature;

trait RequiresSignature
{
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'model');
    }

    public function getSignatureRoute(): string
    {
        return route('sign-pad::signature', [
            'model' => get_class($this),
            'id' => $this->id,
            'token' => md5(config('app.key') . get_class($this)),
        ]);
    }

    public function hasBeenSigned(?string $part): bool
    {
        return $this->signatures()->part($part)->count() > 0;
    }

    public function deleteSignaturesInPart(?string $part): bool
    {
        return $this->signatures()->part($part)->delete();
    }
}
