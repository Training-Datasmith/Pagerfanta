<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\DBAL;

use Doctrine\DBAL\Query\Query_Builder;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Doctrine DBAL QueryBuilder.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Query_Adapter implements Adapter_Interface
{
    private readonly Query_Builder $query_builder;
    /**
     * @var callable(QueryBuilder): (QueryBuilder|void)
     */
    private $count_query_builder_modifier;
    /**
     * @param callable(QueryBuilder): (QueryBuilder|void) $countQueryBuilderModifier
     */
    public function __construct(Query_Builder $query_builder, callable $count_query_builder_modifier)
    {
        $this->query_builder = clone $query_builder;
        $this->count_query_builder_modifier = $count_query_builder_modifier;
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        $qb = $this->prepare_count_query_builder();
        return (int) $qb->execute_query()->fetch_one();
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        $qb = clone $this->query_builder;
        return $qb->set_max_results($length)->set_first_result($offset)->execute_query()->fetch_all_associative();
    }
    private function prepare_count_query_builder(): Query_Builder
    {
        $qb = clone $this->query_builder;
        $callable = $this->count_query_builder_modifier;
        $new_qb = $callable($qb);
        if ($new_qb instanceof Query_Builder) {
            return $new_qb;
        }
        trigger_deprecation('pagerfanta/doctrine-dbal-adapter', '4.6', 'Not returning a "%s" from the query builder modifier in "%s" is deprecated. In 5.0, returning a query builder object will be required.');
        return $qb;
    }
}