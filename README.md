
zang-php
==========

This PHP library is an open source tool built to simplify interaction with the [Avaya CPaaS](http://www.zang.io) telephony platform. Avaya CPaaS makes adding voice and SMS to applications fun and easy.

For more information about Avaya CPaaS, please visit: [Avaya OneCloud™️ CPaaS ](http://zang.io/products/cloud)

To read the official documentation visit [Avaya CPaaS Docs](http://docs.zang.io/aspx/docs)


---
Installation
============
##### PHP 5.3+ Required (7.0+ recommended)

#### Via GitHub clone

Access terminal and run the following code:

```shell
  $ cd ~
  $ git clone https://github.com/zang-cloud/zang-php
  $ cd zang-php
```

#### Via Download

##### Step 1

Download the [.zip file](https://github.com/zang-cloud/zang-php/archive/master.zip).

##### Step 2

Once the .zip download is complete, extract it and get started with the examples below.


---

Usage
======

### REST

See the [Avaya CPaaS REST API documentation](http://docs.zang.io/aspx/rest) for more information.
##### Configuration
First set configuration parameters in "configuration/applications.config.ini" file

Normally you'll want to just set your Avaya CPaaS Platform *AccountSid* and *AuthToken*. But you can also change the base API URL. The default value is https://api.zang.io/v2/.

##### Set Base URL and API Version Example

The base API URL and api version for US(new) and EU deployments are:
US: https://api-us.cpaas.avayacloud.com/v2
EU: https://api-eu.cpaas.avayacloud.com/v2

To change baseUrl to EU deployment set following in applications.config.ini

```php
API_URL=https://api-eu.cpaas.avayacloud.com/v2/
```

##### Send SMS Example

```php
# First we must import the actual Avaya CPaaS library
require_once '../connectors/Sms.php';

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {
    # Now what we need to do is instantiate the library
    # If Credentials are set in <path to application>/configuration/application.config.php application 
    # is going to use this thata. If is nessesary credentals can be overriden as is shown below
    $sms = Sms::getInstance();

    # This is the best approach to setting multiple options recursively
    # Take note that you cannot set non-existing options
    # Credentials can be set in <path to application>/configuration/application.config.php and be used 
    # as is shown below or could be set to some custom value
    $sms -> setOptions(array(
        "account_sid"   => $_ENV["ACCOUNT_SID"],
        "auth_token"    => $_ENV["AUTH_TOKEN"],
    ));

    # NOTICE: The code below will send a new SMS message.

    # Zang_Helpers::filter_e164 is a internal wrapper helper to help you work with phone numbers and their formatting
    # For more information about E.164, please visit: http://en.wikipedia.org/wiki/E.164
    $sentSms = $sms -> sendSms(array(
        'From'          => '(XXX) XXX-XXXX',
        'To'            => '(XXX) XXX-XXXX',
        'Body'          => "This is a test message.",
        'AllowMultiple' => "False"
    ));

    # If you wish to get back the full response object/array then use:
    echo "<pre>";
    print_r($sentSms->getResponse());
    echo "</pre>";
    # OR
    echo $sentSms
    
    # If you wish to get SID then use
    echo $sentSms->sid


} catch (ZangException $e){
    echo "<pre>";
    print_r( "Exception message: " . $e -> getMessage() . "<br>");
    print_r( "Exception code: " . $e -> getCode() . "<br>");
    print_r(  $e -> getTrace() );
    echo "</pre>";
}
```

### InboundXML

InboundXML is an XML dialect which enables you to control phone call flow. For more information please visit the [Avaya CPaaS InboundXML documentation](http://docs.zang.io/aspx/inboundxml).

##### <Say> Example

```php
<?php

# First we must import the actual Avaya CPaaS library
require_once "../library/ZangApi/InboundXML.php";

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {
    # Now what we need to do is instantiate the library
    $inboundXml = new Zang_InboundXML();

    $inboundXml -> say( "Welcome to Avaya CPaaS. This is a sample InboundXML document.", array(
        "voice" => "male"
    ));

    # If you wish to get back validated Inbound XML as string then use:
    echo $inboundXml;


} catch (Exception $e){
    echo $e->getMessage();
    echo "<br><pre>";
    print_r( $e->getTrace() );
    echo "</pre>";
}
```

will render

```xml
<?xml version="1.0"?>
<Response><Say voice="male">Welcome to Avaya CPaaS. This is a sample InboundXML document.</Say></Response>

```

