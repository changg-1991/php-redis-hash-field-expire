<?php
/**
  * Redis Key定义类
  */
class RedisKey {

    /**
     * 全局Hash超时有序集合
     * Sorted Set key:global_expire_zset score:$expireTimestamp item:$key#$field
     */
    const GLOBAL_EXPIRE_ZSET           = 'global_expire_zset';

    /**
     * key缓存
     * TTL:100
     * Hash key:key_cache field:$account value:$key 
     */
    const KEY_CACHE                    = 'key_cache';
    const KEY_CACHE_TTL                = 100;
}