# Nexmo Library for Yii2

[![Latest Version](https://img.shields.io/github/tag/2amigos/yii2-nexmo-library.svg?style=flat-square&label=release)](https://github.com/2amigos/yii2-nexmo-library/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/2amigos/yii2-nexmo-library/master.svg?style=flat-square)](https://travis-ci.org/2amigos/yii2-nexmo-library)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/2amigos/yii2-nexmo-library.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-nexmo-library/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/2amigos/yii2-nexmo-library.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-nexmo-library)
[![Total Downloads](https://img.shields.io/packagist/dt/2amigos/yii2-nexmo-library.svg?style=flat-square)](https://packagist.org/packages/2amigos/yii2-nexmo-library)

Nexmo Library allows Yii programmers to use the Restful API offered by Mobile Messaging provider
[Nexmo](https://es.nexmo.com/).

[Nexmo](https://es.nexmo.com/) is a cloud-based SMS API that lets you send and receive high volume of messages at wholesale rates.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require 2amigos/yii2-nexmo-library:~1.0
```

or add

```
"2amigos/yii2-nexmo-library": "~1.0"
```

to the `require` section of your `composer.json` file.

## Usage

First you have to create your [Nexmo account](Nexmo API credentials) and get your API_KEY and API_SECRET.


```php
// to send an sms message
$sms = new dosamigos\Sms(['key' => 'API_KEY', 'secret' => 'API_SECRET', 'from' => 'SENDERID']);

// lets call the API to get a json response
$sms->format = 'json';

// send a message with an optional parameter (see Nexmo doc for more optional parameters)
$response = $sms->sentText('RECIPIENTSNUMBER', 'Hello World!', ['clientRef' => 'YOURCLIENTREF']);

// if a response expects a JSON object, it will return as an array, if format was a XML, it will return an object.
echo $response['message-count']; // the number of parts the message was split into

```

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Antonio Ramirez](https://github.com/tonydspaniard)
- [Alexander Kochetov](https://github.com/creocoder)
- [All Contributors](https://github.com/2amigos/yii2-nexmo-library/graphs/contributors)

## License

The BSD License (BSD). Please see [License File](LICENSE.md) for more information.

<blockquote>
    <a href="http://www.2amigos.us"><img src="http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png"></a><br>
    <i>web development has never been so fun</i><br>
    <a href="http://www.2amigos.us">www.2amigos.us</a>
</blockquote>
