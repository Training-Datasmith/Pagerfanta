<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\PHPCRODM;

use Doctrine\ODM\PHPCR\Query\Builder\Query_Builder;
use Doctrine\ODM\PHPCR\Query\Query;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Doctrine PHPCR ODM QueryBuilder.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Query_Adapter implements Adapter_Interface
{
    public function __construct(private readonly Query_Builder $query_builder)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->query_builder->get_query()->execute(null, Query::HYDRATE_PHPCR)->get_rows()->count();
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->query_builder->get_query()->set_max_results($length)->set_first_result($offset)->execute();
    }
}