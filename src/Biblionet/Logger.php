<?php

namespace Biblionet;

class Logger
{

    private $debug = false;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    private function _eol()
    {
        return Helper::isCli() ? PHP_EOL : "<br/>";
    }

    public function enable()
    {
        $this->debug = true;
    }

    public function disable()
    {
        $this->debug = false;
    }

    public function log($type, $origin, $title, $text = "")
    {

        if ($this->debug) {
            if (ob_get_level() == 0) ob_start();
            $Timestamp = new \Datetime;
            if (Helper::isCli()){
                echo $Timestamp->format('Y-m-d H:i') . ": [\033[34m " . $type . " \033[0m] [" . $title . '] [' . $origin . '] ' . $text . $this->_eol();
            } else {
                echo $Timestamp->format('Y-m-d H:i') . ': [\033[31m' . $type . '\033[0m] [' . $title . '] [' . $origin . '] ' . $text . $this->_eol();
            }
            
            ob_flush();
            flush();
            //TODO: add logging to database
        }
    }
}
