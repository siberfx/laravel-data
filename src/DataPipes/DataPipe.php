<?php

namespace Spatie\LaravelData\DataPipes;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataClass;

interface DataPipe
{
    public function handle(
        mixed $payload,
        DataClass $class,
        Collection $properties,
        CreationContext $creationContext
    ): Collection;
}
