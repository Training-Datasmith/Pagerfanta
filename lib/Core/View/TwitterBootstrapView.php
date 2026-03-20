<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\View\Template\Template_Interface;
use Pagerfanta\View\Template\Twitter_Bootstrap_Template;
class Twitter_Bootstrap_View extends Template_View
{
    protected function create_default_template(): Template_Interface
    {
        return new Twitter_Bootstrap_Template();
    }
    protected function get_default_proximity(): int
    {
        return 3;
    }
    public function get_name(): string
    {
        return 'twitter_bootstrap';
    }
}