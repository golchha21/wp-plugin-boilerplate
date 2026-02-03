<?php

namespace WPPluginBoilerplate\Support\Settings\Contracts;

interface TabContract
{
    public function id(): string;
    public function label(): string;

    // Capability flags
    public function hasForm(): bool;
    public function hasActions(): bool;

    // Render content only (never form or buttons)
    public function render(): void;
}
