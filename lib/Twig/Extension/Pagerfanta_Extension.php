<?php

declare (strict_types=1);
namespace Pagerfanta\Twig\Extension;

use Twig\Extension\Abstract_Extension;
use Twig\Twig_Function;
final class Pagerfanta_Extension extends Abstract_Extension
{
    /**
     * @return list<TwigFunction>
     */
    public function get_functions(): array
    {
        return [new Twig_Function('pagerfanta', [Pagerfanta_Runtime::class, 'renderPagerfanta'], ['is_safe' => ['html']]), new Twig_Function('pagerfanta_page_url', [Pagerfanta_Runtime::class, 'getPageUrl'])];
    }
}