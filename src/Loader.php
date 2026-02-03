<?php

namespace WPPluginBoilerplate;

class Loader
{
    protected array $actions = [];
    protected array $filters = [];

    public function action(
        string $hook,
        object $component,
        string $callback,
        int $priority = 10,
        int $acceptedArgs = 1
    ): void {
        $this->actions[] = compact(
            'hook',
            'component',
            'callback',
            'priority',
            'acceptedArgs'
        );
    }

    public function filter(
        string $hook,
        object $component,
        string $callback,
        int $priority = 10,
        int $acceptedArgs = 1
    ): void {
        $this->filters[] = compact(
            'hook',
            'component',
            'callback',
            'priority',
            'acceptedArgs'
        );
    }

    public function run(): void
    {
        foreach ($this->actions as $action) {
            add_action(
                $action['hook'],
                [$action['component'], $action['callback']],
                $action['priority'],
                $action['acceptedArgs']
            );
        }

        foreach ($this->filters as $filter) {
            add_filter(
                $filter['hook'],
                [$filter['component'], $filter['callback']],
                $filter['priority'],
                $filter['acceptedArgs']
            );
        }
    }
}
