<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Cydrickn\Number;

/**
 * Description of Configuration
 *
 * @author cydrickn
 */
class Configuration
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config + $this->getDefault();
    }

    public function get($name)
    {
        if (!array_key_exists($name, $this->config)) {
            throw $this->createNotFound($name);
        }

        return $this->config[$name];
    }

    public function set($name, $value)
    {
        if (!array_key_exists($name, $this->config)) {
            throw $this->createNotFound($name);
        }
        $this->config[$name] = $value;

        return $this;
    }

    private function createNotFound($name)
    {
        return new Exceptions\ConfigNotFoundException();
    }

    private function getDefault()
    {
        return [
            'precision'    => 20,
            'round'  => true,
        ];
    }
}
