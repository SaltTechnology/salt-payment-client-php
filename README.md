# SALT Technology PHP Client Library

This library is used to integrate with the SALT Payment Gateway.

Code samples are available as part of the package in the `examples/` folder.

Information about our payment platform and full API documentation can be found here: http://salttechnology.github.io/

Additional instructions on how to get started with the client library can found here: http://salttechnology.github.io/client_libraries.html


## Dependencies

SALT's PHP library requires PHP version 5.3.0 or higher.

## Usage

To use this library, include the SALT.php file and start using the library.



```
<?php
include 'path/to/SALT/lib/SALT.php';
use \SALT\Merchant;
use \SALT\HttpsCreditCardService;
...
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
$merchant = new Merchant ('Merchant ID', 'API Key');
...
```


## Testing

To run the tests, you'll need PHPUnit. Run the following on your command line from the root directory of this project:
```
> phpunit --bootstrap lib/Salt.php tests/
```
