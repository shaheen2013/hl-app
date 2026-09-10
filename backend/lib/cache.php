<?php

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Drivers\Redis\Config as RedisConfig;

// Setup File Path on your config files
CacheManager::setDefaultConfig(new ConfigurationOption($cacheConfig));

// In your class, function, you can call the Cache
// $InstanceCache = CacheManager::getInstance('files');
if (ENV === 'dev' || ENV === 'beta'|| ENV === 'test' && TEST === 'false'){
    $InstanceCache = CacheManager::getInstance('files');
}
elseif (ENV === 'test' && TEST === 'true'){
    $InstanceCache = CacheManager::getInstance('Devnull');
}else {
    $InstanceCache = CacheManager::getInstance("redis", new RedisConfig([
        'host' => REDIS_HOST,
        'port' => REDIS_PORT,
        'optPrefix' => REDIS_PREFIX
    ]));
}

//Get cached item
function getFromCache($key)
{
    global $InstanceCache;
    $CachedString = $InstanceCache->getItem($key);

    if (is_null($CachedString->get()))
        return false;

    return $CachedString;   
}

//Set Cache
function setToCache($key, $data, $time = 604800, $tags='')
{
    global $InstanceCache;
    $CachedString = $InstanceCache->getItem($key);
    $CachedString->set($data)->expiresAfter($time);//in seconds, also accepts Datetime

    if(!empty($tags))
        $CachedString->addTags($tags);

    $InstanceCache->save($CachedString); // Save the cache item just like you do with doctrine and entities
    return;
}

//Delete cache by tag
function deleteCacheByTag($tag)
{
    global $InstanceCache;
    $InstanceCache->deleteItemsByTag($tag);

    return;
}

//Delete cache by tags (array)
function deleteCacheByTags($arrayTags)
{
    global $InstanceCache;
    $InstanceCache->deleteItemsByTags($arrayTags);

    return;
}

function deleteCacheByKey($key)
{
    global $InstanceCache;
    $InstanceCache->deleteItem($key);

    return;
}

/*
*   FX para cambiar un campo de una cache por nombre
*
*   @cacheName (string) nombre de la cache a modificar
*   @key (string) campo a modificar
*   @value (string) nuevo valor que queremos poner al campo $key
*   @seconds (int) segundos para expirar la cache (opcional)
*/
function changeCache($cacheName, $key, $value, $seconds='')
{
    $cache = getFromCache($cacheName);
    $row = $cache->get();

    if(!empty($row) && array_key_exists ($key , $row))
    {
        //get tags
        $tags = $cache->getTags();
        // obtenemos lo que queda para expirar si no nos pasan los segundos
        $cacheExpire = (!empty($seconds)? $seconds : $cache->getTtl());
        //cambiamos el valor del array
        $row[$key]=$value;
        //actulalizamos la cache
        setToCache($cacheName, $row, $cacheExpire, $tags);
    }

    return;
}


//function that deletes cache of $key
//checking first if the id_hotel is part of a chain or not
function deleteCacheByHotelOrChain($key, $id_hotel){
   $id_chain = hotelIdCadena($id_hotel);
   if($id_chain){
      deleteCacheByKey($key . '_chain_' . $id_chain) ;
   } else {
      deleteCacheByKey($key . '_' . $id_hotel) ;
   }
}


//function that sets $result in cache for $key
//checking first if the id_hotel is part of a chain or not
function setToCacheByHotelOrChain($key, $id_hotel, $result, $time = 31536000){
   $id_chain = hotelIdCadena($id_hotel);
   if($id_chain){
      setToCache($key . '_chain_' . $id_chain, $result, $time) ;
   } else {
      setToCache($key . '_' . $id_hotel, $result, $time) ;
   }
}

//function that gets the cache for $key
//checking first if the id_hotel is part of a chain or not
function getFromCacheByHotelOrChain($key, $id_hotel){
   $id_chain = hotelIdCadena($id_hotel);
   if($id_chain){
      getFromCache($key . '_chain_' . $id_chain) ;
   } else {
      getFromCache($key . '_' . $id_hotel) ;
   }
}
