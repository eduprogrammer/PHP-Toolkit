<?php

/*

    Copyright 2022. Eduardo Programador
    www.eduardoprogramador.com 
    consultoria@eduardoprogramador.com

    This class provide methods
    for building pseudorandom numbers.
    It does not have default constructor, 
    so you can't instantiate it directly.
    You must call the static methods for use 
    the class algorithms.
*/

class Random {

    /*
        Private constructor.
        It can not be initialized.
    */
    private function __construct() {

    }

    /*
        Class destructor.
    */
    function __destruct() {}

    /*
        Returns a string that contains pseudorandom numbers.
        The total of numbers must be informed in the 
        method parameter.        
    */
    public static function getRandom($numbers) : string {
        $res = '';
    
        for($i = 0; $i < $numbers; $i++) {
            $res .= rand(0,9);
        }
    
        return $res;
    }

    /*
        Returns a string that contains some ramdom characters,
        including letters, numbers and special ones.

        The total of characters must be informed 
        in the method parameter.
    */
    public static function getSpecialRandom($numbers) : string {
        $res = '';

        $letters = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
        $numbers = ['0','1','2','3','4','5','6','7','8','9'];
        $special = ['@','#','$','%','&','*','?'];

        for($i = 0; $i < $numbers; $i++) {
            $chosen = rand(0,2);

            switch ($chosen) {
                case 0:
                    $upper = rand(0,1);
                    if($upper == 0) {
                        $res .= $letters[rand(0,count($letters) - 1)];
                    } elseif($upper) {
                        $res .= strtoupper($letters[rand(0, count($letters) - 1)]);
                    }
                    break;

                case 1:
                    $res .= $numbers[rand(0, count($numbers) - 1)];
                    break;

                case 2:
                    $res .= $special[rand(0, count($special) - 1)];
                    break;
                
                default:
                    $res .= $numbers[rand(0, count($numbers) - 1)];
                    break;
            }
        }
    }

    

}

?>