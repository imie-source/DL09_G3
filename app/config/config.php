<?php

return new \Phalcon\Config(array(
    'application' => array(
        'controllersDir' => APP_DIR . '/controllers/',
        'modelsDir'      => APP_DIR . '/models/',
        'formsDir'       => APP_DIR . '/forms/',
        'viewsDir'       => APP_DIR . '/views/',
        'libraryDir'     => APP_DIR . '/library/',
        'pluginsDir'     => APP_DIR . '/plugins/',
        'cacheDir'       => APP_DIR . '/cache/',
        'baseUri'        => '/',
        'siteTitle'      => 'SPASM',
        'publicUrl'      => 'www.spasm.local',
        'cryptSalt'      => 'eEAdfgfR|_&G&f,+vU]:jFr!!A&+71w1ds76786M754@@s9~8_4L!<74@[N@DyaIP_2My|:+.u>/6m,$D'
    ),
    'mail' => array(
        'fromName' => 'SPASM',
        'fromEmail' => 'no-reply@spasm.fr',
        'smtp' => array(
            'server' => 'smtp.gmail.com',
            'port' => 465, //587
            'security' => 'ssl',
            'username' => '',
            'password' => ''
        )
    ),
    'amazon' => array(
        'AWSAccessKeyId' => '',
        'AWSSecretKey' => ''
    ),
    'db' => array(
        'username' => null,
        'password' => null,
        'host' => null,
        'port' => 32172,
        'database' => 'spasm'
    )
));
