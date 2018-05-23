<?php

echo "Welcome to the EDD HelpScout testsuite." . PHP_EOL;

define( 'GITHUB_HELPSCOUT_API_PATH', '/my-api-path' );
define( 'GITHUB_HELPSCOUT_SECRET_KEY', 'my-random-string' );

require __DIR__ . '/../vendor/autoload.php';