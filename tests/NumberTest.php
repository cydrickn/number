<?php

/*
 * This file is part of the Cydrickn Number package
 *
 * (c) Cydrick Nonog <cydrick.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Cydrickn\Number;

use Cydrickn\Number\Number;
use PHPUnit\Framework\TestCase;

/**
 * Description of NumberTest
 *
 * @author Cydrick Nonog <cydrick.dev@gmail.com>
 */
final class NumberTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Number::setConfig(['precision' => 5]);
    }

    public function testFormatFailed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Number is expecting 1 parameters to be a number.");

        Number::format('NotANumber1');
    }

    /**
     * @dataProvider formatSuccessfullyDataProvider
     */
    public function testFormatSuccessfully($value, $config, $expectedResult)
    {
        $num = Number::format($value, $config);
        $this->assertSame($expectedResult, $num);
    }

    public function formatSuccessfullyDataProvider()
    {
        yield [20.06, [], '20.06000'];
        yield ['20.06', [], '20.06000'];
        yield ['20.06', ['precision' => 2], '20.06'];
        yield ['20.061', ['precision' => 2], '20.06'];
        yield ['20.065', ['precision' => 2], '20.07'];
        yield ['20.095', ['precision' => 1], '20.1'];
    }

    /**
     * @dataProvider addDataProvider
     */
    public function testAdd($num1, $num2, $expectedResult)
    {
        $num = Number::add($num1, $num2);

        $this->assertSame($expectedResult, $num->toString());
    }

    public function addDataProvider()
    {
        yield [1, 1, '2.00000'];
        yield [99.999, 1.0, '100.99900'];
    }

    public function testDiv()
    {

    }

    public function testMul()
    {

    }

    public function testPow()
    {
        
    }

    public function testSub()
    {

    }

    public function testConvertScientificToDecimal()
    {

    }
}
