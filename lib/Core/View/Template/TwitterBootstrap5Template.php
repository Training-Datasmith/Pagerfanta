<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

class Twitter_Bootstrap5template extends Twitter_Bootstrap4template
{
    /**
     * @return array<string, string>
     */
    protected function get_default_options(): array
    {
        return [...parent::get_default_options(), ...['active_suffix' => '<span class="visually-hidden">(current)</span>']];
    }
}