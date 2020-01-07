## Run Instructions

In order to run files located under this path you will need to follow a few short steps. Runing them is very easy :) 

Once you choose the desired example you will need to:

#### Step 1 - Change credentials
Set credentials in <path to application>/configuration/application.config.php or
Every example file contains the following block of the code where it is possible to set 
credentials 

```php
$sipIpAccessControlList -> setOptions(array(
        "account_sid"   => {AccountSid},
        "auth_token"    => {AuthToken},
    ));
```

`{AccountSid}` and `{AuthToken}` must be replaced with real credentials which you can find at [Avaya CPaaS dashboard](https://accounts.zang.io/#/dashboard)


#### Step 2 - Change parameters ( if needed )

if you are trying to run Send SMS example you will need to update following block of the code:

```php
$sentSms = $sms -> sendSms(array(
        'From'          => '(XXX) XXX-XXXX',
        'To'            => '(XXX) XXX-XXXX',
        'Body'          => "This is a test message.",
        'AllowMultiple' => "False"
    ));
```

where `From` and `To` must be valid phone numbers.
    
    
#### Step 3 - Run the code!

There are many ways to run the example code. To run it in terminal, perform the following commands:

**You must have PHP 5.3 or greater installed in order to run any example!**

```shell
cd zang-php/examples
php send-sms.php
```