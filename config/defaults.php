<?php

// Don't change anything here. Use `config.php` to overwrite and set values.

$config = [];

// Path to the execution file of dig
// E.g. `dig` or `/usr/bin/dig`
$config['digPath'] = 'dig';

// Path to the execution file of nsupdate
// E.g. `nsupdate` or `/usr/bin/nsupdate`
$config['nsupdatePath'] = 'nsupdate';

// Define all users, their passwords and zones
$config['user'] = [
    // 'username' => [
    //     // To hash the password, use the PHP function `password_hash`
    //     'password' => 'hashed-password',
    //     // List all zones that the user is allowed to edit
    //     'zones' => [
    //         'example.com',
    //     ],
    //     // Default TTL when creating new records (optional)
    //     'defaultTtl' => 3600,
    // ],
];

// Define all zones
$config['zones'] = [
    // 'example.com' => [
    //     // Used for dig command (@localhost) and nsupdate command (server localhost)
    //     // Optional, default is localhost
    //     'server' => 'localhost',
    //     // Key for retrieving the current zone with AXFR ([hmac:]keyname:secret), see `man dig` for details
    //     // !!! This is supplied as a command line argument in plain text and can therefore be logged in a history file !!!
    //     // Optional, no key used if not set
    //     'transferKey' => 'hmac-sha256:transfer:WW91IGV4cGVjdGVkIHRvIHNlZSBhIHJlYWwga2V5PyE=',
    //     // Key for sending updates to the zone with nsupdate (RFC 2136) ([hmac:]keyname:secret), see `man nsupdate` for details
    //     // !!! This is supplied as a command line argument in plain text and can therefore be logged in a history file !!!
    //     // Optional, no key used if not set
    //     'updateKey' => 'hmac-sha256:update:VGhpcyBpcyBub3QgYSByZWFsIGtleSBlaXRoZXIuLi4=',
    // ],
];

// Duration in seconds, how long the cookie should be valid
$config['cookieLifetime'] = 60 * 60;

// 16 random bytes in hex
// Generate with `openssl rand -hex 16`
$config['encryptionSecret'] = '506c65617365206368616e6765206d65';

// Name of the cookie
$config['cookieName'] = 'dns-control-panel';

// Allowed record types
$config['recordTypes'] = ['A', 'AAAA', 'MX', 'TXT', 'CNAME', 'SRV', 'NS', 'TLSA', 'CAA', 'SOA', 'PTR', 'DS'];
