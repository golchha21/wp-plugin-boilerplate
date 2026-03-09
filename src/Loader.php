<?php

namespace WPPluginBoilerplate;

class Loader
{
	protected array $hooks = [];

	public function action(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void {
		$this->add('action', $hook, $callback, $priority, $accepted_args);
	}

	public function filter(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void {
		$this->add('filter', $hook, $callback, $priority, $accepted_args);
	}

	protected function add(string $type, string $hook, callable $callback, int $priority, int $accepted_args): void {
		$this->hooks[] = [
			'type' => $type,
			'hook' => $hook,
			'callback' => $callback,
			'priority' => $priority,
			'accepted_args' => $accepted_args,
		];
	}

	public function register(object $component): void
	{
		if (!method_exists($component, 'hooks')) {
			return;
		}

		foreach ($component->hooks() as $type => $hooks) {

			if (!method_exists($this, $type)) {
				continue;
			}

			foreach ($hooks as $hook) {

				[$name, $method, $priority, $args] =
					array_pad($hook, 4, null);

				if (!method_exists($component, $method)) {
					continue;
				}

				$priority ??= 10;
				$args ??= 1;

				$this->$type($name, [$component, $method], $priority, $args);
			}
		}
	}

	public function run(): void
	{
		foreach ($this->hooks as $hook) {

			if ($hook['type'] === 'action') {

				\add_action(
					$hook['hook'],
					$hook['callback'],
					$hook['priority'],
					$hook['accepted_args']
				);

			} else {

				\add_filter(
					$hook['hook'],
					$hook['callback'],
					$hook['priority'],
					$hook['accepted_args']
				);

			}
		}
	}
}
