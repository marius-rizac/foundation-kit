<?php

abstract class Singleton
{
    /**
     * @param bool $new this will reset singleton (create another object)
     * @return null|static
     */
    static public function getInstance($new=false)
    {
        static $inst = null;
        if ( $new === true && $inst !== null ) {
            $inst = null;
        }
        if ( $inst === null ) {
            $inst = new static();
        }
        return $inst;
    }

    final private function __construct()
    {
        // this method should be called only from static init() method
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
        // no code here
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
        // no code here
    }
}
