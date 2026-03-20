<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

/**
 * Adapter which transforms the result of other adapter.
 *
 * @template T
 *
 * @template-covariant Transformed
 *
 * @implements AdapterInterface<Transformed>
 */
class Transforming_Adapter implements Adapter_Interface
{
    /**
     * @var callable(T, array-key): Transformed
     */
    private $transformer;
    /**
     * @param AdapterInterface<T>                 $adapter
     * @param callable(T, array-key): Transformed $transformer
     */
    public function __construct(private readonly Adapter_Interface $adapter, callable $transformer)
    {
        $this->transformer = $transformer;
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->adapter->get_nb_results();
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, Transformed>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        $transformer = $this->transformer;
        foreach ($this->adapter->get_slice($offset, $length) as $key => $item) {
            yield $transformer($item, $key);
        }
    }
}