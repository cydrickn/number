<?php
namespace Cydrickn\Number;

class Number {
    
    protected static $config = array(
        'precision'    => 20,
        'round'  => true
    );
    
    protected $value;
    
    protected $original;
    
    private $localConfig;

    // Static Methods
    
    /**
     * Set the configuration
     * @param type $config
     */
    public static function setConfig($config){
        static::$config = array_replace_recursive(static::$config, $config);
    }
    
    /**
     * Format the number
     * 
     * @param float $num
     * @param array $config
     * @return string
     */
    public static function format($num, $config = array()){
        if ($num instanceof Number) {
            $num = $num . '';
        }
        if(!preg_match('/^([+-]?(\d+(\.\d*)?)|(\.\d+))$/', $num))
            throw new \Exception('Number is expecting 1 parameters to be a number.');
        
        $config = array_replace_recursive(static::$config, $config);
       
        $broken_number = explode('.', $num.'');
        if(count($broken_number) != 2)
            $broken_number[1] = str_pad ('', $config['precision'], '0', STR_PAD_RIGHT);
        else $broken_number[1] = str_pad ($broken_number[1], $config['precision'], '0', STR_PAD_RIGHT);
        
        if($config['round']) {
            if($config['precision'] < strlen($broken_number[1])) {
                $pre = substr($broken_number[1], $config['precision'], 1);
                $broken_number[1] = substr($broken_number[1], 0, $config['precision']);
                if($pre >= 5) {
                    $broken_number[1] += 1;
                }
            }
        }
        
        return implode('.', $broken_number);
    }
    
    /**
     * Get the sum
     * 
     * @param float|string|\Cydrickn\Number\Number $num1
     * @param float|string|\Cydrickn\Number\Number $num2
     * @return \Cydrickn\Number\Number
     */
    public static function add($num1, $num2){
        $num1 = new Number($num1);
        return $num1->plus($num2);
    }
    
    /**
     * Get the quotient
     * 
     * @param float|string|\Cydrickn\Number\Number $num1
     * @param float|string|\Cydrickn\Number\Number $num2
     * @return \Cydrickn\Number\Number
     */
    public static function div($num1, $num2){
        $num1 = new Number($num1);
        return $num1->dividedBy($num2);
    }
    
    /**
     * Get the product
     * 
     * @param float|string|\Cydrickn\Number\Number $num1
     * @param float|string|\Cydrickn\Number\Number $num2
     * @return \Cydrickn\Number\Number
     */
    public static function mul($num1, $num2){
        $num1 = new Number($num1);
        return $num1->times($num2);
    }
    
    /**
     * Get the power
     * 
     * @param float|string|\Cydrickn\Number\Number $num1
     * @param float|string|\Cydrickn\Number\Number $exponent
     * @return \Cydrickn\Number\Number
     */
    public static function pow($num1, $exponent) {
        $num1 = new Number($num1);
        return $num1->toPower($exponent . '');
    }
    
    /**
     * Get the difference
     * 
     * @param float|string|\Cydrickn\Number\Number $num1
     * @param float|string|\Cydrickn\Number\Number $num2
     * @return \Cydrickn\Number\Number
     */
    public static function sub($num1, $num2){
        $num1 = new Number($num1);
        return $num1->sub($num2);
    }
    
    // End of static methods
    
    /**
     * Construct Number Object
     * @param float|string|\Cydrickn\Number\Number $num
     * @throws \Exception
     */
    public function __construct($num, $config = array()) {
        $config = array_replace_recursive(static::$config, $config);
        $this->localConfig = $config;
        $num = $num.'';
        if(preg_match('/^([+-]?(\d+(\.\d*)?)|(\.\d+))$/', $num))
            $this->value = static::format ($num, $config);
        else throw new \Exception('Number is expecting 1 parameters to be a number.');
    }
    
    // Methods
    
    # Arithmetic
    
    /**
     * Divided By
     * 
     * @param float|string|\Cydrickn\Number\Number $num
     * @return \Cydrickn\Number\Number
     */
    public function dividedBy($num){
        $num = $this->format($num);
        $result = $this->value / $num;
        return new Number($result);
    }
    
    /**
     * Minus
     * 
     * @param float|string|\Cydrickn\Number\Number $num
     * @return \Cydrickn\Number\Number
     */
    public function minus($num){
        $num = $this->format($num);
        $result = $this->value - $num;
        return new Number($result);
    }
    
    /**
     * Modulo
     * 
     * @param float|string|\Cydrickn\Number\Number $num
     * @return \Cydrickn\Number\Number
     */
    public function modulo($num) {
        $num = $this->format($num);
        $result = $this->value / $num;
        
        $real = intval($result);
        
        $prod = $this->format($real) * $num;
        return $this->sub($prod);
    }
    
    /**
     * Plus
     * 
     * @param float|string|\Cydrickn\Number\Number $num
     * @return \Cydrickn\Number\Number
     */
    public function plus($num){
        $num = $this->format($num);
        $result = $this->value + $num;
        return new Number($result);
    }
    
