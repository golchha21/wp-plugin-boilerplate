<?php

namespace PluginBoilerplate\Admin;

final class Tab
{
    public string $id;
    public string $label;
    protected bool $is_form_tab;

    public function __construct(string $id, string $label, bool $is_form_tab = true)
    {
        $this->id = $id;
        $this->label = $label;
        $this->is_form_tab = $is_form_tab;
    }

    public function is_form_tab(): bool
    {
        return $this->is_form_tab;
    }
}
