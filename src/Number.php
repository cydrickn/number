<?php

/*
 * This file is part of the Cydrickn Number package
 *
 * (c) Cydrick Nonog <cydrick.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cydrickn\Number;

/**
 * Cydrickn Number
 *
 * @author Cydrick Nonog <cydrick.dev@gmail.com>
 */
class Number
{
    /**
     * Global Configuration
     *
     * @var array
     */
    protected static $config = array(
        'precision' => 20,
        'round' => true
    );

    /**
     * Formatted Value
     *
     * @var string
     */
    protected $value;

    /**
     * Original Value
     *
     * @var string
     */
    protected $original;

    /**
     * Set global configurations
     *
     * @param array $config
     */
    public static function setConfig($config)
    {
        static::$config = array_replace_recursive(static::$config, $config);
    }

    /**
     * Format the given number
     *
     * @param string|int|float $num
     * @param array $config
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function format($num, $config = array())
    {
        if (!preg_match('/^([+-]?(\d+(\.\d*)?)|(\.\d+))$/', $num)) {
            throw new \Exception('Number is expecting 1 parameters to be a number.');
        }

        $config = array_replace_recursive(static::$config, $config);

        $broken_number = explode('.', $num . '');
        if (count($broken_number) != 2) {
            $broken_number[1] = str_pad('', $config['precision'], '0', STR_PAD_RIGHT);
        } else {
            $broken_number[1] = str_pad($broken_number[1], $config['precision'], '0', STR_PAD_RIGHT);
        }

        if ($config['round']) {
            if ($config['precision'] < strlen($broken_number[1])) {
                $pre = substr($broken_number[1], $config['precision'], 1);
                $broken_number[1] = substr($broken_number[1], 0, $config['precision']);
                if ($pre >= 5) {
                    $broken_number[1] += 1;
                }
                $broken_number[1] = str_pad($broken_number[1], $config['precision'], '0', STR_PAD_LEFT);
            }
        }

        return implode('.', $broken_number);
    }

    /**
     * Add two number
     *
     * @param string|float|int $num1
     * @param string|float|int $num2
     *
     * @return \Cydrickn\Number\Number
     */
    public static function add($num1, $num2)
    {
        $num1 = new Number($num1);

        return $num1->plus($num2);
    }



    /**
     * Try to convert scientific number to decimal
     *
     * @param string|int|float $num
     * @return string
     */
    public static function convertScientificToDecimal($num)
    {
        $parts = explode('E', $num);

        if (count($parts) === 2) {
            $exp = abs(end($parts)) + strlen($parts[0]);
            $result = number_format($num, $exp, '.', '');

            list ($int, $decimal) = explode('.', $result);
            $decimal = rtrim($decimal, '0');

            if ($decimal === '') {
                return $int;
            } else {
                return $int . '.' . $decimal;
            }
        } else {
            return $num;
        }
    }

    /**
     * Divide two number
     *
     * @param string|float|int $num1
     * @param string|float|int $num2
     *
     * @return \Cydrickn\Number\Number
     */
    public static function div($num1, $num2)
    {
        $num1 = new Number($num1);
        return $num1->dividedBy($num2);
    }

    /**
     * Multiply two numbers
     *
     * @param string|float|int $num1
     * @param string|float|int $num2
     *
     * @return \Cydrickn\Number\Number
     */
    public static function mul($num1, $num2)
    {
        $num1 = new Number($num1);

        return $num1->times($num2);
    }

    /**
     * Get the power of a number
     *
     * @param string|float|int $num1
     * @param string|float|int $exponent
     *
     * @return \Cydrickn\Number\Number
     */
    public static function pow($num1, $exponent)
    {
        $num1 = new Number($num1);

        return $num1->toPower($exponent);
    }

    /**
     * Subtract two numbers
     *
     * @param string|float|int $num1
     * @param string|float|int $num2
     *
     * @return \Cydrickn\Number\Number
     */
    public static function sub($num1, $num2)
    {
        $num1 = new Number($num1);

        return $num1->minus($num2);
    }

