<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

class Twitter_Bootstrap4template extends Twitter_Bootstrap3template
{
    /**
     * @param int|string $text
     */
    protected function link_li(string $class, string $href, $text, ?string $rel = null): string
    {
        $li_class = htmlspecialchars(implode(' ', array_filter(['page-item', $class])), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
        $rel = $rel ? \sprintf(' rel="%s"', htmlspecialchars($rel, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8')) : '';
        return \sprintf('<li class="%s"><a class="page-link" href="%s"%s>%s</a></li>', $li_class, $href, $rel, $text);
    }
    /**
     * @param int|string $text
     */
    protected function span_li(string $class, $text): string
    {
        $li_class = htmlspecialchars(implode(' ', array_filter(['page-item', $class])), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
        return \sprintf('<li class="%s"><span class="page-link">%s</span></li>', $li_class, $text);
    }
}