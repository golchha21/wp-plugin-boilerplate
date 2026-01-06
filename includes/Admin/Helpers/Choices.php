<?php

namespace PluginBoilerplate\Admin\Helpers;

class Choices
{
    /**
     * Public post types (excluding attachment by default)
     */
    public static function post_types(array $args = []): array
    {
        $defaults = [
            'show_ui' => true,
            'public' => true,
        ];

        $args = array_merge($defaults, $args);

        $exclude = ['attachment'];

        $objects = get_post_types($args, 'objects');
        $choices = [];

        foreach ($objects as $pt) {
            if (in_array($pt->name, $exclude, true)) {
                continue;
            }

            $choices[$pt->name] = $pt->labels->singular_name;
        }

        return $choices;
    }

    /**
     * Taxonomies
     */
    public static function taxonomies(array $args = []): array
    {
        $defaults = [
            'show_ui' => true,
            'public' => true,
        ];

        $args = array_merge($defaults, $args);

        $objects = get_taxonomies($args, 'objects');
        $choices = [];

        foreach ($objects as $tax) {
            $choices[$tax->name] = $tax->labels->singular_name;
        }

        return $choices;
    }

    /**
     * User roles
     */
    public static function roles(): array
    {
        $wp_roles = wp_roles();

        if (!$wp_roles) {
            return [];
        }

        return $wp_roles->get_names();
    }

    /**
     * Users
     */
    public static function users(array $args = []): array
    {
        $defaults = [
            'fields' => ['ID', 'display_name'],
        ];

        $users = get_users(array_merge($defaults, $args));
        $choices = [];

        foreach ($users as $user) {
            $choices[(string)$user->ID] = $user->display_name;
        }

        return $choices;
    }
}