    /**
     * Cydrickn Number constructor
     *
     * @param string|float|int $num
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct($num, $config = array())
    {
        $config = array_replace_recursive(static::$config, $config);
        $num = static::convertScientificToDecimal((string) $num);

        if (preg_match('/^([+-]?(\d+(\.\d*)?)|(\.\d+))$/', $num)) {
            $this->value = static::format($num, $config);
        } else {
            throw new \Exception('Number is expecting 1 parameters to be a number.');
        }
    }

    # Arithmetic

    /**
     * Divided By
     *
     * @param string|float|int $num
     *
     * @return \Cydrickn\Number\Number
     */
    public function dividedBy($num)
    {
        $num = $this->format($num);
        $result = $this->value / $num;

        return new Number($result);
    }

    /**
     * Minus
     *
     * @param string|float|int $num
     *
     * @return \Cydrickn\Number\Number
     */
    public function minus($num)
    {
        $num = $this->format($num);
        $result = $this->value - $num;

        return new Number($result);
    }

    /**
     * Modulo
     *
     * @param string|float|int $num
     *
     * @return \Cydrickn\Number\Number
     */
    public function modulo($num)
    {
        $num = $this->format($num);
        $result = $this->value / $num;

        $real = intval($result);

        $prod = $this->format($real) * $num;

        return $this->sub($prod);
    }

    /**
     * Plus
     *
     * @param string|float|int $num
     *
     * @return \Cydrickn\Number\Number
     */
    public function plus($num)
    {
        $num = $this->format($num);
        $result = $this->value + $num;

        return new Number($result);
    }

    /**
     * Times
     *
     * @param string|float|int $num
     *
     * @return \Cydrickn\Number\Number
     */
    public function times($num)
    {
        $num = $this->format($num);
        $result = $this->value * $num;

        return new Number($result);
    }

    /**
     * To Power
     *
     * @param string|float|int $num
     *
     * @return \Cydrickn\Number\Number
     */
    public function toPower($num)
    {
        return new Number(pow($this->value, $num));
    }

    # end of arithmetic

    # Comparison and equality

    /**
     * Check if the $num is equal to the value of the Number
     *
     * @param type $num
     * @return type
     */
    public function equals($num)
    {
        $num = $this->format($num);

        return $this->value == $num;
    }

    /**
     * Check if the value of Number is greater than to $num
     *
     * @param string|float|int $num
     *
     * @return bool
     */
    public function greaterThan($num)
    {
        $num = $this->format($num);

        return $this->value > $num;
    }

    /**
     * Check if the value of Number is greater than or equal to $num
     *
     * @param string|float|int $num
     * @return bool
     */
    public function greaterThanOrEqual($num)
    {
        $num = $this->format($num);

        return $this->value >= $num;
    }

    /**
     * Check if the value of Number is less than to $num
     *
     * @param string|float|int $num
     *
     * @return bool
     */
    public function lessThan($num)
    {
        $num = $this->format($num);

        return $this->value < $num;
    }

    /**
     * Check if the value of Number is less than or equal to $num
     *
     * @param string|float|int $num
     *
     * @return bool
     */
    public function lessThanOrEqual($num)
    {
        $num = $this->format($num);

        return $this->value < $num;
    }

    /**
     * Check if the $num is not equal to the value of the Number
     *
     * @param string|float|int $num
     *
     * @return bool
     */
    public function notEqual($num)
    {
        $num = $this->format($num);

        return $this->value != $num;
    }

    /**
     * Get the float value
     *
     * @return float
     */
    public function toFloat()
    {
        eval('$var = ' . $this->value . ';');

        return $var;
    }

