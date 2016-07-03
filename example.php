<?php

require_once('RedisConnection.class.php');
require_once('RedisKey.class.php');

$redisModel                            = RedisConnection::getInstance();

$filed                                 = 'test_field';
$value                                 = 'test_value';
$this->_redisModel->hSetEx(Base_RedisKey::KEY_CACHE, Base_RedisKey::KEY_CACHE_TTL, $filed, $value);