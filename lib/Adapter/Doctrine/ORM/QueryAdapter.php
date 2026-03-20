<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\ORM;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query_Builder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Doctrine ORM Query or QueryBuilder.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Query_Adapter implements Adapter_Interface
{
    /**
     * @var Paginator<T>
     */
    private readonly Paginator $paginator;
    /**
     * @param bool      $fetchJoinCollection Whether the query joins a collection (true by default)
     * @param bool|null $useOutputWalkers    Flag indicating whether output walkers are used in the paginator
     */
    public function __construct(Query|Query_Builder $query, bool $fetch_join_collection = true, ?bool $use_output_walkers = null)
    {
        $this->paginator = new Paginator($query, $fetch_join_collection);
        $this->paginator->set_use_output_walkers($use_output_walkers);
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return \count($this->paginator);
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return \Traversable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        $this->paginator->get_query()->set_first_result($offset)->set_max_results($length);
        return $this->paginator->getIterator();
    }
}