<?php

namespace WPPluginBoilerplate\Admin\Modules;

use WPPluginBoilerplate\Admin\Contracts\AdminModule;
use WPPluginBoilerplate\Core\Fields\FieldDefinition;
use WPPluginBoilerplate\Core\Fields\Types\RepeaterField;
use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\PostMeta\MetaBoxes;
use WPPluginBoilerplate\PostMeta\PostMetaRepository;
use WPPluginBoilerplate\PostMeta\Contracts\MetaBoxContract;
use WPPluginBoilerplate\Core\Fields\FieldFactory;
use WPPluginBoilerplate\Core\Fields\FieldRenderer;

class MetaBoxModule implements AdminModule
{
	public function register(Loader $loader): void
	{
		$loader->action('add_meta_boxes', $this, 'registerMetaBoxes');
		$loader->action('save_post', $this, 'save', 10, 2);
	}

	public function registerMetaBoxes(): void
	{
		foreach (MetaBoxes::all() as $box) {

			foreach ($box->postTypes() as $postType) {

				\add_meta_box(
					$box->id(),
					$box->title(),
					fn($post) => $this->render($box, $post->ID),
					$postType,
					$box->context(),
					$box->priority()
				);
			}
		}
	}

	private function render(MetaBoxContract $box, int $postId): void
	{
		\wp_nonce_field($box->id(), $box->id() . '_nonce');

		echo '<div class="wppb-admin">';
		$tabs = $box->tabs();

		if (!empty($tabs)) {
			$this->renderTabs($tabs, $postId);
			return;
		}
		$this->renderFields($box->fields(), $postId);
		echo '</div>'; // admin wrapper
	}

	private function renderTabs(array $tabs, int $postId): void
	{
		$instanceId = uniqid('wppb_meta_');

		echo '<div class="wppb-meta-tabs" data-instance="' . esc_attr($instanceId) . '">';

		// Navigation
		echo '<div class="wppb-meta-tabs-nav">';

		foreach ($tabs as $index => $tab) {

			$active = $index === 0 ? 'active' : '';
			$panelId = $instanceId . '_' . $tab->id();

			echo '<button
                type="button"
                class="wppb-meta-tab ' . esc_attr($active) . '"
                data-target="' . esc_attr($panelId) . '"
              >';

			echo esc_html($tab->label());

			echo '</button>';
		}

		echo '</div>';

		// Content
		echo '<div class="wppb-meta-tabs-content">';

		foreach ($tabs as $index => $tab) {

			$active = $index === 0 ? 'active' : '';
			$panelId = $instanceId . '_' . $tab->id();

			echo '<div
                class="wppb-meta-tab-panel ' . esc_attr($active) . '"
                id="' . esc_attr($panelId) . '"
              >';

			$this->renderFields($tab->fields(), $postId);

			echo '</div>';
		}

		echo '</div>'; // content
		echo '</div>'; // tabs
	}

	private function renderFields(array $fields, int $postId): void
	{
		foreach ($fields as $key => $definition) {

			$metaKey = $this->prefixed($key);

			$value = PostMetaRepository::get($postId, $metaKey);

			$field = FieldDefinition::fromSchema($metaKey, $definition);

			echo '<div class="wppb-meta-field">';

			// Label column (2 cols)
			if (!empty($field->label)) {
				echo '<div class="wppb-label width-2">';
				echo '<label for="' . esc_attr($metaKey) . '"><strong>';
				echo esc_html($field->label);
				echo '</strong></label>';
				echo '</div>';
			}

			// Field column (10 cols)
			FieldRenderer::render(null, $field, $value);

			echo '</div>';
		}
	}

	public function save(int $postId): void
	{
		if (\defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (\wp_is_post_revision($postId)) {
			return;
		}

		foreach (MetaBoxes::all() as $box) {

			if (!\in_array(\get_post_type($postId), $box->postTypes(), true)) {
				continue;
			}

			if (!isset($_POST[$box->id() . '_nonce'])) {
				continue;
			}

			if (!\wp_verify_nonce($_POST[$box->id() . '_nonce'], $box->id())) {
				continue;
			}

			if (!\current_user_can($box->capability(), $postId)) {
				continue;
			}

			$tabs = $box->tabs();

			if (!empty($tabs)) {
				foreach ($tabs as $tab) {
					$this->saveFields($tab->fields(), $postId);
				}
			} else {
				$this->saveFields($box->fields(), $postId);
			}
		}
	}

	private function saveFields(array $fields, int $postId): void
	{
		foreach ($fields as $key => $definition) {

			$metaKey = $this->prefixed($key);
			$fieldType = $definition['field'] ?? 'text';

			// Checkbox
			if ($fieldType === 'checkbox') {
				$value = isset($_POST[$metaKey]) ? 1 : 0;
				PostMetaRepository::update($postId, $metaKey, $value);
				continue;
			}

			// Repeater
			if ($fieldType === 'repeater') {

				$raw = $_POST[$metaKey] ?? [];

				$repeater = new RepeaterField($metaKey, $definition, $raw);

				$value = $repeater->sanitize($raw);

				PostMetaRepository::update($postId, $metaKey, $value);
				continue;
			}

			// Scalar fields
			if (!array_key_exists($metaKey, $_POST)) {
				PostMetaRepository::delete($postId, $metaKey);
				continue;
			}

			$value = $_POST[$metaKey];

			PostMetaRepository::update($postId, $metaKey, $value);
		}
	}

	private function prefixed(string $key): string
	{
		return '_' . WPPB_PREFIX . $key;
	}
}
