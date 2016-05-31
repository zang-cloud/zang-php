## Run Instructions

In order to run files located under this path you will need to follow a few short steps. Runing them is very easy :) 

Once you choose the desired example you will need to:

#### Step 1 - Change credentials

Every example file contains the following block of the code:

```php
# A 36 character long AccountSid is always required. It can be described
# as the username for your account
$account_sid = '{AccountSid}';

# A 34 character long AuthToken is always required. It can be described
# as your account's password
$auth_token  = '{AuthToken}';
```

`{AccountSid}` and `{AuthToken}` must be replaced with real credentials which you can find at [Zang dashboard](https://www.zang.io/dashboard)


#### Step 2 - Change parameters ( if needed )

if you are trying to run the [Send SMS](https://github.com/Zang/zang-php/blob/master/examples/send-sms.php) example you will need to update following block of the code:

```php
$sms_message = $zang->create('sms_messages', array(
    'From' => '(XXX) XXX-XXXX',
    'To'   => '(XXX) XXX-XXXX',
    'Body' => "This is an SMS message sent from the Zang PHP wrapper! Easy as 1, 2, 3!"
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