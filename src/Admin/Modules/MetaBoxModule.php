<?php

namespace WPPluginBoilerplate\Admin\Modules;

use WPPluginBoilerplate\Admin\Contracts\AdminModule;
use WPPluginBoilerplate\Core\Fields\FieldDefinition;
use WPPluginBoilerplate\Core\Fields\Types\RepeaterField;
use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\MetaBox\MetaBoxes;
use WPPluginBoilerplate\MetaBox\MetaBoxRepository;
use WPPluginBoilerplate\MetaBox\Contracts\MetaBoxContract;
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
		global $post;

		foreach (MetaBoxes::all() as $box) {

			foreach ($box->postTypes() as $postType) {

				// If we're editing a post, check template before registering
				if ($post instanceof \WP_Post) {
					if (!$this->templateMatches($box, $post->ID)) {
						continue; // skip registration entirely
					}
				}

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
			$this->renderTabs($box, $tabs, $postId);
		} else {
			$this->renderFields($box, $box->fields(), $postId);
		}

		echo '</div>'; // always close wrapper
	}

	private function renderTabs(MetaBoxContract $box, array $tabs, int $postId): void
	{
		$instanceId = uniqid('wppb_meta_');

		echo '<div class="wppb-meta-tabs" data-instance="' . \esc_attr($instanceId) . '">';

		// Navigation
		echo '<div class="wppb-meta-tabs-nav">';

		foreach ($tabs as $index => $tab) {

			$active = $index === 0 ? 'active' : '';
			$panelId = $instanceId . '_' . $tab->id();

			echo '<button
                type="button"
                class="wppb-meta-tab ' . \esc_attr($active) . '"
                data-target="' . \esc_attr($panelId) . '"
              >';

			echo \esc_html($tab->label());

			echo '</button>';
		}

		echo '</div>';

		// Content
		echo '<div class="wppb-meta-tabs-content">';

		foreach ($tabs as $index => $tab) {

			$active = $index === 0 ? 'active' : '';
			$panelId = $instanceId . '_' . $tab->id();

			echo '<div
                class="wppb-meta-tab-panel ' . \esc_attr($active) . '"
                id="' . \esc_attr($panelId) . '"
              >';

			$this->renderFields($box, $tab->fields(), $postId);

			echo '</div>';
		}

		echo '</div>'; // content
		echo '</div>'; // tabs
	}

	private function renderFields(MetaBoxContract $box, array $fields, int $postId): void
	{
		foreach ($fields as $key => $definition) {

			$metaKey = $this->prefixed($box, $key);

			$value = MetaBoxRepository::get($postId, $box->id(), $key);

			$field = FieldDefinition::fromSchema($key, $definition);

			echo '<div class="wppb-meta-field ' . \esc_attr($field->field) .  '">';

			// Label column (2 cols)
			if (!empty($field->label)) {
				echo '<div class="wppb-label width-2">';
				echo '<label for="' . \esc_attr($metaKey) . '"><strong>';
				echo \esc_html($field->label);
				echo '</strong></label>';
				echo '</div>';
			}

			$metaPrefix = '_' . WPPB_PREFIX . $box->id();
			FieldRenderer::render($metaPrefix, $field, $value, 'meta');

			echo '</div>';
		}
	}

	public function save(int $postId): void
	{
		if (\defined('DOING_AUTOSAVE') && \DOING_AUTOSAVE) {
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
					$this->saveFields($box, $tab->fields(), $postId);
				}
			} else {
				$this->saveFields($box, $box->fields(), $postId);
			}
		}
	}

	private function saveFields(MetaBoxContract $box, array $fields, int $postId): void
	{
		foreach ($fields as $key => $definition) {

			$metaKey = $this->prefixed($box, $key);
			$fieldType = $definition['field'] ?? 'text';

			// Checkbox
			if ($fieldType === 'checkbox') {
				$value = isset($_POST[$metaKey]) ? 1 : 0;
				MetaBoxRepository::update($postId, $box->id(), $key, $value);
				continue;
			}

			// Repeater
			if ($fieldType === 'repeater') {

				$raw = $_POST[$metaKey] ?? [];

				$repeater = new RepeaterField($metaKey, $definition, $raw);

				$value = $repeater->sanitize($raw);

				MetaBoxRepository::update($postId, $box->id(), $key, $value);
				continue;
			}

			// Scalar fields
			if (!array_key_exists($metaKey, $_POST)) {
				MetaBoxRepository::delete($postId, $box->id(), $key);
				continue;
			}

			$value = $_POST[$metaKey];

			MetaBoxRepository::update($postId, $box->id(), $key, $value);
		}
	}

	private function prefixed(MetaBoxContract $box, string $key): string
	{
		return '_' . WPPB_PREFIX . $box->id() . '_' . $key;
	}

	private function templateMatches(MetaBoxContract $box, int $postId): bool
	{
		$templates = $box->templates();

		if (empty($templates)) {
			return true;
		}

		// 1️⃣ Classic theme template
		$classic = get_post_meta($postId, '_wp_page_template', true);

		if ($classic && in_array($classic, $templates, true)) {
			return true;
		}

		// 2️⃣ Block theme template
		$block = get_post_meta($postId, '_wp_template', true);

		if ($block) {
			// Extract template slug from "theme//template-slug"
			if (str_contains($block, '//')) {
				[, $slug] = explode('//', $block, 2);
			} else {
				$slug = $block;
			}

			if (in_array($slug, $templates, true)) {
				return true;
			}
		}

		return false;
	}
}
