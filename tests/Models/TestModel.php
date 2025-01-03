<?php

namespace Kaemmerlingit\LaravelSignPad\Tests\Models;

use Kaemmerlingit\LaravelSignPad\Concerns\RequiresSignature;
use Kaemmerlingit\LaravelSignPad\Contracts\CanBeSigned;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model implements CanBeSigned
{
    use RequiresSignature;

    protected $guarded = [];
}
