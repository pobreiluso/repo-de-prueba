<?php

define('ROOT_DIR', '/');

define ('VISUALIZEUS_CONSUMER_KEY', 'server-suplied-consumer-key-here');
define ('VISUALIZEUS_CONSUMER_SECRET', 'server-suplied-consumer-secret-here');

require_once( './visualizeus/oauth/oauth-php/OAuthServer.php');
include_once( './visualizeus/oauth/oauth-php/OAuthRequester.php');
session_start();

?>
