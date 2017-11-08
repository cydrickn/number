<?php

namespace Cydrickn\Number;

/**
 * Description of Number2
 */
class Number2
{
    private static $config;
    private $localConfig;
    private $value;

    public static function setConfig($config = [])
    {

    }

    public function __construct($value, $config = null)
    {
        $this->value = $value;
    }

    public function getConfig($name)
    {

    }

    public function __toString()
    {
        return (string) $this->format();
    }

    private function format($config = [])
    {
        $formated = $this->value;

        return $formated;
    }

    private function initConfig()
    {
        if (static::$config instanceof Configuration) {
            $this->localConfig = static::$config;
        }
    }
}
