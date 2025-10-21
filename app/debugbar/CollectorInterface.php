<?php

namespace App\DebugBar;

interface CollectorInterface
{
    public function getName(): string;
    public function collect(): array;
}