    /**
     * Parse an equation
     *
     * @param string $string
     * @param array $vars
     * @param bool $compute
     *
     * @return \Cydrickn\Number\Number
     */
    public static function parseEquation($string, $vars = array(), $compute = true)
    {
        $index = 0;
        $array_parse = array();
        $type = null;
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if ($char == '(') {

                if ($type == 'num' || $type == 'arr') {
                    $index++;
                    $array_parse[$index] = '*';
                    $index++;
                }

                $type = 'arr';
                $parse = "";
                $open = 0;
                for ($ii = $i + 1; $ii < strlen($string); $ii++) {
                    if ($string[$ii] == "(") {
                        $open++;
                    }
                    if ($string[$ii] == ")") {
                        $open--;
                    }

                    if ($open == -1) {
                        break;
                    } else {
                        $parse .= $string[$ii];
                    }
                }
                $i = $ii;
                if (array_key_exists($index, $array_parse)) {
                    $index++;
                }
                if ($compute) {
                    $array_parse[$index] = self::parseEquation($parse, $vars, $compute);
                } else {
                    $array_parse[$index] = '(' . self::parseEquation($parse, $vars, $compute) . ')';
                }
            } elseif (self::isOperator($char) && $type != 'opr' && !is_null($type)) {
                if (array_key_exists($index, $array_parse)) {
                    $index++;
                }
                $type = 'opr';
                $array_parse[$index] = $char;
            } else {
                if (array_key_exists($index, $array_parse) && $type != 'num') {
                    if ($type == 'arr') {
                        $index++;
                        $array_parse[$index] = '*';
                    }
                    $index++;
                    $array_parse[$index] = '';
                } elseif (!array_key_exists($index, $array_parse)) {
                    $array_parse[$index] = '';
                }
                $type = 'num';
                $array_parse[$index] .= $char;
            }
        }
        self::subtitute($array_parse, $vars);

        if ($compute) {
            return (new Number(self::compute($array_parse, $vars)));
        }

        return implode('', $array_parse);
    }

    /**
     * Subtitute
     *
     * @param array $array
     * @param array $vars
     */
    private static function subtitute(&$array, $vars = array())
    {
        foreach ($array as &$arr) {
            if (!self::isOperator($arr)) {
                if (is_string($arr) && array_key_exists($arr, $vars)) {
                    $arr = $vars[$arr];
                }
            }
        }
    }

    /**
     * Compute
     *
     * @param array $array
     *
     * @return string
     */
    private static function compute($array)
    {
        $new_array = $array;
        foreach (self::getOperators() as $opr) {
            for ($index = 0; $index < count($new_array); $index++) {
                $val = $new_array[$index];
                if (self::isOperator($val) && $val === $opr) {
                    $num1 = $new_array[$index - 1];
                    $num2 = $new_array[$index + 1];
                    $num = new Number($num1);
                    switch ($val) {
                        case '+':
                            $num = $num->plus($num2);
                            break;
                        case '-':
                            $num = $num->minus($num2);
                            break;
                        case '*':
                            $num = $num->times($num2);
                            break;
                        case '/':
                            $num = $num->dividedBy($num2);
                            break;
                        case '%':
                            $num = $num->modulo($num2);
                            break;
                        case '^':
                            $num = $num->toPower($num2);
                            break;
                        default: $num = new Number($num2);
                    }
                    array_splice($new_array, $index - 1, 3, $num . '');
                    $index -= 1;
                }
            }
        }
        return $new_array[0];
    }

    /**
     * Get operators
     *
     * @return array
     */
    private static function getOperators()
    {
        return array('^', '%', '*', '/', '+', '-');
    }

    /**
     * Check if given value is an operator
     *
     * @param string|int|float|\Cydrickn\Number\Number $operator
     *
     * @return bool
     */
    private static function isOperator($operator)
    {
        if ($operator instanceof Number) {
            return false;
        }
        $has = false;
        foreach (self::getOperators() as $op) {
            if ($operator === $op) {
                $has = true;
                break;
            }
        }

        return $has;
    }

    /**
     * Magic function to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * To string
     *
     * @return string
     */
    public function toString()
    {
        return (string) $this->value;
    }

    /**
     * To integer
     *
     * @return int
     */
    public function toInt()
    {
        return (int) $this->value;
    }
}
