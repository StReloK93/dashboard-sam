<?php

return [
    'oracle' => [
        'driver' => 'oracle',
        'tns' => env('DB_TNS', ''),
        'host' => '172.16.0.169',
        'port' => '1521',
        'database' => 'EDT',
        'service_name' => 'ORCL',
        'username' => 'EDT',
        'password' => 'Qazx1234*',
        'charset' => env('DB_CHARSET', 'AL32UTF8'),
        'prefix' => env('DB_PREFIX', ''),
        'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
        'edition' => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        'load_balance' => env('DB_LOAD_BALANCE', 'yes'),
        'max_name_len' => env('ORA_MAX_NAME_LEN', 30),
        'dynamic' => [],
        'sessionVars' => [
            'NLS_TIME_FORMAT' => 'HH24:MI:SS',
            'NLS_DATE_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
            'NLS_TIMESTAMP_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
            'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
            'NLS_NUMERIC_CHARACTERS' => '.,',
        ],
    ],
];


// Oracle ga ulanish parametrlari

// EDT =
//   (DESCRIPTION =
//     (ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.0.169)(PORT = 1521))
//     (CONNECT_DATA =
//       (SERVER = DEDICATED)
//       (SID = ORCL)
//     )
//   )
  

  
// EDT   
// Qazx1234*



// return [
//     'oracle' => [
//         'driver'         => 'oracle',
//         'tns'            => env('DB_TNS', ''),
//         'host'           => '172.16.0.30',
//         'port'           => '1521',
//         'database'       => 'NMMC',
//         'service_name'   => 'ORCL',
//         'username'       => 'AM.SOLIEV',
//         'password'       => 'zzzz1111*',
//         // 'charset'        => env('DB_CHARSET', 'AL32UTF8'),
//         // 'prefix'         => env('DB_PREFIX', ''),
//         // 'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
//         // 'edition'        => env('DB_EDITION', 'ora$base'),
//         // 'server_version' => env('DB_SERVER_VERSION', '12c'),
//         // 'load_balance'   => env('DB_LOAD_BALANCE', 'yes'),
//         'dynamic'        => [],
//     ],
// ];