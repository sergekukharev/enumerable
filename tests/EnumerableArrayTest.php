<?php

use PHPUnit\Framework\TestCase;
use Sergekukharev\Enumerable\EnumerableArray;

class EnumerableArrayTest extends TestCase
{
    // TODO reduce with callback
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

    public function testToArrayProperlyConvertsToArray()
    {
        self::assertEquals([1, 2, 5, -10], (new EnumerableArray([1, 2, 5, -10]))->toArray());
    }

    public function testCollectReturnsNewCollectionWithElementsReturnedFromCallback()
    {
        $array = new EnumerableArray([10, 12, 14, -8]);

        $multiplyByTwo = function($item) {
            return $item * 2;
        };

        $newArray = $array->collect($multiplyByTwo);

        self::assertInstanceOf(EnumerableArray::class, $newArray);
        self::assertEquals([20, 24, 28, -16], $newArray->toArray());
    }

    public function testCountAllElements()
    {
        $array = new EnumerableArray([1, 2, 3, 4]);

        self::assertEquals(4, $array->count());
    }

    public function testCountWithCallbackOnlyValuesWhereCallbackReturnsTrue()
    {
        $array = new EnumerableArray([10, 14, 6, -3, 0, -91213]);

        self::assertEquals(4, $array->count(function($i) { return $i >= 0; }));
    }

    public function testCountItem()
    {
        $array = new EnumerableArray([0, 1, 2, 1, 1, 1, 3, 0, 1]);

        self::assertEquals(5, $array->countItem(1));
    }

    public function testCountItemIsCheckingIdentity()
    {
        $array = new EnumerableArray([1, '1', '1', 1, 1]);

        self::assertNotEquals(4, $array->countItem(1));
        self::assertEquals(3, $array->countItem(1));
        self::assertEquals(2, $array->countItem('1'));
    }

    public function testDropDropsElements()
    {
        $array = new EnumerableArray([1, 2, 3, 4]);

        self::assertEquals(new EnumerableArray([3, 4]), $array->drop(2));
    }

    public function testDropWhenDroppedItemsIsBiggerThanCollectionHasReturnsEmptyColleciton()
    {
        $array = new EnumerableArray([1, 2, 3, 4]);

        self::assertEquals(new EnumerableArray([]), $array->drop(100));
    }

    public function testDropWithNegativeCountKeepsElementsInTheEndOfArray()
    {
        $array = new EnumerableArray([1, 2, 3, 4, 5]);

        self::assertEquals(new EnumerableArray([4, 5]), $array->drop(-2));
    }

    public function testDropWhileStopsWhenCallbackReturnsFalseAndReturnsCollectionWithPreviousElements()
    {
        $array = new EnumerableArray([1, 2, 3, 4, 5]);

        self::assertEquals(new EnumerableArray([4, 5]), $array->dropWhile(function($i) { return $i < 4; }));
    }

    public function testEachWithIndexPassesIndex()
    {
        $array = new EnumerableArray([1, 2, 3]);
        $sumIndex = 0;
        $sumValues = 0;

        $array->eachWithIndex(function ($index, $item) use (&$sumIndex, &$sumValues) {
            $sumIndex += $index;
            $sumValues += $item;
        });

        self::assertEquals(array_sum([1, 2, 3]), $sumValues);
        self::assertEquals(3, $sumIndex);
    }

    public function testFindReturnsFirstValueFound()
    {
        $lookup = function($item) { return $item > 5; };

        $array = new EnumerableArray([-10, 5, 6, 19, 132, -3, 3]);

        self::assertEquals(6, $array->find($lookup));
    }

    public function testFindReturnsNullIfNothingWasFoundAndNoSecondCallbackProvided()
    {
        $lookup = function($item) { return $item > 5; };

        $array = new EnumerableArray([-10, 5, -3, 3]);

        self::assertEquals(null, $array->find($lookup));
    }

    public function testFindReturnsResultsOfSecondCallbackIfNothingWasFound()
    {
        $lookup = function($item) { return $item > 5; };
        $finally = function() { return 'default-value'; };

        $array = new EnumerableArray([-10, 5, -3, 3]);

        self::assertEquals('default-value', $array->find($lookup, $finally));
    }

    public function testFindAllReturnsCollectionWithAllElementsForWhichCallbackReturnedTrue()
    {
        $lookup = function($item) { return $item > 5; };

        $array = new EnumerableArray([-10, 5, 6, 19, 132, -3, 3]);

        self::assertEquals(new EnumerableArray([6, 19, 132]), $array->findAll($lookup));
    }

