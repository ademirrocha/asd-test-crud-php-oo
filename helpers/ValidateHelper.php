<?php
namespace helpers;

class ValidateHelper{

    function required($key, $rule){
        if(isset($_GET[$key]) || isset($_POST[$key])){
            return true;
        }
        return false;

    }
        
    function validateRules(array $rules){
        $result = array();
        $result = array();
        
        foreach($rules as $key => $rule){

            if($rule == 'required'){
                if(! $this->required($key, $rule)){
                   
                    array_push($result, [$key.'.'.$rule => true]);
                }
            }
        }
        return $result;
    }

   
}
