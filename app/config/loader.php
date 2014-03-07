<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    array(
        'Nannyster\Controllers' => $config->application->controllersDir,
        'Nannyster\Models' => $config->application->modelsDir,
    'Nannyster\Forms' => $config->application->formsDir,
    'Nannyster' => $config->application->libraryDir
    )
)->register();
