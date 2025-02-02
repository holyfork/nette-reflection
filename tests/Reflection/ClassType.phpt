<?php

/**
 * Test: Nette\Reflection\ClassType tests.
 */

use Nette\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Foo
{
	public function f()
	{
	}
}

class Bar extends Foo implements Countable
{
	public $var;

	public function count(): int
	{
		return 1;
	}
}


Assert::equal(new Reflection\ClassType('Bar'), Reflection\ClassType::from('Bar'));
Assert::equal(new Reflection\ClassType('Bar'), Reflection\ClassType::from(new Bar));


$rc = Reflection\ClassType::from('Bar');

Assert::null($rc->getExtension());


Assert::equal([
	'Countable' => new Reflection\ClassType('Countable'),
], $rc->getInterfaces());


Assert::equal(new Reflection\ClassType('Foo'), $rc->getParentClass());


Assert::null($rc->getConstructor());


Assert::equal(new Reflection\Method('Foo', 'f'), $rc->getMethod('f'));


Assert::exception(function () use ($rc) {
	$rc->getMethod('doesntExist');
}, 'ReflectionException', 'Method Bar::doesntExist() does not exist');

Assert::equal([
	new Reflection\Method('Bar', 'count'),
	new Reflection\Method('Foo', 'f'),
], $rc->getMethods());


Assert::equal(new Reflection\Property('Bar', 'var'), $rc->getProperty('var'));


Assert::exception(function () use ($rc) {
	$rc->getProperty('doesntExist');
}, 'ReflectionException', 'Property Bar::$doesntExist does not exist');

Assert::equal([
	new Reflection\Property('Bar', 'var'),
], $rc->getProperties());
