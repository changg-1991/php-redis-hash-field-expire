<?php
/**
 * Redis Hash Field 超时控制
 * 脚本建议用 crontab 启动，* * * * * /usr/bin/php /ABSOLUTE_PATH/hashFieldTimeoutCenter.php > /dev/null 2>&1
 */

require_once('RedisConnection.class.php');
require_once('RedisKey.class.php');

ini_set('default_socket_timeout', -1); # 为了防止Redis出现read error on connection

define('PID_FILE_NAME',                'hashFieldTimeoutCenter.pid');

# 防止脚本重复执行
if (PHP_OS == 'Linux') {
    $baseDir                           = dirname(__FILE__);
    $pidFile                           = $baseDir.'/'.PID_FILE_NAME;
    if (is_file($pidFile)) {
        $oldPid                        = file_get_contents($pidFile);
        $cmd                           = 'ps aux | awk \'{print $2}\' | grep -e "^'.$oldPid.'$" | wc -l';
        $ret                           = `$cmd`;
        if ($ret > 0) {
            die('Script still running.');
        }
    }

    $pid                               = getmypid();
    file_put_contents($pidFile, $pid);
}

$redisModel                            = RedisConnection::getInstance();

while (true) {
    $expireTime                        = time();
    $items                             = $redisModel->link->zRangeByScore(RedisKey::GLOBAL_EXPIRE_ZSET, '-inf', $expireTime);
    foreach ($items as $item) {
        list($key, $field)             = explode('#', $item);
        $redisModel->link->hDel($key, $field);
        $redisModel->link->zRem(RedisKey::GLOBAL_EXPIRE_ZSET, $item);
    }
    sleep(1);
}