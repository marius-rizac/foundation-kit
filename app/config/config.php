<?php

if ( ! defined('SYSTEM') )      define('SYSTEM',        dirname(dirname(__DIR__)));
if ( ! defined('LAYOUT') )      define('LAYOUT',        SYSTEM . '/app/layout/');
if ( ! defined('MODULES') )     define('MODULES',       SYSTEM . '/app/modules/');
if ( ! defined('LIBS') )        define('LIBS',          SYSTEM . '/app/libs/');
if ( ! defined('CONFIG') )      define('CONFIG',        SYSTEM . '/app/config/');
if ( ! defined('LESS') )        define('LESS',          LAYOUT . '/less/');
if ( ! defined('PUBLIC_DOC') )  define('PUBLIC_DOC',    SYSTEM . '/public_html');
if ( ! defined('APP_LOGS') )    define('APP_LOGS',      SYSTEM . '/app.log');

$directories = array(
    LAYOUT . '/default/',
    LAYOUT . '/views/',
);
