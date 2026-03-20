<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\Mongo_Dbodm;

use Doctrine\ODM\Mongo_Db\Aggregation\Builder;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Doctrine MongoDB ODM Aggregation Builder.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Aggregation_Adapter implements Adapter_Interface
{
    public function __construct(private readonly Builder $aggregation_builder)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        $aggregation_builder = clone $this->aggregation_builder;
        return $aggregation_builder->hydrate(null)->count('numResults')->get_aggregation()->getIterator()->to_array()[0]['numResults'] ?? 0;
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        $aggregation_builder = clone $this->aggregation_builder;
        return $aggregation_builder->skip($offset)->limit($length)->get_aggregation()->getIterator();
    }
}