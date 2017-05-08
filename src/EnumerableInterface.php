<?php

namespace Sergekukharev\Enumerable;

use Countable;
use Iterator;
use IteratorAggregate;

interface EnumerableInterface extends Countable, IteratorAggregate
{
    /**
     * Core of the class, calls $callback on each element.
     *
     * @param callable|null $callback
     * @return void
     */
    public function each(callable $callback = null);

    /**
     * Runs callback against each collection element. Returns True if block never returns false or null,
     * false otherwise.
     *
     * If $callback is null, it will return True if none of the collection elements are null or false.
     *
     * @param callable|null $callback
     * @return boolean
     */
    public function allTrue(callable $callback = null);

    /**
     * Runs callback against each collection element. Returns True if block returns value other
     * than false or null at least once.
     *
     * If $callback is null, it will return True if any of the collection elements are not null or false.
     *
     * @param callable|null $callback
     * @return boolean
     */

    public function anyTrue(callable $callback = null);

    /**
     * Enumerates over the items, chunking them together based on the return value of the callback.
     *
     * Each consecutive call to callback that returns the same value will add item to the current chunk.
     * Otherwise it will start a new chunk.
     *
     * Elements that return the same callback value are chunked together.
     *
     * @param callable $callback
     * @return Iterator
     */
    public function chunk(callable $callback);

    /**
     * Returns a new instance of EnumerableInterface with the results of running callback against each element.
     *
     * @param callable $callback
     * @return static
     */
    public function collect(callable $callback);

    /**
     * Counts elements that return true with $callback called.
     *
     * If no $callback is provided, counts all elements.
     *
     * @param callable $callback
     * @return int
     */
    public function count(callable $callback = null);

    /**
     * Counts elements that are identical to $item.
     *
     * @param mixed $item
     * @return int
     */
    public function countItem($item);

    /**
     * Drops $count elements from the collection and returns the rest.
     *
     * @param $count
     * @return static
     */
    public function drop($count);

    /**
     * Drops elements until $callback with this element returns true. Result includes the first element
     * with true value and all the remaining elements of collection.
     *
     * @param callable $callback
     * @return static
     */
    public function dropWhile(callable $callback);

    /**
     * Calls $callback with each's elements index and value.
     *
     * $callback should expect parameters in order of index, value.
     *
     * @param callable $callback
     * @return void
     */
    public function eachWithIndex(callable $callback);

    /**
     * Returns array representation of the Collection.
     *
     * @return array
     */
    public function toArray();

    /**
     * Returns first element in collection, for which $callback returns true.
     *
     * If $noneFound is provided and no elements were found, $noneFound is called and find() returns it's result.
     *
     * @param callable $callback
     * @param callable $noneFound
     * @return mixed|null
     */
    public function find(callable $callback, callable $noneFound = null);

    /**
     * Returns every element in collection, for which $callback returns true.
     *
     * @param callable $callback
     * @return static
     */
    public function findAll(callable $callback);

    /**
     * If $valueOrCallback is callable, it will return the index the of first element for which $valueOrCallback
     * returned true.
     *
     * Otherwise returns the index of the first element in collection for which the element is identical to $value.
     *
     * @param callable|mixed $valueOrCallback
     * @param callable $noneFound
     * @return mixed
     */
    public function findIndex($valueOrCallback, callable $noneFound = null);

    /**
     * Returns first $count elements of the collection or returns first element if $count is null.
     *
     * @param int $count
     * @return mixed|static
     */
    public function first($count = null);

    /**
     * Returns array of the collection elements grouped by the results of the callback.
     *
     * @param callable $callback
     * @return array
     */
    public function groupBy(callable $callback);

    /**
     * Returns true if the collection contains at least one element that is identical to $value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function doesInclude($value);

    /**
     * Reduces collection by applying binary operation on each element accumulating result in memo variable.
     *
     * If $initial is null, it will pick the first element as initial value.
     *
     * TODO ~~If $operation is callable, it calls it for each element, passing memo and element in this order.~~
     *
     * @param string $operation
     * @param mixed $initial
     * @return mixed
     */
    public function reduce($operation, $initial = null);

