<?php
namespace Cydrickn\Number;

class Number {
    
    protected static $config = array(
        'precision'    => 20,
        'round'  => true
    );
    
    protected $value;
    
    protected $original;

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
        return $num1->toPower($exponent);
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
        $index = 0;
        $array_parse = array();
        $type = null;
        for($i = 0;$i < strlen($string);$i++) {
            $char = $string[$i];
            if($char == '(') {
                
                if($type == 'num' || $type == 'arr') {
                    $index++;
                    $array_parse[$index] = '*';
                    $index++;
                }
                
                $type = 'arr';
                $parse = "";
                $open = 0;
                for($ii = $i+1; $ii < strlen($string);$ii++) {
                    if($string[$ii] == "(") $open++;
                    if($string[$ii] == ")") $open--;
                    
                    if($open == -1) break;
                    else $parse .= $string[$ii];
                }
                $i = $ii;
                if(array_key_exists($index, $array_parse)) {
                    $index++;
                }
                if($compute)
                    $array_parse[$index] = self::parseEquation($parse, $vars, $compute);
                else $array_parse[$index] = '('.self::parseEquation($parse, $vars, $compute).')';
            } else if(self::isOperator($char) && $type != 'opr' && !is_null($type)) {
                if(array_key_exists($index, $array_parse)) {
                    $index++;
                }
                $type = 'opr';
                $array_parse[$index] = $char;
            } else {
                if(array_key_exists($index, $array_parse) && $type != 'num') {
                    if($type == 'arr') {
                        $index++;
                        $array_parse[$index] = '*';
                    }
                    $index++;
                    $array_parse[$index] = '';
                } else if(!array_key_exists($index, $array_parse)) {
                    $array_parse[$index] = '';
                }
                $type = 'num';
                $array_parse[$index] .= $char;
            }
        }
        self::subtitute($array_parse, $vars);
        if($compute)
            return (new Number(self::compute($array_parse, $vars)));
        return implode('',$array_parse);
    }
    
    private static function subtitute(&$array, $vars = array()) {
        foreach($array as &$arr) {
            if(!self::isOperator($arr)) {
                if(is_string($arr) && array_key_exists($arr, $vars)) {
                    $arr = $vars[$arr];
                }
            }
        }
    }
    
    private static function compute($array) {
        $new_array = $array;
        foreach(self::getOperators() as $opr) {
            for($index = 0;$index < count($new_array);$index++) {
                $val = $new_array[$index];
                if(self::isOperator($val) && $val === $opr) {
                    $num1 = $new_array[$index-1];
                    $num2 = $new_array[$index+1];
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
                    array_splice($new_array, $index-1, 3, $num . '');
                    $index-=1;
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
    
    
    // End of mehods
    
    
    // Magic
    
    public function __toString(){
        return $this->value;
    }
    
    // End of magic
    
}
