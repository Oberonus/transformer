# Oberonus API Transformer

## Contents

- [Installation](#installation)
- [Quick Start](#quick-start)

## Installation

To install Transformer run the command:

    composer require oberonus/transformer
        
## Quick Start

Declare child class from Oberon\Transform:
```php
use Oberonus\Transformer;

class MyTransformer extends Transformer {
   
    //fields list to transform
    protected $fields = ['one', 'two'];

    //converting rules
    protected $converters = [
        'one' => [\Oberonus\Transformer\Transformer::RENAME, 'renamed_one']
    ];
}
```
Now it's easy to process transformations:
```php
$transformer = new MyTransformer;
$result = $transformer->transform(['one' => 1, 'two' => 2]);
```
And result will be:
```php
[
    "renamed_one" => 1,
    "two" => 2
]
```
