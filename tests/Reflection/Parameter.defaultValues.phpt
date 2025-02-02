<?php

/**
 * Test: Nette\Reflection\Parameter default values test.
 */

use Nette\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function check($name, $args)
{
	$method = new Reflection\Method($name);
	foreach ($method->getParameters() as $param) {
		echo "$name(\${$param->getName()})\n";
		[$isOptional, $isDefaultValueAvailable, $defaultValue] = array_shift($args) + [null, null, null];
		Assert::same($isOptional, $param->isOptional(), 'optional');
		Assert::same($isDefaultValueAvailable, $param->isDefaultValueAvailable(), 'default available');

		if ($isDefaultValueAvailable) {
			Assert::same($defaultValue, $param->getDefaultValue(), 'default value');
		}
	}
}


class Test
{
	public function func1($a, $b, $c)
	{
	}
	public function func2($a, $b, $c = null)
	{
	}
	public function func3($a, $b = null, $c = null)
	{
	}
	public function func4($a, array $b = null, array $c)
	{
	}
	public function func5($a, $b = null, array $c = null)
	{
	}
	public function func6($a, Exception $b = null, Exception $c)
	{
	}
	public function func7($a, $b = null, Exception $c = null)
	{
	}
}


check('Test::func1', [
	/* $a */ [false, false], // isOptional | isDefaultValueAvailable | [ getDefaultValue ]
	/* $b */ [false, false],
	/* $c */ [false, false],
]);
check('Test::func2', [
	/* $a */ [false, false],
	/* $b */ [false, false],
	/* $c */ [true, true],
]);
check('Test::func3', [
	/* $a */ [false, false],
	/* $b */ [true, true, null],
	/* $c */ [true, true, null],
]);
if (PHP_VERSION_ID >= 80100) {
	check('Test::func4', [
		/* $a */ [false, false],
		/* $b */ [false, false],
		/* $c */ [false, false],
	]);
} else {
	check('Test::func4', [
		/* $a */ [false, false],
		/* $b */ [false, true],
		/* $c */ [false, false],
	]);
}
check('Test::func5', [
	/* $a */ [false, false],
	/* $b */ [true, true, null],
	/* $c */ [true, true, null],
]);
if (PHP_VERSION_ID >= 80100) {
	check('Test::func6', [
		/* $a */ [false, false],
		/* $b */ [false, false],
		/* $c */ [false, false],
	]);
} else {
	check('Test::func6', [
		/* $a */ [false, false],
		/* $b */ [false, true],
		/* $c */ [false, false],
	]);
}
check('Test::func7', [
	/* $a */ [false, false],
	/* $b */ [true, true, null],
	/* $c */ [true, true, null],
]);
check('Exception::__construct', [
	/* $message */ [true, true, ''],
	/* $code */ [true, true, 0],
	/* $previous */ [true, true],
]);
check('FilesystemIterator::__construct', [
	/* $path */ [false, false],
	/* $flags */ [true, true, 4096],
]);
/*
check('PDO::__construct', [
	/* $dsn * / [false, false],
	/* $username * / [true, false],
	/* $passwd * / [true, false],
	/* $options * / [true, false],
]);
check('mysqli::mysqli', [
	/* $host * / [true, false],
	/* $username * / [true, false],
	/* $passwd * / [true, false],
	/* $dbname * / [true, false],
	/* $port * / [true, false],
	/* $socket * / [true, false],
]);
*/
