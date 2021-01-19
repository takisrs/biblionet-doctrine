<?php

namespace Biblionet;

class Helper{

    public static function isJson($str){
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function isCli(){
        return PHP_SAPI === 'cli';
    }

}