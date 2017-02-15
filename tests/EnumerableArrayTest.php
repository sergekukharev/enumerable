<?php

use PHPUnit\Framework\TestCase;
use Sergekukharev\Enumerable\EnumerableArray;

class EnumerableArrayTest extends TestCase
{
    public function testCanCallCallbackOnEachElement()
    {
        $raw = [1, 2, 3];
        $array = new EnumerableArray($raw);
        $sum = 0;

        $array->each(function($item) use (&$sum) {$sum += $item;});

        self::assertEquals(array_sum($raw), $sum);
    }

    public function testAllTrueReturnsTrueIfCallbackAlwaysReturnsTrue()
    {
        $array = new EnumerableArray([10, 11, 3]);

        self::assertTrue($array->allTrue(function($item) { return $item > 0; }));
    }

    public function testAllTrueReturnsFalseIfAnyCallbackReturnsFalse()
    {
        $array = new EnumerableArray([10, -1, 3]);

        self::assertFalse($array->allTrue(function($item) { return $item > 0; }));
    }

    public function testAnyTrueReturnsFalseOnlyIfCallbackAlwaysReturnsFalse()
    {
        $array = new EnumerableArray([-6, -1, -3]);

        self::assertFalse($array->anyTrue(function($item) { return $item > 0; }));
    }

    public function testAnyTrueReturnsFalseIfAnyCallbackReturnsTrue()
    {
        $array = new EnumerableArray([-4, 5, -3]);

        self::assertTrue($array->anyTrue(function($item) { return $item > 0; }));
    }

    public function testChunkReturnsGroupedByCallbackResultsChunks()
    {
        $array = new EnumerableArray(['Alice', 'Bob', 'Bill', 'Chalres', 'Cindy', 'Carol']);

        $firstLetter = function($string) { return $string[0]; };

        $chunks = iterator_to_array($array->chunk($firstLetter));

        self::assertArrayHasKey('A', $chunks);
        self::assertArrayHasKey('B', $chunks);
        self::assertArrayHasKey('C', $chunks);
        self::assertArrayNotHasKey('D', $chunks);

        self::assertEquals(['Alice'], $chunks['A']);
        self::assertEquals(['Bob', 'Bill'], $chunks['B']);
        self::assertEquals(['Chalres', 'Cindy', 'Carol'], $chunks['C']);
    }

    public function testChunkReturnsGroupedByCallbackResultsChunks2()
    {
        $scores = new EnumerableArray([
            ['name' => 'Alice', 'score' => 76],
            ['name' => 'Bob', 'score' => 45],
            ['name' => 'Charles', 'score' => 93]
        ]);

        $checkIfPassed = function($student) {
            return $student['score'] > 75 ? 'passed' : 'failed';
        };

        $chunks = iterator_to_array($scores->chunk($checkIfPassed));

        self::assertArrayHasKey('passed', $chunks);
        self::assertArrayHasKey('failed', $chunks);

        self::assertEquals(
            [['name' => 'Alice', 'score' => 76],['name' => 'Charles','score' => 93]],
            $chunks['passed']
        );

        self::assertEquals([['name' => 'Bob', 'score' => 45]], $chunks['failed']);
    }
}
