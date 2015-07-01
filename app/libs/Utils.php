<?php

abstract class Utils
{
    public static function inc($path)
    {
        // remove //
        $path = preg_replace('/([\/]+)/', '/', $path);
        // remove ../
        $path = preg_replace('/(\.\.\/)/', '', $path);
        // remove ./
        $path = preg_replace('/(\.\/)/', '', $path);

        return $path;
    }

    public static function p($data='', $title='')
    {
        if ( php_sapi_name() == 'cli' ) {
            if ( $title != '' ) {
                echo $title, "\n";
            }
            echo print_r($data, true);
            echo "\n";
        } else {
            if ( trim($title) !== '' ) {
                printf(
                    '<fieldset><legend>%s</legend><pre>%s</pre></fieldset>',
                    $title,
                    print_r($data, true)
                );
            } else {
                printf('<pre>%s</pre>', print_r($data, true));
            }
        }
    }

    public static function pd($data='', $title='')
    {
        self::p($data, $title);
        die;
    }

    public static function nice_url_title($str, $separator = 'dash', $lowercase = true)
    {
        if ( $separator == 'dash' ) {
            $search     = '_';
            $replace    = '-';
        } else {
            $search     = '-';
            $replace    = '_';
        }

        $tr = array(
            'Î'=>'I',
            'Ă'=>'A',
            'Â'=>'A',
            'î'=>'i',
            'â'=>'a',
            'ă'=>'a',
            'Ş'=>'S',
            'ş'=>'s',
            'Ţ'=>'T',
            'ţ'=>'t',
        );
        $str = strtr($str, $tr);


        $trans = array(
            '&\#\d+?;'              => '',
            '&\S+?;'                => '',
            '\s+'                   => $replace,
            '[^a-z0-9\-_]'          => '',
            $replace.'+'            => $replace,
            $replace.'$'            => $replace,
            '^'.$replace            => $replace,
            '\.+$'                  => ''
        );

        $str = ($lowercase === TRUE) ? strtolower(strip_tags($str)) : strip_tags($str);

        foreach ($trans as $key => $val) {
            $str = preg_replace("#".$key."#i", $val, $str);
        }

        return trim(stripslashes($str));
    }
}
