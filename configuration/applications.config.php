<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 6.4.2017.
 * Time: 20:30
 */

# A 36 character long AccountSid is always required. It can be described
# as the username for your account
#
#define SID here and use it in the rest of application
define( 'ACCOUNT_SID', "TestAccountSid" );

## A 34 character long AuthToken is always required. It can be described
# as your account's password
#
# Auth token - define token here and use it in the rest of application
define( 'AUTH_TOKEN', "TestToken" );

#Zang API URL. Default value is "https://api.zang.io/v2/".
define( 'API_URL', "https://api.zang.io/v2/" );

#Zang API URL port. If default URL is used application automatically set appropriate port and API_PORT value should be empty.
#If URL is set to "https://...." applications automatically set port 443. If you wish to override this value define new below.
#If URL is set to "http://...." applications automatically set port 80. If you wish to override this value define new below.
#If URL is set to some other value please define port to use!
define( "API_PORT", "" );