    public static function round() {
        $args = func_get_args();
        $static = !(isset($this) && get_class($this) == __CLASS__);
        if($static) {
            $args[0] = $args[0] .'';
            if(!isset($args[1])) {
                $args[1] = 0;
            } else if($args[1] instanceof Number) {
                $args[1] = $args[1] . '';
            }
            if(!isset($args[2])) {
                $args[2] = PHP_ROUND_HALF_UP;
            } else if($args[2] instanceof Number) {
                $args[2] = $args[2] . '';
            }
            $num = round($args[0], $args[1], $args[2]);
            return new Number($num);
        } else {
            $num = $this . '';
            if(!isset($args[0])) $args[0] = 0;
            if(!isset($args[1])) $args[1] = PHP_ROUND_HALF_UP;
            $num = round($num, $args[0], $args[1]);
            return new Number($num);
        }
    }
    
    /**
     * Times
     * 
     * @param float|string|\Cydrickn\Number\Number $num
     * @return \Cydrickn\Number\Number
     */
    public function times($num){
        $num = $this->format($num);
        $result = $this->value * $num;
        return new Number($result);
    }
    
    /**
     * To Power
     * 
     * @param float|string|\Cydrickn\Number\Number $num
     * @return \Cydrickn\Number\Number
     */
    public function toPower($num) {
        $_num = $this;
        /*for($i = 1; $i < $num; $i++) {
            $num2 = $this->format($this->value);
            $_num = $_num->times($num2);
        }*/
        $_num = new Number(pow($this->value, $num));
        return $_num;
    }
    
    # end of arithmetic
    
    # Comparison and equality
    
    /**
     * Check if the $num is equal to the value of the Number
     * 
     * @param  float|string|\Cydrickn\Number\Number
     * @return boolean
     */
    public function equals($num){
        $num = $this->format($num);
        return $this->value == $num;
    }
    
    /**
     * Check if the value of Number is greater than to $num
     * 
     * @param  float|string|\Cydrickn\Number\Number
     * @return boolean
     */
    public function greaterThan($num){
        $num = $this->format($num);
        return $this->value > $num;
    }
    
    /**
     * Check if the value of Number is greater than or equal to $num
     * 
     * @param  float|string|\Cydrickn\Number\Number
     * @return boolean
     */
    public function greaterThanOrEqual($num){
        $num = $this->format($num);
        return $this->value >= $num;
    }
    
    /**
     * Check if the value of Number is less than to $num
     * 
     * @param  float|string|\Cydrickn\Number\Number
     * @return boolean
     */
    public function lessThan($num){
        $num = $this->format($num);
        return $this->value < $num;
    }
    
    /**
     * Check if the value of Number is less than or equal to $num
     * 
     * @param  float|string|\Cydrickn\Number\Number
     * @return boolean
     */
    public function lessThanOrEqual($num){
        $num = $this->format($num);
        return $this->value < $num;
    }
    
    /**
     * Check if the $num is not equal to the value of the Number
     * 
     * @param  float|string|\Cydrickn\Number\Number
     * @return boolean
     */
    public function notEqual($num){
        $num = $this->format($num);
        return $this->value != $num;
    }
    
    # end of comparison and equality
    
    /**
     * Get the float value
     * 
     * @return float
     */
    public function toFloat(){
        return floatval($this->value);
    }
    
    /**
     * Parse an equation
     * 
     * @param sting $string
     * @param array $vars
     * @return \Cydrickn\Number\Number
     */
    public static function parseEquation($string, $vars = array(), $compute = true) {
        $functions = self::getFunctionsEq();
        $funcs = array();
        $string = str_replace(' ', '', $string);
        $string = trim(preg_replace('/([0-9]+)/', '#$1#', $string));
        foreach($functions as $func) {
            $count = 0;
            $string = str_replace($func, '$' . (count($funcs)), $string, $count);
            if($count > 0) {
                $funcs[] = $func;
            }
        }
        
        $string = self::normalize($string, $vars);
        if(!$compute) {
            foreach($funcs as $key => $func) {
                $string = str_replace('$' . $key, $func, $string);
            }
            $string = str_replace('#', '', $string);
            return $string;
        }
        
        return new Number(self::parseEq($string, $funcs)['value']);
    }
    
