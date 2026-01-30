<?php

define("ROOT", dirname(__DIR__));
const WWW = ROOT . '/public';
const CORE = ROOT . '/core';
const CONFIG = ROOT . '/config';
const APP = '/app';
const VIEWS = APP . '/views';
const VENDOR = APP . '/vendor';

define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');