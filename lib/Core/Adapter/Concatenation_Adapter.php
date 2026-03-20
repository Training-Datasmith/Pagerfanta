<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

use Pagerfanta\Exception\InvalidArgumentException;
/**
 * Adapter that concatenates the results of other adapters.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Concatenation_Adapter implements Adapter_Interface
{
    /**
     * @var list<AdapterInterface<T>>
     */
    protected array $adapters;
    /**
     * Cache of the numbers of results of the adapters. The indexes correspond the indexes of the $adapters property.
     *
     * @var list<int<0, max>>|null
     */
    protected ?array $adapters_nb_results_cache = null;
    /**
     * @param list<AdapterInterface<T>> $adapters
     *
     * @throws InvalidArgumentException if an adapter is not a {@see AdapterInterface} instance
     */
    public function __construct(array $adapters)
    {
        foreach ($adapters as $adapter) {
            if (!$adapter instanceof Adapter_Interface) {
                throw new InvalidArgumentException(\sprintf('The $adapters argument of the %s constructor expects all items to be an instance of %s.', self::class, Adapter_Interface::class));
            }
        }
        $this->adapters = $adapters;
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        if (null === $this->adapters_nb_results_cache) {
            $this->refresh_adapters_nb_results();
        }
        \assert(null !== $this->adapters_nb_results_cache);
        return array_sum($this->adapters_nb_results_cache);
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        if (null === $this->adapters_nb_results_cache) {
            $this->refresh_adapters_nb_results();
        }
        \assert(null !== $this->adapters_nb_results_cache);
        $slice = [];
        $previous_adapters_nb_results_sum = 0;
        $request_first_index = $offset;
        $request_last_index = $offset + $length - 1;
        foreach ($this->adapters as $index => $adapter) {
            $adapter_nb_results = $this->adapters_nb_results_cache[$index];
            $adapter_first_index = $previous_adapters_nb_results_sum;
            $adapter_last_index = $adapter_first_index + $adapter_nb_results - 1;
            $previous_adapters_nb_results_sum += $adapter_nb_results;
            // The adapter is fully below the requested slice range — skip it
            if ($adapter_last_index < $request_first_index) {
                continue;
            }
            // The adapter is fully above the requested slice range — finish the gathering
            if ($adapter_first_index > $request_last_index) {
                break;
            }
            // Else the adapter range definitely intersects with the requested range
            $fetch_offset = $request_first_index - $adapter_first_index;
            $fetch_length = $length;
            // The requested range start is below the adapter range start
            if ($fetch_offset < 0) {
                $fetch_length += $fetch_offset;
                $fetch_offset = 0;
            }
            // The requested range end is above the adapter range end
            if ($fetch_offset + $fetch_length > $adapter_nb_results) {
                $fetch_length = $adapter_nb_results - $fetch_offset;
            }
            // Getting the subslice from the adapter and adding it to the result slice
            $fetch_slice = $adapter->get_slice($fetch_offset, $fetch_length);
            foreach ($fetch_slice as $item) {
                $slice[] = $item;
            }
        }
        return $slice;
    }
    /**
     * Refreshes the cache of the numbers of results of the adapters.
     */
    protected function refresh_adapters_nb_results(): void
    {
        if (null === $this->adapters_nb_results_cache) {
            $this->adapters_nb_results_cache = [];
        }
        foreach ($this->adapters as $index => $adapter) {
            $this->adapters_nb_results_cache[$index] = $adapter->get_nb_results();
        }
    }
}