# SALT Technology PHP Client Library

This library is used to integrate with the SALT Payment Gateway.

Code samples are available as part of the package in the `examples/` folder.

Information about our payment platform and full API documentation can be found here: http://salttechnology.github.io/

Additional instructions on how to get started with the client library can found here: http://salttechnology.github.io/client_libraries.html


## Dependencies

SALT's PHP library requires PHP version 5.3.0 or higher.


## Download & Install
Clone our repository or download the (source)[https://github.com/SaltTechnology/salt-payment-client-php/archive/master.zip] as a zip file.

## Usage

To use this library, include the SALT.php file and start using the library.



```
<?php
//Include the salt library
include 'path/to/SALT/lib/SALT.php';

//Include the classes you want to use from the SALT namespace.
use \SALT\Merchant;
use \SALT\HttpsCreditCardService;

...

//Initialize the SALT library (HttpsCreditCardService is the key class here)
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
$merchant = new Merchant ('Merchant ID', 'API Key');
$service = new HttpsCreditCardService($merchant, $url);
```

## Exceptions & Error Handling

Any transaction errors, validation errors, connection errors, or other types of errors will cause a \SALT\SaltError (Exception). Make sure to catch these exceptions and handle them appropriately.


## Testing

To run the tests, you'll need PHPUnit. Run the following on your command line from the root directory of this project:
```
> phpunit --bootstrap lib/Salt.php tests/
```
