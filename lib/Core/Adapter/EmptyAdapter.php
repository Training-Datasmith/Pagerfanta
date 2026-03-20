<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

/**
 * An adapter that is always empty.
 *
 * @template-implements AdapterInterface<never>
 */
class Empty_Adapter implements Adapter_Interface
{
    public function get_nb_results(): int
    {
        return 0;
    }
    public function get_slice(int $offset, int $length): iterable
    {
        return [];
    }
}