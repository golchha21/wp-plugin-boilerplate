<?php

namespace WPPluginBoilerplate\Settings;

final class SettingsRepository
{
	public static function get(string $optionKey, string $scope = 'site'): array
	{
		$value = is_multisite() && $scope === 'network'
			? \get_site_option($optionKey, [])
			: \get_option($optionKey, []);

		return is_array($value) ? $value : [];
	}

	public static function update(string $optionKey, array $values, string $scope = 'site'): void
	{
		if (is_multisite() && $scope === 'network') {
			\update_site_option($optionKey, $values);
			return;
		}

		\update_option($optionKey, $values);
	}

	public static function delete(string $optionKey, string $scope = 'site'): void
	{
		if (is_multisite() && $scope === 'network') {
			\delete_site_option($optionKey);
			return;
		}

		\delete_option($optionKey);
	}

	public static function getValue(string $optionKey, string $valueKey, string $scope = 'site', mixed $default = null): mixed {
		$values = self::get($optionKey, $scope);

		return $values[$valueKey] ?? $default;
	}

	public static function setValue(string $optionKey, string $valueKey, mixed $value, string $scope = 'site'): void {
		$values = self::get($optionKey, $scope);

		$values[$valueKey] = $value;

		self::update($optionKey, $values, $scope);
	}

	public static function deleteValue(string $optionKey, string $valueKey, string $scope = 'site'): void {
		$values = self::get($optionKey, $scope);

		if (!array_key_exists($valueKey, $values)) {
			return;
		}

		unset($values[$valueKey]);

		self::update($optionKey, $values, $scope);
	}


}
