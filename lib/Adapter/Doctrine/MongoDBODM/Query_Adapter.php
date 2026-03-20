<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\Mongo_Dbodm;

use Doctrine\ODM\Mongo_Db\Query\Builder;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Doctrine MongoDB ODM QueryBuilder.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Query_Adapter implements Adapter_Interface
{
    public function __construct(private readonly Builder $query_builder)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        $qb = clone $this->query_builder;
        return $qb->limit(0)->skip(0)->count()->get_query()->execute();
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->query_builder->limit($length)->skip($offset)->get_query()->execute();
    }
}