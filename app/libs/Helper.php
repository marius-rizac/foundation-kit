<?php

/**
 * This class will hint you the available object methods
 *
 */
class Helper
{
    public function __call($a, $b)
    {
        $class_name = get_class($this);

        Utils::p('Method <b>' . $a . '</b> for class <b>' . get_called_class() . '</b> does not exists ');
        Utils::p(get_class_methods($class_name), 'Available methods for class <b>' . $class_name . '</b>:');
        Utils::p(debug_backtrace(), 'Backtrace');
    }
}
