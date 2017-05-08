<?php

namespace Sergekukharev\Enumerable;

/**
 * Interface ComparableInterface that shows that instance can be compared to other instances.
 *
 * @see https://wiki.php.net/rfc/comparable This interface should be removed if RFC is approved.
 */
interface ComparableInterface
{
    /**
     * Returns 1 if current instance should be ordered higher than $other.
     * Returns 0 if instances have same level of ordering.
     * Returns -1 if current instance should be ordered lower than $other.
     *
     * @param static $other
     * @return int
     */
    public function compareTo($other);
}
