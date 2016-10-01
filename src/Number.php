<?php
namespace Cydrickn\Number;

class Number {
    
    protected static $config = array(
        'precision'    => 20,
        'round'  => true
    );
    
    protected $value;


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
        
        if($config['round']){
            return number_format (
                    $broken_number[0] . '.' . $broken_number[1],
                    $config['precision'],'.',''
                    );
        } else if(!$config['round']) {
            return number_format (
                    $broken_number[0] . '.' . substr($broken_number[1], 0, $config['precision']),
                    $config['precision'],'.',''
                    );
        }
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
        $result = $this->value - $num;
        return new Number($result);
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
    
    // End of mehods
    
    
    // Magic
    
    public function __toString(){
        return $this->value;
    }
    
    // End of magic
    
}
