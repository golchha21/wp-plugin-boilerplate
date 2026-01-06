<?php

namespace PluginBoilerplate\Admin;

final class Target
{
    public const OPTIONS_PAGE = 'options_page';
    public const USER_PROFILE = 'user_profile';

    private string $type;
    private ?string $page;
    private ?string $section;

    private function __construct(string $type, ?string $page = null, ?string $section = null)
    {
        $this->type = $type;
        $this->page = $page;
        $this->section = $section;
    }

    public static function options_page(string $page, string $section = 'default'): self
    {
        if (in_array($page, ['privacy', 'permalink'], true)) {
            throw new \LogicException(
                sprintf(
                    '%s settings page is not supported. ' .
                    'Fields must be rendered and saved manually.',
                    ucfirst($page)
                )
            );
        }

        // Standard Settings API pages
        return new self(self::OPTIONS_PAGE, $page, $section);
    }

    public static function user_profile(): self
    {
        return new self(self::USER_PROFILE);
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_page(): ?string
    {
        return $this->page;
    }

    public function get_section(): ?string
    {
        return $this->section;
    }

    public function is_options_page(): bool
    {
        return $this->type === self::OPTIONS_PAGE;
    }

    public function is_user_profile(): bool
    {
        return $this->type === self::USER_PROFILE;
    }
}
