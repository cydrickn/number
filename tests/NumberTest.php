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
        $result = Number::format($value, $config);

        $this->assertSame($expectedResult, $result);
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
        $result = Number::add($num1, $num2);

        $this->assertSame($expectedResult, $result->toString());
    }

    public function addDataProvider()
    {
        yield [1, 1, '2.00000'];
        yield [99.999, 1.0, '100.99900'];
    }

    /**
     * @dataProvider divDataProvider
     */
    public function testDiv($num1, $num2, $expectedResult)
    {
        $result = Number::div($num1, $num2);

        $this->assertSame($expectedResult, $result->toString());
    }

    public function divDataProvider()
    {
        yield [1, 1, '1.00000'];
        yield ['1', '1', '1.00000'];
        yield ['20.06', '3', '6.68667'];
    }

    /**
     * @dataProvider mulDataProvider
     */
    public function testMul($num1, $num2, $expectedResult)
    {
        $result = Number::mul($num1, $num2);

        $this->assertSame($expectedResult, $result->toString());
    }

    public function mulDataProvider()
    {
        yield [20, 3.6, '72.00000'];
        yield [0.0063, 0.23, '0.00145'];
    }

    /**
     * @dataProvider powDataProvider
     */
    public function testPow($num1, $exponent, $expectedResult)
    {
        $result = Number::pow($num1, $exponent);

        $this->assertSame($expectedResult, $result->toString());
    }

    public function powDataProvider()
    {
        yield [2, 2, '4.00000'];
        yield ['3.69', '2.1', '15.51513'];
    }

    /**
     * @dataProvider subDataProvider
     */
    public function testSub($num1, $num2, $expectedResult)
    {
        $result = Number::sub($num1, $num2);

        $this->assertSame($expectedResult, $result->toString());
    }

    public function subDataProvider()
    {
        yield [2, 1, '1.00000'];
        yield ['1000.8989', '90312.8912', '-89311.99230'];
    }

    public function testToString()
    {
        $num = new Number(2.0001);

        $this->assertSame('2.00010', $num->toString());
    }

    public function testToFloat()
    {
        $num = new Number('2.00100');

        $this->assertSame(2.001, $num->toFloat());
    }

    public function testToInt()
    {
        $num = new Number(2.001);

        $this->assertSame(2, $num->toInt());
    }

    public function testConvertScientificToDecimal()
    {
        $resultNegativeExponent = Number::convertScientificToDecimal('2.1E-5');
        $this->assertSame('0.000021', $resultNegativeExponent);

        $resultPositiveExponent = Number::convertScientificToDecimal('2.1E5');
        $this->assertSame('210000', $resultPositiveExponent);
    }
}
