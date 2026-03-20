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
     * @param callable(): int<0, max>                                                    $nbResultsCallable
     * @param callable(int<0, max> $offset, int<0, max> $length): iterable<array-key, T> $sliceCallable
     */
    public function __construct(callable $nb_results_callable, callable $slice_callable)
    {
        $this->nb_results_callable = $nb_results_callable;
        $this->slice_callable = $slice_callable;
    }
    /**
     * @return int<0, max>
     *
     * @throws NotValidResultCountException if the number of results is less than zero
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
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        $callable = $this->slice_callable;
        return $callable($offset, $length);
    }
}