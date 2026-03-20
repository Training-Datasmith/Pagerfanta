<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\View\Template\Semantic_Ui_Template;
use Pagerfanta\View\Template\Template_Interface;
class Semantic_Ui_View extends Template_View
{
    protected function create_default_template(): Template_Interface
    {
        return new Semantic_Ui_Template();
    }
    protected function get_default_proximity(): int
    {
        return 3;
    }
    public function get_name(): string
    {
        return 'semantic_ui';
    }
}