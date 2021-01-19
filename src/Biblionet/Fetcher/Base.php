<?php
namespace Biblionet\Fetcher;

use GuzzleHttp\Client;
use Biblionet\Helper;

class Base
{
    protected $client;
    protected $username = '';
    protected $password = '';
    protected $debug = false;

    function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client([
            'base_uri' => 'https://biblionet.gr/wp-json/biblionetwebservice/',
            'timeout'  => 10.0
        ]);

        $this->enableDebug();
    }

    public function enableDebug(){
        $this->debug = true;
    }

    public function disableDebug(){
        $this->debug = false;
    }

    protected function log($type, $title, $text = ""){

        if ($this->debug){
            if (ob_get_level() == 0) ob_start();
            $Timestamp = new \Datetime; 
            echo $Timestamp->format('Y-m-d H:i') . ': [' . $type . '] [' . $title . '] ' . $text . (Helper::isCli() ? PHP_EOL : "<br/>");
            ob_flush();
            flush();
            //TODO: add logging to database
        }
        
    }

}
