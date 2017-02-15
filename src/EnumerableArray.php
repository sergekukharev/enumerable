<?php

namespace Sergekukharev\Enumerable;

use ArrayIterator;
use Traversable;

class EnumerableArray implements EnumerableInterface
{
    /** @var ArrayIterator */
    private $iterator;

    /**
     * @param array $raw
     */
    public function __construct(array $raw = [])
    {
        $this->iterator = new ArrayIterator($raw);
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $this->iterator->rewind();

        return $this->iterator;
    }

    /**
     * @inheritDoc
     */
    public function each(callable $callback = null)
    {
        foreach ($this->iterator as $value) {
            $callback($value);
        }
    }

    /**
     * @inheritDoc
     */
    public function allTrue(callable $callback = null)
    {
        foreach ($this->getIterator() as $value) {
            if (!$callback($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function anyTrue(callable $callback = null)
    {
        foreach ($this->getIterator() as $value) {
            if ($callback($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function chunk(callable $callback)
    {
        $chunks = [];

        foreach ($this->iterator as $value) {
            $chunkKey = $callback($value);

            if (!isset($chunks[$chunkKey])) {
                $chunks[$chunkKey] = [];
            }

            $chunks[$chunkKey][] = $value;
        }

        return new ArrayIterator($chunks);
    }

    /**
     * @inheritDoc
     */
    public function collect(callable $callback)
    {
        // TODO: Implement collect() method.
    }

    /**
     * @inheritDoc
     */
    public function count(callable $callback = null)
    {
        // TODO: Implement count() method.
    }

    /**
     * @inheritDoc
     */
    public function countItem($item)
    {
        // TODO: Implement countItem() method.
    }

    /**
     * @inheritDoc
     */
    public function cycle(callable $callback, $times = null)
    {
        // TODO: Implement cycle() method.
    }

    /**
     * @inheritDoc
     */
    public function drop($count)
    {
        // TODO: Implement drop() method.
    }

    /**
     * @inheritDoc
     */
    public function dropWhile(callable $callback)
    {
        // TODO: Implement dropWhile() method.
    }

    /**
     * @inheritDoc
     */
    public function eachSlice($size, callable $callback)
    {
        // TODO: Implement eachSlice() method.
    }

    /**
     * @inheritDoc
     */
    public function eachWithIndex(callable $callback)
    {
        // TODO: Implement eachWithIndex() method.
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @inheritDoc
     */
    public function find(callable $callback, callable $noneFound = null)
    {
        // TODO: Implement find() method.
    }

    /**
     * @inheritDoc
     */
    public function findAll(callable $callback)
    {
        // TODO: Implement findAll() method.
    }

    /**
     * @inheritDoc
     */
    public function findIndex($valueOrCallback)
    {
        // TODO: Implement findIndex() method.
    }

    /**
     * @inheritDoc
     */
    public function first($count = null)
    {
        // TODO: Implement first() method.
    }

    /**
     * @inheritDoc
     */
    public function groupBy(callable $callback)
    {
        // TODO: Implement groupBy() method.
    }

    /**
     * @inheritDoc
     */
    public function doesInclude($value)
    {
        // TODO: Implement doesInclude() method.
    }

    /**
     * @inheritDoc
     */
    public function reduce($operationOrCallback, $initial = null)
    {
        // TODO: Implement reduce() method.
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback)
    {
        // TODO: Implement map() method.
    }

    /**
     * @inheritDoc
     */
    public function max(callable $compare = null)
    {
        // TODO: Implement max() method.
    }

    /**
     * @inheritDoc
     */
    public function maxElements($count, $compare = null)
    {
        // TODO: Implement maxElements() method.
    }

    /**
     * @inheritDoc
     */
    public function min(callable $compare = null)
    {
        // TODO: Implement min() method.
    }

    /**
     * @inheritDoc
     */
    public function minElements($count, $compare = null)
    {
        // TODO: Implement minElements() method.
    }

    /**
     * @inheritDoc
     */
    public function minMax(callable $compare = null)
    {
        // TODO: Implement minMax() method.
    }

    /**
     * @inheritDoc
     */
    public function hasExactlyOne(callable $identifier)
    {
        // TODO: Implement hasExactlyOne() method.
    }

    /**
     * @inheritDoc
     */
    public function hasNone(callable $identifier)
    {
        // TODO: Implement hasNone() method.
    }

    /**
     * @inheritDoc
     */
    public function reject(callable $callback)
    {
        // TODO: Implement reject() method.
    }

    /**
     * @inheritDoc
     */
    public function reverseEach(callable $callback)
    {
        // TODO: Implement reverseEach() method.
    }

    /**
     * @inheritDoc
     */
    public function select(callable $callback)
    {
        // TODO: Implement select() method.
    }

    /**
     * @inheritDoc
     */
    public function sort(callable $compare = null)
    {
        // TODO: Implement sort() method.
    }

    /**
     * @inheritDoc
     */
    public function take($items)
    {
        // TODO: Implement take() method.
    }

    /**
     * @inheritDoc
     */
    public function takeWhile(callable $callback)
    {
        // TODO: Implement takeWhile() method.
    }

    /**
     * @inheritDoc
     */
    public function unique()
    {
        // TODO: Implement unique() method.
    }
}
