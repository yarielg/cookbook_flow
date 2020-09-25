<?php

/*
*
* @package yariko
*
*/

namespace Memd\Inc\Base;

class Deactivate{

    public static function deactivate(){
        flush_rewrite_rules();
    }
}
