<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\View\Template\Template_Interface;
use Pagerfanta\View\Template\Twitter_Bootstrap5template;
class Twitter_Bootstrap5view extends Twitter_Bootstrap_View
{
    protected function create_default_template(): Template_Interface
    {
        return new Twitter_Bootstrap5template();
    }
    public function get_name(): string
    {
        return 'twitter_bootstrap5';
    }
}