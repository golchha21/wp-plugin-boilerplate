<?php

namespace PluginBoilerplate\Admin\Fields;

class RawHtml extends Field
{
    public function render(): void
    {
        if (is_callable($this->args)) {
            call_user_func($this->args);
        }
    }

    public function sanitize($value)
    {
        return null;
    }
}
