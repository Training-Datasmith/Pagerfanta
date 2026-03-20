<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

class Foundation6Template extends Template
{
    /**
     * @return array<string, string>
     */
    protected function get_default_options(): array
    {
        return ['prev_message' => 'Previous', 'next_message' => 'Next', 'dots_message' => '', 'active_suffix' => '', 'css_active_class' => 'current', 'css_container_class' => 'pagination', 'css_disabled_class' => 'disabled', 'css_dots_class' => 'ellipsis', 'css_item_class' => '', 'css_prev_class' => 'pagination-previous', 'css_next_class' => 'pagination-next', 'container_template' => '<nav aria-label="Pagination"><ul class="%s">%%pages%%</ul></nav>', 'rel_previous' => 'prev', 'rel_next' => 'next'];
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
        return $this->link_li($class, $this->generate_route($page), $text, $rel);
    }
    public function previous_disabled(): string
    {
        return $this->li($this->previous_disabled_class(), $this->option('prev_message'));
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
        return $this->li($this->next_disabled_class(), $this->option('next_message'));
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
        return $this->li($this->option('css_active_class'), $text);
    }
    public function separator(): string
    {
        $li_class = \sprintf(' class="%s"', trim($this->option('css_item_class') . ' ' . $this->option('css_dots_class')));
        return \sprintf('<li aria-hidden="true"%s>%s</li>', $li_class, $this->option('dots_message'));
    }
    protected function li(string $class, int|string $text): string
    {
        $li_class = \sprintf(' class="%s"', htmlspecialchars(trim($this->option('css_item_class') . ' ' . $class), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8'));
        return \sprintf('<li%s>%s</li>', $li_class, $text);
    }
    protected function link_li(string $class, string $href, int|string $text, ?string $rel = null): string
    {
        $class = trim($this->option('css_item_class') . ' ' . $class);
        $li_class = '' === $class ? '' : \sprintf(' class="%s"', htmlspecialchars($class, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8'));
        $item_rel = $rel ? \sprintf(' rel="%s"', htmlspecialchars($rel, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8')) : '';
        return \sprintf('<li%s><a href="%s"%s>%s</a></li>', $li_class, $href, $item_rel, $text);
    }
}