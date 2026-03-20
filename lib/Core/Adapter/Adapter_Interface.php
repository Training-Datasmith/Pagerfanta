<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

use Pagerfanta\Exception\Not_Valid_Result_Count_Exception;
/**
 * @template-covariant T
 */
interface Adapter_Interface
{
    /**
     * Returns the number of results for the list.
     *
     * @return int<0, max>
     *
     * @throws NotValidResultCountException if the number of results is less than zero
     */
    public function get_nb_results(): int;
    /**
     * Returns a slice of the results representing the current page of items in the list.
     *
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable;
}