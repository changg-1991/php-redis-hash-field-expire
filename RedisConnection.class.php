<?php
/**
 * Redis 连接类
 * RedisConnection 被设计为单例模式
 */

require_once('RedisKey.class.php');

class RedisConnection {

    private static $_instance;

    private static $_addr;
    private static $_port;
    private static $_pwd;
    private static $_database;

    public  $link;
    
    private function __construct() {
        self::$_addr                   = '';
        self::$_port                   = '';
        self::$_pwd                    = '';
        self::$_database               = '';

        $this->_redisConnection();
    }
    

    private function __clone() {}


    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance           = new self();
        }

        return self::$_instance;
    }

    
    private function _redisConnection() {
        $this->link                    = new Redis();
        $this->link->connect(self::$_addr, self::$_port);

        $this->link->auth(self::$_pwd);

        $this->link->select(self::$_database);
    }


    /**
     * Hash Expire Set
     * @param string $key Hash Key
     * @param integer $seconds 生存期，单位s
     * @param string $field Hash Field
     * @param string $value 值
     * @return void
     */
    public function hSetEx($key, $seconds, $field, $value) {
        $this->link->hSet($key, $field, $value);

        $expireTimestamp               = time() + $seconds;
        $item                          = $key.'#'.$field;
        $this->link->zAdd(RedisKey::GLOBAL_EXPIRE_ZSET, $expireTimestamp, $item);
    }
}