<?php

echo exec( 'composer update' ) . "\n";

require_once( __DIR__ . '/../vendor/autoload.php' );

require_once( __DIR__ . '/../ParamProcessor.php' );

require_once( __DIR__ . '/testLoader.php' );
