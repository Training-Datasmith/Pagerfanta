<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

/**
 * Adapter which generates a null item list based on a number of results.
 *
 * @implements AdapterInterface<null>
 */
class Null_Adapter implements Adapter_Interface
{
    /**
     * @param int<0, max> $nbResults
     */
    public function __construct(private readonly int $nb_results = 0)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->nb_results;
    }
    /**
     * The following methods are derived from code of the Zend Framework
     * Code subject to the new BSD license (http://framework.zend.com/license/new-bsd).
     *
     * Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
     *
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, null>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        if ($offset >= $this->nb_results) {
            return [];
        }
        return $this->create_null_array($this->calculate_null_array_length($offset, $length));
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return int<0, max>
     */
    private function calculate_null_array_length(int $offset, int $length): int
    {
        $remain_count = $this->remain_count($offset);
        if ($length > $remain_count) {
            return $remain_count;
        }
        return $length;
    }
    /**
     * @param int<0, max> $offset
     *
     * @return int<0, max>
     */
    private function remain_count(int $offset): int
    {
        return max(0, $this->nb_results - $offset);
    }
    /**
     * @param int<0, max> $length
     *
     * @return array<int, null>
     */
    private function create_null_array(int $length): array
    {
        return array_fill(0, $length, null);
    }
}