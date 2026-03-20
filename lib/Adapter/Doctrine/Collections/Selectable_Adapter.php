<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\Collections;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Selectable instance.
 *
 * @template TKey of array-key
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Selectable_Adapter implements Adapter_Interface
{
    /**
     * @param Selectable<TKey, T> $selectable
     */
    public function __construct(private readonly Selectable $selectable, private readonly Criteria $criteria)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->selectable->matching($this->create_criteria(0, null))->count();
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->selectable->matching($this->create_criteria($offset, $length));
    }
    /**
     * @param int<0, max>      $firstResult
     * @param int<0, max>|null $maxResult
     */
    private function create_criteria(int $first_result, ?int $max_result): Criteria
    {
        $criteria = clone $this->criteria;
        $criteria->set_first_result($first_result);
        $criteria->set_max_results($max_result);
        return $criteria;
    }
}