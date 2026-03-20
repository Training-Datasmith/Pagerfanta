<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\DBAL;

use Doctrine\DBAL\Query\Query_Builder;
use Pagerfanta\Exception\InvalidArgumentException;
/**
 * Extended Doctrine DBAL adapter which assists in building the count query modifier for a SELECT query on a single table.
 *
 * @template T
 *
 * @extends QueryAdapter<T>
 */
class Single_Table_Query_Adapter extends Query_Adapter
{
    /**
     * @param string $countField Primary key for the table in query, used in the count expression. Must include table alias.
     *
     * @throws InvalidArgumentException if the count field does not have a table alias
     */
    public function __construct(Query_Builder $query_builder, string $count_field)
    {
        parent::__construct($query_builder, $this->create_count_query_modifier($count_field));
    }
    private function create_count_query_modifier(string $count_field): \Closure
    {
        $select = $this->create_select_for_count_field($count_field);
        return static function (Query_Builder $query_builder) use ($select): Query_Builder {
            $query_builder->select($select);
            // @phpstan-ignore-next-line function.alreadyNarrowedType
            if (method_exists($query_builder, 'resetOrderBy')) {
                $query_builder->reset_order_by();
            } else {
                $query_builder->reset_query_part('orderBy');
            }
            $query_builder->set_max_results(1);
            return $query_builder;
        };
    }
    /**
     * @throws InvalidArgumentException if the count field does not have a table alias
     */
    private function create_select_for_count_field(string $count_field): string
    {
        if ($this->count_field_has_no_alias($count_field)) {
            throw new InvalidArgumentException('The $countField must contain a table alias in the string.');
        }
        return \sprintf('COUNT(DISTINCT %s) AS total_results', $count_field);
    }
    private function count_field_has_no_alias(string $count_field): bool
    {
        return !str_contains($count_field, '.');
    }
}