# MLocati's unofficial Nexi XPay client library for PHP

This project contains a PHP library that makes it easy to use the [Nexi](https://www.nexi.it) XPay APIs (not for Intesa Sanpaolo bank).

This requires a merchant Alias and a MAC Key.
If instead you have an API Key, you may need to use [this other library](https://github.com/mlocati/nexi-xpay-web).


## Installation

### Install with composer

Simply run the following command:

```sh
composer require mlocati/nexi-xpay
```

### Manual installation

Download the code of this library, place it somewhere in your project, and add this PHP instruction before using anything of this library:

```php
require '/path/to/nexi.php';
```

## Usage

### Configuration

First of all, you have need a merchant **alias** and a **MAC Key** (provided to you by Nexi).

You also need the base URL of the Nexi XPay API.
You can find its default value in `MLocati\Nexi\XPay\Configuration`:

- for test environments: you can use `MLocati\Nexi\XPay\Configuration::DEFAULT_BASEURL_TEST`
- for production environments: you can use `MLocati\Nexi\XPay\Configuration::DEFAULT_BASEURL_PRODUCTION`

This library provides an easy way to represent a configuration, by using the `MLocati\Nexi\XPay\Configuration\FromArray` class:

```php
use MLocati\Nexi\XPay\Configuration;

// For test environment
$configuration = new Configuration\FromArray([
    'alias' => 'YOUR ALIAS FOR TEST',
    'macKey' => 'YOUR MAC KEY FOR TEST',
    'environment' => 'test',
]);
// For production environment
$configuration = new Configuration\FromArray([
    'alias' => 'YOUR ALIAS FOR PRODUCTION',
    'macKey' => 'YOUR MAC KEY FOR PRODUCTION',
]);
```

Of course you can override the default base URL (use the `baseUrl` array key).

You can also use a custom class, provided it implements [the `MLocati\Nexi\XPay\Configuration` interface](https://github.com/mlocati/nexi-xpay/blob/main/src/Configuration.php).

### The Nexi Client

The main class of this library is `MLocati\Nexi\XPay\Client`: it allows you invoking the Nexi APIs.

You can create an instance of it simply with:

```php
use MLocati\Nexi\XPay\Client;

$client = new Client($configuration);
```

#### HTTP Communications

The Nexi client needs to perform HTTP requests.
In order to do that, it automatically detects the best available way to do that:
- if the [cURL PHP extension](https://www.php.net/manual/en/book.curl.php) is available, it uses it (see the `MLocati\Nexi\XPay\HttpClient\Curl` class)
- otherwise, if the [PHP HTTP stream wrapper](https://www.php.net/manual/en/context.http.php) is enabled, it uses it (it requires the [OpenSSL PHP extension](https://www.php.net/manual/en/book.openssl.php) - see the `MLocati\Nexi\XPay\HttpClient\StreamWrapper` class)

You can also provide your own implementation, provided it implements [the `MLocati\Nexi\XPay\HttpClient` interface](https://github.com/mlocati/nexi-xpay/blob/main/src/HttpClient.php).
That way you can easily log the communication with the Nexi servers, as well as customize the HTTP client (for example because you are behind a proxy).

For example, if you want to use your custom HTTP client implementation, you can simply write:

```php
use MLocati\Nexi\XPay\Client;

$myHttpClient = new My\Custom\HttpClient();
$client = new Client($configuration, $myHttpClient);
```

### Sample Usage

The Nexi client provided by this library allows you to use some of the methods you can find in [the Nexi documentation website](https://ecommerce.nexi.it/sviluppatori).

Here's a sample code that allows you to accept payments:

1. Your customer is on apage of your website where you want to place a "Pay with Nexi" button:
   ```php
   <?php
   use MLocati\Nexi\XPay\Dictionary\Currency;
   use MLocati\Nexi\XPay\Dictionary\Language;
   use MLocati\Nexi\XPay\Entity\SimplePay\Request;

   $language = Language::ID_ENG;
   $currency = Currency::ID_EUR;
   $amount = 123.45;
   $internalOrderID = 'internal-order-id';

   $request = new Request();
   $request
       ->setLanguageId($language)
       ->setImportoAsDecimal($amount)
       ->setDivisa($currency)
       ->setCodTrans($internalOrderID)
       ->setUrl('http://your.website/callback')
       ->setUrl_back('http://your.website/payment-canceled')
   ;
   // Store somewhere your $internalOrderID, for example with $_SESSION['order-id'] = $internalOrderID
   ?>
   <form method="POST" action="<?= htmlspecialchars($client->getSimplePaySubmitUrl()) ?>">
       <?php
       foreach ($client->sign($request) as $field => $value) {
           ?>
           <input type="hidden" name="<?= htmlspecialchars($field) ?>" value="<?= htmlspecialchars($value) ?>" />
           <?php
       }
       ?>
       <button type="submit">Pay with Nexi</button>
   </form>
   ```
2. when the users click the "Pay with Nexi" button, they will go to the Nexi servers where they can enter their credit card
3. when the users pay on the Nexi website, they will come back to your website at the URL used in the `setUrl()` above. When that URL is called, you can have some code like this:
   ```
   <?php
   use MLocati\Nexi\XPay\Entity\SimplePay\Callback\Data;

   $data = Data::fromCustomerRequest($_GET);
   $data->checkMac($configuration);
   if ($data->getEsito() === \MLocati\Nexi\XPay\Entity\Response::ESITO_OK) {
       // The order has been paid
   } else {
       // Display an error message and let users come back to the checkout page
   }
