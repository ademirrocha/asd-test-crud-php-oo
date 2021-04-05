<?php
namespace helpers;

require_once $GLOBALS['PATH'] . '/helpers/AbstractGetData.php';

use helpers\AbstractGetData;
use classes\database\Container;
session_start();
class ValidateHelper{

    function required($param, $type){
        if( is_null($this->getValueParam($param, $type))){
            return false;
        }
        return true;
    }

    function getValueParam($param, $type){
        
        if($type == 'GET' && isset($_GET[$param])){
            return $_GET[$param];
        }else if($type == 'POST' && isset($_POST[$param])){
            return $_POST[$param];
        }else{
            return null;
        }
    }

    function password_confirmation($type){
        if(! is_null($this->getValueParam('password', $type)) && ! is_null($this->getValueParam('password_confirmation', $type)) ){
            if($this->getValueParam('password', $type) != $this->getValueParam('password_confirmation', $type)){
                return false;
            }
        }
        return true;
    }

    function correct_password($param, $type){
        if(! is_null($this->getValueParam($param, $type))){
            if(isset($_SESSION['user'])){
                if( $this->getValueParam($param) != $_SESSION['user']['password']){
                    return false;
                }else{
                    return true;
                }
            }
        }
        return true;  //Mudar para false quando implementar session
    }

    function is_email($param, $type){
        $value = $this->getValueParam($param, $type);
        if(is_null($value)){
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_EMAIL) == ''){
            return false;
        }
        return true;
    }

    function exists($key, $table, $type){
        
        $value = $this->getValueParam($key, $type);
        
        if(is_null($value)){
            return false;
        }
        $conn = Container::getDB();
        $abstractGet = new AbstractGetData($conn);

        $data = $abstractGet->get($table, $key, $value);
        
        if($data == ''){
            return false;
        }
        return true;
    }
        
    function validateRules(array $rules, $type){
        $result = array();
        $result = array();
        
        foreach($rules as $key => $rule){
            $ruleType = explode(':', $rule);

            if($ruleType[0] == 'exists'){
                if(!$this->exists($key, $ruleType[1], $type)){
                    array_push($result, [$key.'.'.$rule => true]);
                }
            }

            if($ruleType[0] == 'type' && $ruleType[1] == 'email'){
                if(!$this->is_email($key, $type)){
                    array_push($result, [$key.'.'.$rule => true]);
                }
            }

            if($ruleType[0] == 'required'){
                if(! $this->required($key, $type)){
                    array_push($result, [$key.'.'.$ruleType[0] => true]);
                }
            }

            if($ruleType[0] == 'password_confirmation'){
                if(! $this->password_confirmation($type)){
                    array_push($result, [$key.'.'.$ruleType[0] => true]);
                }
            }

            if($ruleType[0] == 'correct_password'){
                if(! $this->correct_password($key, $type)){
                    array_push($result, [$key.'.'.$ruleType[0] => true]);
                }
            }

        }
        return $result;
    }

   
}
