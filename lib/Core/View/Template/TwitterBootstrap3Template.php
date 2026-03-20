<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

class Twitter_Bootstrap3template extends Twitter_Bootstrap_Template
{
    /**
     * @return array<string, string>
     */
    protected function get_default_options(): array
    {
        return [...parent::get_default_options(), ...['active_suffix' => '<span class="sr-only">(current)</span>', 'container_template' => '<ul class="%s">%%pages%%</ul>']];
    }
}