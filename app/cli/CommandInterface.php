<?php

namespace App\Cli;

interface CommandInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function execute(array $args): int;
}