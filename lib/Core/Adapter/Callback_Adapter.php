<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

use Pagerfanta\Exception\Not_Valid_Result_Count_Exception;
/**
 * Adapter which calculates pagination from callable functions.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Callback_Adapter implements Adapter_Interface
{
    /**
     * @var callable(): int<0, max>
     */
    private $nb_results_callable;
    /**
     * @var callable(int<0, max>, int<0, max>): iterable<array-key, T>
     */
    private $slice_callable;
    /**
     * @param callable(): int<0, max>                                                    $nb_results_callable Callable that returns the total number of results (must be >= 0)
     * @param callable(int<0, max> $offset, int<0, max> $length): iterable<array-key, T> $slice_callable     Callable that returns a slice of results given an offset and length
     */
    public function __construct(callable $nb_results_callable, callable $slice_callable)
    {
        $this->nb_results_callable = $nb_results_callable;
        $this->slice_callable = $slice_callable;
    }
    /**
     * Returns the total number of results by invoking the registered count callable.
     *
     * @return int<0, max> Total result count, always >= 0
     *
     * @throws \Pagerfanta\Exception\Not_Valid_Result_Count_Exception If the callable returns a negative number
     *
     * @complexity O(1) for this adapter — delegates entirely to the injected callable
     */
    public function get_nb_results(): int
    {
        $callable = $this->nb_results_callable;
        $count = $callable();
        if ($count < 0) {
            throw new Not_Valid_Result_Count_Exception(\sprintf('The callable to calculate the number of results in "%s()" must return a number greater than or equal to zero.', __METHOD__));
        }
        return $count;
    }
    /**
     * Returns a slice of results by invoking the registered slice callable.
     *
     * @param int<0, max> $offset Zero-based start position within the full result set
     * @param int<0, max> $length Number of items to return
     *
     * @return iterable<array-key, T> Items for the requested page
     *
     * @complexity O(1) for this adapter — delegates entirely to the injected callable
     */
    public function get_slice(int $offset, int $length): iterable
    {
        $callable = $this->slice_callable;
        return $callable($offset, $length);
    }
}