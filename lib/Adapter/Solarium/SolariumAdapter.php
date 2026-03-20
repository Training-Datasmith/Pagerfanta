<?php

declare (strict_types=1);
namespace Pagerfanta\Solarium;

use Pagerfanta\Adapter\Adapter_Interface;
use Solarium\Core\Client\Client_Interface;
use Solarium\Core\Client\Endpoint;
use Solarium\Core\Query\Document_Interface;
use Solarium\Query_Type\Select\Query\Query;
use Solarium\Query_Type\Select\Result\Result;
/**
 * Adapter which calculates pagination from a Solarium Query.
 *
 * @implements AdapterInterface<DocumentInterface>
 */
class Solarium_Adapter implements Adapter_Interface
{
    private ?Result $result_set = null;
    private Endpoint|string|null $endpoint = null;
    /**
     * @var int<0, max>|null
     */
    private ?int $result_set_start = null;
    /**
     * @var int<0, max>|null
     */
    private ?int $result_set_rows = null;
    public function __construct(private readonly Client_Interface $client, private readonly Query $query)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->get_result_set()->get_num_found();
    }
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->get_result_set($offset, $length);
    }
    /**
     * @param int<0, max>|null $start
     * @param int<0, max>|null $rows
     */
    public function get_result_set(?int $start = null, ?int $rows = null): Result
    {
        if ($this->result_set_start_and_rows_are_not_null_and_change($start, $rows)) {
            $this->result_set_start = $start;
            $this->result_set_rows = $rows;
            $this->modify_query();
            $this->result_set = null;
        }
        if (!$this->result_set instanceof Result) {
            $this->result_set = $this->create_result_set();
        }
        return $this->result_set;
    }
    /**
     * @param int<0, max>|null $start
     * @param int<0, max>|null $rows
     *
     * @phpstan-assert-if-true int<0, max> $start
     * @phpstan-assert-if-true int<0, max> $rows
     */
    private function result_set_start_and_rows_are_not_null_and_change(?int $start, ?int $rows): bool
    {
        return $this->result_set_start_and_rows_are_not_null($start, $rows) && $this->result_set_start_and_rows_change($start, $rows);
    }
    /**
     * @param int<0, max>|null $start
     * @param int<0, max>|null $rows
     *
     * @phpstan-assert-if-true int<0, max> $start
     * @phpstan-assert-if-true int<0, max> $rows
     */
    private function result_set_start_and_rows_are_not_null(?int $start, ?int $rows): bool
    {
        return null !== $start && null !== $rows;
    }
    /**
     * @param int<0, max> $start
     * @param int<0, max> $rows
     */
    private function result_set_start_and_rows_change(int $start, int $rows): bool
    {
        return $start !== $this->result_set_start || $rows !== $this->result_set_rows;
    }
    private function modify_query(): void
    {
        \assert(null !== $this->result_set_start);
        \assert(null !== $this->result_set_rows);
        $this->query->set_start($this->result_set_start)->set_rows($this->result_set_rows);
    }
    private function create_result_set(): Result
    {
        return $this->client->select($this->query, $this->endpoint);
    }
    public function set_endpoint(Endpoint|string|null $endpoint): static
    {
        $this->endpoint = $endpoint;
        return $this;
    }
}