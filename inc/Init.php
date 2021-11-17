<?php

namespace Cbf\Inc;

final class Init{

    public static function get_services(){

        return [
            Base\DefaultSettings::class,
            //Base\Pages::class,
            Base\Enqueue::class,
            Base\Registration::class,
            Base\Shortcodes::class
        ] ;
    }

    public static function register_services(){

        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if(method_exists( $service , 'register')){
                $service->register();
            }
        }

    }

    private static function instantiate($class){

        $service = new $class();
        return $service;
    }

}
?>
