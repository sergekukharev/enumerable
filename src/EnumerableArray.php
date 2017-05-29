<?php

namespace Sergekukharev\Enumerable;

use ArrayIterator;
use RuntimeException;

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
     * @return ArrayIterator
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
        foreach ($this->getIterator() as $value) {
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
        //TODO implement me properly.
    }

    /**
     * @inheritDoc
     */
    public function collect(callable $callback)
    {
        $newData = [];

        foreach ($this->getIterator() as $value) {
            $newData[] = $callback($value);
        }

        return new static($newData);
    }

    /**
     * @inheritDoc
     */
    public function count(callable $callback = null)
    {
        if ($callback === null) {
            return $this->getIterator()->count();
        }

        $count = 0;

        foreach ($this->getIterator() as $item) {
            if ($callback($item) === true) {
                $count++;
            }
        }

        return $count;

    }

    /**
     * @inheritDoc
     */
    public function countItem($item)
    {
        return $this->count(function($i) use ($item) { return $i === $item; });
    }

    /**
     * @inheritDoc
     */
    public function drop($count)
    {
        return new static(array_slice($this->getIterator()->getArrayCopy(), $count));
    }

    /**
     * @inheritDoc
     */
    public function dropWhile(callable $callback)
    {
        $itemsToDrop = 0;

        foreach ($this->getIterator() as $item) {
            if ($callback($item) === false) {
                break;
            }

            $itemsToDrop++;
        }

        return $this->drop($itemsToDrop);
    }

    /**
     * @inheritDoc
     */
    public function eachWithIndex(callable $callback)
    {
        foreach ($this->getIterator() as $key => $value) {
            $callback($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->getIterator()->getArrayCopy();
    }

    /**
     * @inheritDoc
     */
    public function find(callable $callback, callable $noneFound = null)
    {
        foreach ($this->getIterator() as $item) {
            if ($callback($item) === true) {
                return $item;
            }
        }

        return $noneFound === null ? null : $noneFound();
    }

    /**
     * @inheritDoc
     */
    public function findAll(callable $callback)
    {
        $data = [];

        foreach ($this->getIterator() as $item) {
            if ($callback($item) === true) {
                $data[] = $item;
            }
        }

        return new static($data);
    }

    /**
     * @inheritDoc
     */
    public function findIndex($valueOrCallback, callable $noneFound = null)
    {
        return is_callable($valueOrCallback) ? $this->findIndexByCallback($valueOrCallback, $noneFound) :
            $this->findIndexByValue($valueOrCallback, $noneFound);
    }

    /**
     * @param $valueOrCallback
     * @param callable $noneFound
     * @return mixed
     */
    private function findIndexByCallback($valueOrCallback, callable $noneFound = null)
    {
        foreach ($this->getIterator() as $index => $item) {
            if ($valueOrCallback($item) === true) {
                return $index;
            }
        }

        return $noneFound === null ? null : $noneFound();
    }

    /**
     * @param $valueOrCallback
     * @param callable $noneFound
     * @return mixed
     */
    private function findIndexByValue($valueOrCallback, callable $noneFound = null)
    {
        foreach ($this->getIterator() as $index => $item) {
            if ($item === $valueOrCallback) {
                return $index;
            }
        }

        return $noneFound === null ? null : $noneFound();
    }

    /**
     * @inheritDoc
     */
    public function first($count = null)
    {
        if ($count === null) {
            return $this->getIterator()->current();
        }

        $data = [];
        $maxIndex = min($this->getIterator()->count(), $count);
        $iterator = $this->getIterator();

        for($i = 0; $i < $maxIndex; $i++) {
            $data[] = $iterator->current();
            $iterator->next();
        }

        return new static($data);
    }

    /**
     * @inheritDoc
     */
    public function groupBy(callable $callback)
    {
        $chunks = [];

        foreach ($this->getIterator() as $value) {
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
    public function doesInclude($value)
    {
        foreach ($this->getIterator() as $item) {
            if ($item === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     * @throws \RuntimeException
     */
    public function reduce($operation, $initial = null)
    {
        switch ($operation) {
            case '.':
                return $this->reduceToString($initial);
            case '+':
                return $this->reduceToSum($initial);
            case '-':
                return $this->reduceToDiff($initial);
            case '*':
                return $this->reduceToMultiplication($initial);
            case '/':
                return $this->reduceToDivision($initial);
            default:
                throw new \RuntimeException('Unknown reduce operation');
        }
    }

    private function reduceToString($initial) {
        $memo = $initial === null ? '' : $initial;

        if (!is_string($memo)) {
            throw new RuntimeException('Initial value for reduce operation should be string');
        }

        foreach ($this->getIterator() as $item) {
            $memo .= $item;
        }

        return $memo;
    }

    private function reduceToSum($initial) {
        $memo = $initial === null ? 0 : $initial;

        foreach ($this->getIterator() as $item) {
            $memo += $item;
        }

        return $memo;
    }

    private function reduceToDiff($initial) {
        $memo = $initial;

        foreach ($this->getIterator() as $item) {
            if ($memo === null) {
                $memo = $item;
                continue;
            }

            $memo -= $item;
        }

        return $memo;
    }

    private function reduceToMultiplication($initial) {
        $memo = $initial === null ? 1 : $initial;

        foreach ($this->getIterator() as $item) {
            $memo *= $item;
        }

        return $memo;
    }

    private function reduceToDivision($initial) {
        if (($initial !== null && $this->doesInclude(0)) || $this->drop(1)->doesInclude(0)) {
            throw new RuntimeException('Collection contains Zero(s). Can\'t apply reduce with division');
        }

        $memo = $initial;

        foreach ($this->getIterator() as $item) {
            if ($memo === null) {
                $memo = $item;
                continue;
            }

            $memo /= $item;
        }

        return $memo;
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback)
    {
        return $this->collect($callback);
    }

    /**
     * @inheritDoc
     */
    public function max(callable $compare = null)
    {
        if ($compare === null) {
            $compare = function($a, $b) {
                return $a <=> $b;
            };
        }

        $maxValue = $this->getIterator()->current();

        foreach ($this->getIterator() as $item) {
            if ($compare($item, $maxValue) === 1) {
                $maxValue = $item;
            }
        }

        return $maxValue;
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
        if ($compare === null) {
            $compare = function($a, $b) {
                return $a <=> $b;
            };
        }

        $minValue = $this->getIterator()->current();

        foreach ($this->getIterator() as $item) {
            if ($compare($item, $minValue) === -1) {
                $minValue = $item;
            }
        }

        return $minValue;
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
        return [
            $this->min($compare),
            $this->max($compare)
        ];
    }

    /**
     * @inheritDoc
     * @param mixed $identifier
     */
    public function hasExactlyOne($identifier = true)
    {
        $count = 0;

        foreach ($this->getIterator() as $item) {
            if ($count > 1) {
                break;
            }

            if (is_callable($identifier) && $identifier($item) === true) {
                $count++;
                continue;
            }

            if ($item === $identifier) {
                $count++;
            }
        }

        return $count === 1;
    }

    /**
     * @inheritDoc
     * @param mixed $identifier
     */
    public function hasNone($identifier = true)
    {
        foreach ($this->getIterator() as $item) {
            if (is_callable($identifier) && $identifier($item) === true) {
                return false;
            }

            if ($item === $identifier) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function reject(callable $callback)
    {
        $data = [];

        foreach ($this->getIterator() as $item) {
            if ($callback($item) === false) {
                $data[] = $item;
            }
        }

        return new static($data);
    }

    /**
     * @inheritDoc
     */
    public function reverseEach(callable $callback)
    {
        $result = null;

        foreach ($this->getReverseIterator() as $value) {
            $result = $callback($value);
        }

        return $result;
    }

    /**
     * @return ArrayIterator
     */
    private function getReverseIterator()
    {
        return new ArrayIterator(array_reverse($this->getIterator()->getArrayCopy()));
    }

    /**
     * @inheritDoc
     */
    public function select(callable $callback)
    {
        return $this->findAll($callback);
    }

    /**
     * @inheritDoc
     */
    public function sort(callable $compare = null)
    {
        $arrayCopy = $this->getIterator()->getArrayCopy();

        if ($compare === null) {

            sort($arrayCopy);

            return new static($arrayCopy);
        }

        usort($arrayCopy, $compare);

        return new static($arrayCopy);
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
