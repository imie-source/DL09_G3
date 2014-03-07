<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'nannyster',
    ),
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
        'fromEmail' => 'no-reply@spsm.fr',
        'smtp' => array(
            'server' => 'smtp.gmail.com',
            'port' => 465, //587
            'security' => 'ssl',
            'username' => 'kevin.balini@gmail.com',
            'password' => 'MP7REccg'
        )
    ),
    'amazon' => array(
        'AWSAccessKeyId' => '',
        'AWSSecretKey' => ''
    )
));
