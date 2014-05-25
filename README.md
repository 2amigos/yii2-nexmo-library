Nexmo Library for Yii2
======================

Nexmo Library allows Yii programmers to use the Restful API offered by Mobile Messaging provider
[Nexmo](https://es.nexmo.com/).

[Nexmo](https://es.nexmo.com/) is a cloud-based SMS API that lets you send and receive high volume of messages at wholesale rates.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require "2amigos/yii2-nexmo-library" "*"
```
or add

```json
"2amigos/yii2-nexmo-library" : "*"
```

to the require section of your application's `composer.json` file.

Usage
-----

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

Further Information
-------------------

For further information regarding Nexmo, please visit [its documentation](https://docs.nexmo.com/)


> [![2amigOS!](http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png)](http://www.2amigos.us)

<i>Web development has never been so fun!</i>
[www.2amigos.us](http://www.2amigos.us)