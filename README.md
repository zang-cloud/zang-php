zang-php
==========

This PHP library is an open source tool built to simplify interaction with the [Zang](http://www.zang.io) telephony platform. Zang makes adding voice and SMS to applications fun and easy.

For this libraries full documentation visit: http://zang.github.com/zang-cloud/zang-php

For more information about Zang visit:  [zang.io/features](http://www.zang.io/features) or [zang.io/docs](http://www.zang.io/docs)

---

Installation
============

#### Via Pear

At the moment we don't support the PEAR package but will in the near future!

##### PHP 5.2+ Required (5.3+ recommended)

#### Via GitHub clone

Access terminal and run the following code:

```shell
  $ cd ~
  $ git clone https://github.com/zang-cloud/zang-php.git
  $ cd zang-php
```

#### Via Download

##### Step 1

Download the [.zip file](https://github.com/zang-cloud/zang-php/zipball/master).

##### Step 2

Once the .zip download is complete, extract it and get started with the examples below.


---

Usage
======

### REST

[Zang REST API documentation](http://www.zang.io/docs/)

##### Send SMS Example

```php
<?php
require_once '../library/Zang.php';

// Set up your Zang credentials
$zang = Zang::getInstance();
$zang -> setOptions(array(
    'account_sid'       => '{AccountSid}',
    'auth_token'        => '{AuthToken}',
));

// Send the SMS
$sms_message = $zang->create('sms_messages', array(
    'From' => '+12223334444',
    'To'   => '+15550001212',
    'Body' => "This is an SMS message sent from the Zang PHP wrapper! Easy as 1, 2, 3!"
));

print_r($sms_message);
```

### InboundXML

InboundXML is an XML dialect which enables you to control phone call flow. For more information please visit the [Zang InboundXML documentation](http://www.zang.io/docs/api/inboundxml/)

##### <Say> Example

```php
<?php

require_once('library/Zang/InboundXML.php');

$inbound_xml = new Zang_InboundXML();

$inbound_xml->say('Welcome to Zang. This is a sample InboundXML document.', array('voice' => 'man'));

echo $inbound_xml;
```

will render

```xml
<?xml version="1.0" encoding="UTF-8"?>
<Response>
  <Say voice="man">Welcome to Zang. This is a sample InboundXML document.</Say>
</Response>
```

Just host that PHP file somewhere, buy a phone number in your [Zang Account Dashboard](https://www.zang.io/dashboard/) and assign the URL of that PHP page to your new number. Whenever you dial that number, the InboundXML this page generates will be executed and you'll hear our text-to-speech engine say welcome.
