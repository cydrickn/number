# Number

[![PHP from Packagist](https://img.shields.io/packagist/php-v/cydrickn/number.svg)](https://packagist.org/packages/cydrickn/number)

An arbitrary precision for floating point Numbers for PHP

## Add to composer
```bash
composer require cydrickn/number
```
## Use class
```php
use Cydrickn\Number\Number;
```
## Global Configuration
```php
Number::setConfig(array('places'=>20,'round'=>true));
```
Options|Description|Type|Default
-------|-----------|----|-------
places|The number decimals|integer|20
round|Round the number|boolean|true
## Constructor
```php
$num = new Number(0.1);
```
Parameters|Type|Default
----------|----|-------
$num|number, string or Number|
$config|array|array()
## Methods
#### Plus
```php
plus($num)
```
**$num** = number | string | Number()

Return new Number
The value is the value of this Number plus to $num
```php
$x = new Number(1.2);
$x->plus(3.2);          // 4.4
```
#### Minus
```php
minus($num)
```
**$num** = number | string | Number()

Return new Number
The value is the value of this Number minus to $num
```php
$x = new Number(4.2);
$x->minus(3.2);          // 1
```
#### Times
```php
times($num)
```
**$num** = number | string | Number()

Return new Number
The value is the value of this Number times to $num
```php
$x = new Number(1.2);
$x->times(2);          // 2.4
```
#### Divided By
```php
dividedBy($num)
```
**$num** = number | string | Number()

Return new Number
The value is the value of this Number divided by to $num
```php
$x = new Number(10.68);
$x->dividedBy(2);          // 5.34
```
#### Modulo
```php
modulo($num)
```
**$num** = number | string | Number()

Return new Number
The value is the value of this Number modulos of $num
```php
$x = new Number(10);
$x->modulo(3);          // 1
```
## Equality and Comparison
#### Equals
```php
equals($num)
```
**$num** = number | string | Number()

Return Boolean
Return *true* if the value of Number is equal to $num, return *false* if not
```php
$x = new Number(10);
$x->equals(10);         // true
```
#### Not equal
```php
notEqual($num)
```
**$num** = number | string | Number()

Return Boolean
Return *true* if the value of Number is not equal to $num, return *false* if not
```php
$x = new Number(10);
$x->notEqual(10);       // false
```
#### Greater than
```php
greaterThan($num)
```
**$num** = number | string | Number()

Return Boolean
Return *true* if the value of Number is greater than to $num, return *false* if not
```php
$x = new Number(10);
$x->greaterThan(10);     // false
```
#### Greater than or equal
```php
greaterThanOrEqual($num)
```
**$num** = number | string | Number()

Return Boolean
Return *true* if the value of Number is greater than or equal to $num, return *false* if not
```php
$x = new Number(10);
$x->greaterThanOrEqual(10);      // true
```

#### Less than
```php
lessThan($num)
```
**$num** = number | string | Number()

Return Boolean
Return *true* if the value of Number is less than to $num, return *false* if not
```php
$x = new Number(10);
$x->lessThan(11);               // true
```
#### Less than or equal
```php
lessThanOrEqual($num)
```
**$num** = number | string | Number()

Return Boolean
Return *true* if the value of Number is less than or equal to $num, return *false* if not
```php
$x = new Number(10);
$x->lessThanOrEqual(9);         // false
```