    public function testFindIndexReturnsIndexOfFirstFoundElement()
    {
        $lookup = function($item) { return $item > 5; };

        $array = new EnumerableArray([-10, 5, 6, 19, 132, -3, 3]);

        self::assertEquals(2, $array->findIndex($lookup));
    }

    public function testFindIndexReturnsResultOfSecondCallbackIfNothingWasFound()
    {
        $lookup = function($item) { return $item > 5; };
        $finally = function() { return 'default-index'; };

        $array = new EnumerableArray([-10, 5, -3, 3]);

        self::assertEquals('default-index', $array->findIndex($lookup, $finally));
    }

    public function testFindIndexLooksForItemThatIsEqualToValuePassedIfItsNotCallable()
    {
        $array = new EnumerableArray([-10, 5, 6, 19, 132, -3, 3]);

        self::assertEquals(3, $array->findIndex(19));
    }

    public function testFindFirstReturnsFirstElementIfCountIsNotProvided()
    {
        $array = new EnumerableArray([-10, 5, 6, 19, 132, -3, 3]);

        self::assertEquals(-10, $array->first());
    }

    public function testFirstReturnsCollectionOfFirstElementsWhenCountIsProvided()
    {
        $array = new EnumerableArray([-10, 5, 6, 19, 132, -3, 3]);

        self::assertEquals(new EnumerableArray([-10, 5, 6]), $array->first(3));
    }

    public function testGroupByReturnsGroupedByCallbackResultsChunks()
    {
        $array = new EnumerableArray(['Alice', 'Bob', 'Bill', 'Chalres', 'Cindy', 'Carol']);

        $firstLetter = function($string) { return $string[0]; };

        $chunks = iterator_to_array($array->groupBy($firstLetter));

        self::assertArrayHasKey('A', $chunks);
        self::assertArrayHasKey('B', $chunks);
        self::assertArrayHasKey('C', $chunks);
        self::assertArrayNotHasKey('D', $chunks);

        self::assertEquals(['Alice'], $chunks['A']);
        self::assertEquals(['Bob', 'Bill'], $chunks['B']);
        self::assertEquals(['Chalres', 'Cindy', 'Carol'], $chunks['C']);
    }

    public function testGroupByReturnsGroupedByCallbackResultsChunks2()
    {
        $scores = new EnumerableArray([
            ['name' => 'Alice', 'score' => 76],
            ['name' => 'Bob', 'score' => 45],
            ['name' => 'Charles', 'score' => 93]
        ]);

        $checkIfPassed = function($student) {
            return $student['score'] > 75 ? 'passed' : 'failed';
        };

        $chunks = iterator_to_array($scores->groupBy($checkIfPassed));

        self::assertArrayHasKey('passed', $chunks);
        self::assertArrayHasKey('failed', $chunks);

        self::assertEquals(
            [['name' => 'Alice', 'score' => 76],['name' => 'Charles','score' => 93]],
            $chunks['passed']
        );

        self::assertEquals([['name' => 'Bob', 'score' => 45]], $chunks['failed']);
    }

    public function testDoesIncludeReturnsTrueIfCollectionHasAtLeastOneElementIdenticalToPassedValue()
    {
        $array = new EnumerableArray([0, 5, 6]);

        self::assertTrue($array->doesInclude(5));
    }

    public function testDoesIncludeReturnsFalseIfThereAreNoElementsIdenticalToPassedValue()
    {
        $array = new EnumerableArray([0, 5, 6]);

        self::assertFalse($array->doesInclude(10));
    }

    public function testDoesIncludeUsesIdenticalComparison()
    {
        $array = new EnumerableArray([0, '5', 6]);

        self::assertFalse($array->doesInclude(5));
    }

    /**
     * @param array $rawArray
     * @param string $operation
     * @param number $initialValue
     * @param number $expectedResult
     * @dataProvider provideReduceWithNumbersAndInitialValues
     */
    public function testReduceWithNumbers($rawArray, $operation, $initialValue, $expectedResult)
    {
        self::assertEquals($expectedResult, (new EnumerableArray($rawArray))->reduce($operation, $initialValue));
    }

    public function provideReduceWithNumbersAndInitialValues()
    {
        return [
            [[1, 2, 3], '+', 5, 11],
            [[2, 4, 3], '*', 2, 48],
            [[10, 2, 3], '-', 15, 0],
            [[10, 5], '/', 150, 3],
        ];
    }

