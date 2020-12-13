<?php

namespace App\Helpers;

use ReflectionMethod;

class ObjectHelper {
    public static function existsMethod($obj, $methodName){

        $methods = self::getMethods($obj);

        $neededObject = array_filter(
            $methods,
            function ($e) use($methodName) {
                return $e->Name == $methodName;
            }
        );

        if (is_array($neededObject))
            return true;

        return false;
    }

    public static function getMethods($obj){

        $var = new \ReflectionClass($obj);

        return $var->getMethods(ReflectionMethod::IS_PROTECTED);
    }
}