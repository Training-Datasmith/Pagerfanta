<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\View\Template\Foundation6Template;
use Pagerfanta\View\Template\Template_Interface;
class Foundation6View extends Template_View
{
    protected function create_default_template(): Template_Interface
    {
        return new Foundation6Template();
    }
    protected function get_default_proximity(): int
    {
        return 3;
    }
    public function get_name(): string
    {
        return 'foundation6';
    }
}