<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

class Default_Template extends Template
{
    /**
     * @return array<string, string>
     */
    protected function get_default_options(): array
    {
        return ['prev_message' => 'Previous', 'next_message' => 'Next', 'dots_message' => '&hellip;', 'active_suffix' => '', 'css_active_class' => 'pagination__item--current-page', 'css_container_class' => 'pagination', 'css_disabled_class' => 'pagination__item--disabled', 'css_dots_class' => 'pagination__item--separator', 'css_item_class' => 'pagination__item', 'css_prev_class' => 'pagination__item--previous-page', 'css_next_class' => 'pagination__item--next-page', 'container_template' => '<nav class="%s">%%pages%%</nav>', 'rel_previous' => 'prev', 'rel_next' => 'next', 'page_template' => '<a class="%class%" href="%href%"%rel%>%text%</a>', 'span_template' => '<span class="%class%">%text%</span>'];
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
        $href = $this->generate_route($page);
        $replace = [htmlspecialchars(trim($this->option('css_item_class') . ' ' . $class), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8'), $href, $text];
        $replace[] = $rel ? \sprintf(' rel="%s"', htmlspecialchars($rel, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8')) : '';
        return str_replace(['%class%', '%href%', '%text%', '%rel%'], $replace, $this->option('page_template'));
    }
    public function previous_disabled(): string
    {
        $class = trim(implode(' ', [$this->option('css_item_class'), $this->option('css_prev_class'), $this->option('css_disabled_class')]));
        return $this->generate_span($class, $this->option('prev_message'));
    }
    public function previous_enabled(int $page): string
    {
        return $this->page_with_text_and_class($page, $this->option('prev_message'), $this->option('css_prev_class'), $this->option('rel_previous'));
    }
    public function next_disabled(): string
    {
        $class = trim(implode(' ', [$this->option('css_item_class'), $this->option('css_next_class'), $this->option('css_disabled_class')]));
        return $this->generate_span($class, $this->option('next_message'));
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
        $class = trim(implode(' ', [$this->option('css_item_class'), $this->option('css_active_class')]));
        $text = trim($page . ' ' . $this->option('active_suffix'));
        return $this->generate_span($class, $text);
    }
    public function separator(): string
    {
        $class = trim(implode(' ', [$this->option('css_item_class'), $this->option('css_dots_class')]));
        return $this->generate_span($class, $this->option('dots_message'));
    }
    private function generate_span(string $class, int|string $page): string
    {
        return str_replace(['%class%', '%text%'], [htmlspecialchars($class, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8'), (string) $page], $this->option('span_template'));
    }
}