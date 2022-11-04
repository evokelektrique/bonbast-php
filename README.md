# Bonbast PHP

Simple and easy to use package to retrieve the latest updated currency prices from the Bonbast website.

# Install

Using composer `composer require evokelektrique/bonbast:dev-master`

# Usage

```php
use Bonbast\Bonbast;

$bonbast = new Bonbast();
$result = $bonbast->get_formatted_price("usd"); // usd, eur ...

var_dump($result);
// array(2) {
//   ["sell"]=>
//   string(5) "32730"
//   ["buy"]=>
//   string(5) "32630"
// }
```
