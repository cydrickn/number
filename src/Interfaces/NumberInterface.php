<?php

namespace Cydrickn\Number\Interfaces;

/**
 * Number Interface
 */
interface NumberInterface
{
    public function dividedBy($num);
    public function minus($num);
    public function plus($num);
    public function times($num);

    public static function add($num1, $num2);

    /**
     * @deprecated since version 2
     *
     * @param type $num1
     * @param type $num2
     */
    public static function div($num1, $num2);
    public static function divide($num1, $num2);
    public static function mul($num1, $num2);
    public static function multiply($num1, $num2);
    public static function sub($num1, $num2);
    public static function substract($num1, $num2);
}