    private static function parseEq($str, $funcs) {
        $index = 0;
        $tokens = array();
        $isNew = true;
        $open = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $char = $str[$i];
            if($char == "$") {
                $tokens[$index] = array('type' => 'FUNC', 'func' => null,'value' => '');
                
                for($x = $i+1; $x < strlen($str); $x++) {
                    if($str[$x] != '(') {
                        $tokens[$index]['func'] = $str[$x];
                        continue;
                    }

                    $tokens[$index]['func'] = $funcs[$tokens[$index]['func']];
                    break;
                }
                $i = $x+1;
                
                $tokens[$index]['value'] = self::findEnd($str, $i, '(', ')');
            } else if ($char == '(') {
                $i++;
                $tokens[$index] = array('type' => 'PAR','value' => '');
                $tokens[$index]['value'] = self::findEnd($str, $i, '(', ')');
            } else if(self::isOperator($char)) {
                $tokens[$index] = array('type' => 'OPR','value' => $char);
            } else if($char == '#'){
                $i++;
                $tokens[$index] = array('type' => 'NUM','value' => '');
                $tokens[$index]['value'] = self::findEnd($str, $i, null, '#');
            }
            
            if($tokens[$index]['type'] == 'PAR') {
                $tokens[$index] = self::parseEq($tokens[$index]['value'], $funcs);
            } else if($tokens[$index]['type'] == 'FUNC') {
                $tokens[$index]['value'] = self::parseFunc($tokens[$index]['value'], $funcs);
                $tokens[$index]['value'] = self::executeFunction($tokens[$index]['func'], $tokens[$index]['value']) . '';
            }
            
            $index++;
        }
        
        return self::compute($tokens);
    }
    
    private static function findEnd($str, &$index, $open, $close) {
        $string = '';
        $count = 1;
        for($i = $index; $i < strlen($str); $i++) {
            $char =  $str[$i];
            if($char == $open) {
                if($count > 0) {
                    $string .= $char;
                }
                $count++;
            } else if($char == $close) {
                $count--;
                if($count > 0) {
                    $string .= $char;
                } else {
                    break;
                }
            } else {
                $string .= $char;
            }
        }
        $index = $i;
        return $string;
    }
    
    private static function parseFunc($parse, $funcs) {
        $sps = array();
        $_open = 0;
        $_parse = '';
        $_index = 0;
        
        for($x = 0; $x < strlen($parse); $x++) {
            $c = $parse[$x];
            if($c == '(') {
                $_open++;

            } else if($c == ')') {
                $_open--;
            }

            if($_open == 0 && $c == ',') {
                $_index++;
            } else {
                if(!isset($sps[$_index])) {
                    $sps[$_index] = '';
                }
                $sps[$_index] .= $c;
            }
        }

        $args = array();
        foreach($sps as $sp) {
            $args[] = self::parseEq($sp, $funcs)['value'];
        }
        
        return $args;
    }
    
    public static function normalize($string, $vars = array()) {
        
        $string = trim(preg_replace('/((?<=[0-9A-Za-z])(?=[A-Za-z])|(?<=[A-Za-z])(?=[0-9A-Za-z]))/', "*", $string), '*');
        
        $string = str_replace(')$', ')*$', $string);
        $string = str_replace(')(', ')*(', $string);
        
        foreach($vars as $key => $var) {
            $val = $var;
            if(is_numeric($val)) {
                $val = "#$val#";
            }
            $string = str_replace($key, $val, $string);
        }
        
        return $string;
    }
    
    private static function compute($array) {
        $new_array = $array;
        foreach(self::getOperators() as $opr) {
            if(count($new_array) > 1) {
                for($index = 0;$index < count($new_array);$index++) {
                    $val = $new_array[$index]['value'];
                    $type = $new_array[$index]['type'];
                    if($type == 'OPR' && $val === $opr) {
                        $num1 = $new_array[$index-1]['value'];
                        $num2 = $new_array[$index+1]['value'];
                        $num = new Number($num1);
                        switch ($val) {
                            case '+': 
                                $num = $num->plus($num2);break;
                            case '-': 
                                $num = $num->minus($num2);break;
                            case '*': 
                                $num = $num->times($num2);break;
                            case '/': 
                                $num = $num->dividedBy($num2);break;
                            case '%': 
                                $num = $num->modulo($num2);break;
                            case '^':
                                $num = $num->toPower($num2);break;
                            default: $num = new Number($num2);
                        }
                        $num = array(array('type' => 'NUM', 'value' => $num . ''));
                        array_splice($new_array, $index-1, 3, $num);
                        $index-=1;
                    }
                }
            }
        }
        return $new_array[0];
    }
    
    private static function getOperators() {
        return array('^','%','*','/','+','-');
    }
    
    private static function isOperator($operator) {
        if($operator instanceof Number) return false;
        $has = false;
        foreach(self::getOperators() as $op) {
            if($operator === $op) {
                $has = true;
                break;
            }
        }
        return $has;
    }
    
    private static function isFunctionEq($func) {
        $fns = self::getFunctionsEq();
        
        return in_array($func, $fns);
    }
    
    private static function getFunctionsEq() {
        return array(
            'add',
            'sub',
            'mul',
            'div',
            'round',
            'pow'
        );
    }
    
    private static function executeFunction($func, $arguments) {
        $ars = array();
        foreach ($arguments as $arg) {
            $ars[] = $arg . '';
        }
        return call_user_func_array(get_class() .'::' . $func, $arguments);
    } 
    
    
    // End of mehods
    
    
    // Magic
    
    public function __toString(){
        return $this->value;
    }
    
    // End of magic
    
}
