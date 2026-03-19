<?php

declare(strict_types=1);

namespace Pagerfanta\View\Template;

class TwitterBootstrap4Template extends TwitterBootstrap3Template
{
    /**
     * @param int|string $text
     */
    protected function linkLi(string $class, string $href, $text, ?string $rel = null): string
    {
        $liClass = htmlspecialchars(implode(' ', array_filter(['page-item', $class])), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
        $rel = $rel ? \sprintf(' rel="%s"', htmlspecialchars($rel, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8')) : '';

        return \sprintf('<li class="%s"><a class="page-link" href="%s"%s>%s</a></li>', $liClass, $href, $rel, $text);
    }

    /**
     * @param int|string $text
     */
    protected function spanLi(string $class, $text): string
    {
        $liClass = htmlspecialchars(implode(' ', array_filter(['page-item', $class])), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');

        return \sprintf('<li class="%s"><span class="page-link">%s</span></li>', $liClass, $text);
    }
}
