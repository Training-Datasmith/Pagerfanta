<?php

declare (strict_types=1);
namespace Pagerfanta\Elastica;

use Elastica\Query;
use Elastica\Result;
use Elastica\Result_Set;
use Elastica\Searchable_Interface;
use Pagerfanta\Adapter\Adapter_Interface;
use Pagerfanta\Exception\Not_Valid_Result_Count_Exception;
/**
 * Adapter which calculates pagination from an Elastica Query.
 *
 * @implements AdapterInterface<Result>
 */
class Elastica_Adapter implements Adapter_Interface
{
    /**
     * @var int<0, max>|null
     */
    private readonly ?int $max_results;
    private ?Result_Set $result_set = null;
    /**
     * @param array<string, mixed> $options
     * @param int|null             $maxResults Limit the number of totalHits returned by ElasticSearch; see https://github.com/whiteoctober/Pagerfanta/pull/213#issue-87631892
     *
     * @throws NotValidResultCountException if the maximum number of results is less than zero
     */
    public function __construct(private readonly Searchable_Interface $searchable, private readonly Query $query, private readonly array $options = [], ?int $max_results = null)
    {
        if (null !== $max_results && $max_results < 0) {
            throw new Not_Valid_Result_Count_Exception(\sprintf('The maximum number of results for the "%s" constructor must be at least zero.', static::class));
        }
        $this->max_results = $max_results;
    }
    /**
     * Returns the Elastica ResultSet.
     *
     * Will return null if getSlice has not yet been called.
     */
    public function get_result_set(): ?Result_Set
    {
        return $this->result_set;
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        $total_hits = $this->result_set instanceof Result_Set ? $this->result_set->get_total_hits() : $this->searchable->count($this->query);
        if (null === $this->max_results) {
            return $total_hits;
        }
        return min($total_hits, $this->max_results);
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<int, Result>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->result_set = $this->searchable->search($this->query, array_merge($this->options, ['from' => $offset, 'size' => $length]));
    }
}