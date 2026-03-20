<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

class Semantic_Ui_Template extends Template
{
    /**
     * @return array<string, string>
     */
    protected function get_default_options(): array
    {
        return ['prev_message' => 'Previous', 'next_message' => 'Next', 'dots_message' => '&hellip;', 'active_suffix' => '', 'css_active_class' => 'active', 'css_container_class' => 'ui pagination menu', 'css_disabled_class' => 'disabled', 'css_dots_class' => 'disabled', 'css_item_class' => 'item', 'css_prev_class' => '', 'css_next_class' => '', 'container_template' => '<div class="%s">%%pages%%</div>', 'rel_previous' => 'prev', 'rel_next' => 'next'];
    }
    public function container(): string
    {
        return \sprintf($this->option('container_template'), $this->option('css_container_class'));
    }
    public function page(int $page): string
    {
        return $this->page_with_text($page, (string) $page);
    }
    public function page_with_text(int $page, string $text, ?string $rel = null): string
    {
        return $this->page_with_text_and_class($page, $text, '', $rel);
    }
    private function page_with_text_and_class(int $page, string $text, string $class, ?string $rel = null): string
    {
        return $this->link($class, $this->generate_route($page), $text, $rel);
    }
    public function previous_disabled(): string
    {
        return $this->div($this->previous_disabled_class(), $this->option('prev_message'));
    }
    private function previous_disabled_class(): string
    {
        return $this->option('css_prev_class') . ' ' . $this->option('css_disabled_class');
    }
    public function previous_enabled(int $page): string
    {
        return $this->page_with_text_and_class($page, $this->option('prev_message'), $this->option('css_prev_class'), $this->option('rel_previous'));
    }
    public function next_disabled(): string
    {
        return $this->div($this->next_disabled_class(), $this->option('next_message'));
    }
    private function next_disabled_class(): string
    {
        return $this->option('css_next_class') . ' ' . $this->option('css_disabled_class');
    }
    public function next_enabled(int $page): string
    {
        return $this->page_with_text_and_class($page, $this->option('next_message'), $this->option('css_next_class'), $this->option('rel_next'));
    }
    public function first(): string
    {
        return $this->page(1);
    }
    public function last(int $page): string
    {
        return $this->page($page);
    }
    public function current(int $page): string
    {
        $text = trim($page . ' ' . $this->option('active_suffix'));
        return $this->div($this->option('css_active_class'), $text);
    }
    public function separator(): string
    {
        return $this->div($this->option('css_dots_class'), $this->option('dots_message'));
    }
    private function link(string $class, string $href, int|string $text, ?string $rel = null): string
    {
        $rel = $rel ? \sprintf(' rel="%s"', htmlspecialchars($rel, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8')) : '';
        $combined_class = htmlspecialchars($this->option('css_item_class') . ' ' . $class, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
        return \sprintf('<a class="%s" href="%s"%s>%s</a>', $combined_class, $href, $rel, $text);
    }
    private function div(string $class, int|string $text): string
    {
        $combined_class = htmlspecialchars($this->option('css_item_class') . ' ' . $class, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
        return \sprintf('<div class="%s">%s</div>', $combined_class, $text);
    }
}