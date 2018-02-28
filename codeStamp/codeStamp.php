<?php

namespace codeStamp;

class codeStamp {

    private $timestamp_start;
    private $timestamp_prev;
    private $timestamp_delta;
    private $timestamp_runing;
    
    function __construct()
    {
        $this->timestamp_start = $this->_codeStampMicrotime();
        $this->timestamp_delta = 0;
        $this->timestamp_runing = 0;
}

    private function _codeStampMicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function codeStampShow( $msg )
    {
        $this->timestamp_delta = $this->_codeStampMicrotime() - $this->timestamp_prev;
        $this->timestamp_runing = $this->_codeStampMicrotime() - $this->timestamp_start;

        printf("\n%01.3f [+%01.3f] : %s\n\n", round( $this->timestamp_runing, 2), round( $this->timestamp_delta, 2), $msg );
        $this->timestamp_prev = $this->_codeStampMicrotime();
    }

}