    /**
     * Returns new collection with the results of running $callback over each element.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback);

    /**
     * Returns maximum value of the collection.
     *
     * If $compare is given, it's results will be used to compare items between each other.
     * $compare should only return -1, 0 or 1.
     *
     * TODO ~~If items in collection implement Sergekukharev/Enumerable/ComparableInterface, it will be used to determine
     * maximum value.~~
     *
     * Otherwise, method will try to apply normal comparison logic.
     *
     * @param callable $compare
     * @return mixed
     */
    public function max(callable $compare = null);

    /**
     * Returns $count elements from the collection that have maximum values.
     *
     * If $compare is given, it's results will be used to compare items between each other.
     * $compare should only return -1, 0 or 1.
     *
     * TODO ~~If items in collection implement Sergekukharev/Enumerable/ComparableInterface, it will be used to determine
     * maximum value.~~
     *
     * Otherwise, method will try to apply normal comparison logic.
     *
     * @param callable $compare
     * @return mixed
     */
    public function maxElements($count, $compare = null);

    /**
     * Returns minimum value of the collection.
     *
     * If $compare is given, it's results will be used to compare items between each other.
     * $compare should only return -1, 0 or 1.
     *
     * If items in collection implement Sergekukharev/Enumerable/ComparableInterface, it will be used to determine
     * minimum value.
     *
     * Otherwise, method will try to apply normal comparison logic.
     *
     * @param callable $compare
     * @return mixed
     */
    public function min(callable $compare = null);

    /**
     * Returns $count elements from the collection that have minimum values.
     *
     * If $compare is given, it's results will be used to compare items between each other.
     * $compare should only return -1, 0 or 1.
     *
     * TODO ~~ If items in collection implement Sergekukharev/Enumerable/ComparableInterface, it will be used to determine
     * minimum value.~~
     *
     * Otherwise, method will try to apply normal comparison logic.
     *
     * @param callable $compare
     * @return mixed
     */
    public function minElements($count, $compare = null);

    /**
     * Returns maximum and minimum values from the collection
     *
     * If $compare is given, it's results will be used to compare items between each other.
     * $compare should only return -1, 0 or 1.
     *
     * If items in collection implement Sergekukharev/Enumerable/ComparableInterface, it will be used to determine
     * minimum and maximum values.
     *
     * Otherwise, method will try to apply normal comparison logic.
     * @param callable|null $compare
     * @return array
     */
    public function minMax(callable $compare = null);

    /**
     * Returns true if collection has exactly one element for which $identifier returned true.
     *
     * @param callable $identifier
     * @return boolean
     */
    public function hasExactlyOne(callable $identifier);

    /**
     * Returns true if $identifier returned false for all the elements.
     *
     * @param callable $identifier
     * @return boolean
     */
    public function hasNone(callable $identifier);

    /**
     * Returns new collection with elements, for which $callback returned false.
     *
     * @param callable $callback
     * @return mixed
     */
    public function reject(callable $callback);

    /**
     * Loops through collection from last till first element.
     *
     * @param callable $callback
     * @return void
     */
    public function reverseEach(callable $callback);

    /**
     * Alias to findAll.
     * @return mixed
     */
    public function select(callable $callback);

    /**
     * Returns sorted collection.
     *
     * If $compare is given, it's results will be used to compare items between each other.
     * $compare should only return -1, 0 or 1.
     *
     * If items in collection implement Sergekukharev/Enumerable/ComparableInterface, it will be used to sort
     * collection.
     *
     * Otherwise, method will try to apply normal comparison logic.
     *
     * @param callable $compare
     * @return static
     */
    public function sort(callable $compare = null);

    /**
     * Returns first $items from the collection.
     *
     * @param int $items
     * @return static
     */
    public function take($items);

    /**
     * Passes each element to $callback until it returns false. Returns all prior elements.
     *
     * @param callable $callback
     * @return static
     */
    public function takeWhile(callable $callback);

    /**
     * Returns new collection with only unique values from current collection.
     *
     * @return static
     */
    public function unique();
}