    public function testReduceToSumWithNoInitialValuePicksFirstElementAsInitial()
    {
        self::assertEquals(9, (new EnumerableArray([2, 4, 3]))->reduce('+'));
    }

    public function testReduceToMultiplicationWithNoInitialValuePicksFirstElementAsInitial()
    {
        self::assertEquals(24, (new EnumerableArray([2, 4, 3]))->reduce('*'));
    }

    public function testReduceToDivisionWithNoInitialValuePicksFirstElementAsInitial()
    {
        self::assertEquals(2, (new EnumerableArray([48, 4, 2, 3]))->reduce('/'));
    }

    public function testReduceToDiffWithNoInitialValuePicksFirstElementAsInitial()
    {
        self::assertEquals(3, (new EnumerableArray([12, 4, 2, 3]))->reduce('-'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testReduceToDivisionThrowsRuntimeErrorIfAnyOfTheElementIsZeroWithInitialValue()
    {
        (new EnumerableArray([0, 1, 3]))->reduce('/', 10);
    }

    public function testReduceToDivisionThrowsIfAnyButFirstIsZeroWithoutInitialValue()
    {
        self::assertEquals(0, (new EnumerableArray([0, 1, 3]))->reduce('/'));
    }

    public function testReduceWithStringsConcatsThemWithInitialValue()
    {
        self::assertEquals('StartTestFooBar', (new EnumerableArray(['Test', 'Foo', 'Bar']))->reduce('.', 'Start'));
    }

    public function testReduceWithStringsConcatsThemWithoutInitialValue()
    {
        self::assertEquals('TestFooBar', (new EnumerableArray(['Test', 'Foo', 'Bar']))->reduce('.'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testReduceWithStringsAndInitialValueThatIsNotStringWillThrowException()
    {
        (new EnumerableArray(['a', 'b']))->reduce('.', 123);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testReduceWithInvalidOperationThrows()
    {
        (new EnumerableArray(['a', 'b']))->reduce('++');
    }

    public function testMapIsAliasToCollect()
    {
        $array = new EnumerableArray([10, 12, 14, -8]);

        $multiplyByTwo = function($item) {
            return $item * 2;
        };

        $newArray = $array->map($multiplyByTwo);

        self::assertInstanceOf(EnumerableArray::class, $newArray);
        self::assertEquals([20, 24, 28, -16], $newArray->toArray());
    }

    public function testMaxWithNormalLogic()
    {
        $array = new EnumerableArray([10, 12, 14, -8]);

        self::assertEquals(14, $array->max());
    }

    public function testMaxWithNormalLogicWorksWithStrings()
    {
        $array = new EnumerableArray(['a', 'b', 'c']);

        self::assertEquals('c', $array->max());
    }

    public function testMaxWithCallback()
    {
        $callback = function ($a, $b) {
            return version_compare($a, $b);
        };

        $array = new EnumerableArray(['1.2.3', '1.0.123', '1.3.0']);

        self::assertEquals('1.3.0', $array->max($callback));
    }

    public function testMinWithNormalLogic()
    {
        $array = new EnumerableArray([10, 12, 14, -8]);

        self::assertEquals(-8, $array->min());
    }

    public function testMinWithNormalLogicWorksWithStrings()
    {
        $array = new EnumerableArray(['a', 'b', 'c']);

        self::assertEquals('a', $array->min());
    }

    public function testMinWithCallback()
    {
        $callback = function ($a, $b) {
            return version_compare($a, $b);
        };

        $array = new EnumerableArray(['1.2.3', '1.0.123', '1.3.0']);

        self::assertEquals('1.0.123', $array->min($callback));
    }

    public function testMinMaxWithoutCallback()
    {
        $array = new EnumerableArray([10, 12, 14, -8]);

        self::assertEquals([-8, 14], $array->minMax());
    }

    public function testMinMaxWithCallback()
    {
        $callback = function ($a, $b) {
            return version_compare($a, $b);
        };

        $array = new EnumerableArray(['1.2.3', '1.0.123', '1.3.0']);

        self::assertEquals(['1.0.123', '1.3.0'], $array->minMax($callback));
    }

    public function testHasExactlyOneTrueCase()
    {
        self::markTestIncomplete();
        $array = new EnumerableArray([10, 12, 14, -8]);

        self::assertTrue($array->hasExactlyOne(12));
    }
}
