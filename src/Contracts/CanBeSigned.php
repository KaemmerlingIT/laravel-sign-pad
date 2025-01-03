<?php

namespace Kaemmerlingit\LaravelSignPad\Contracts;

interface CanBeSigned
{
    public function getSignatureRoute(): string;

    public function hasBeenSigned(?string $part): bool;
}
