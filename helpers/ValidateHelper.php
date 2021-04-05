<?php
namespace helpers;

require_once $GLOBALS['PATH'] . '/helpers/AbstractGetData.php';

use helpers\AbstractGetData;
use classes\database\Container;

class ValidateHelper{


    function required($key){
        if(isset($_GET[$key]) || isset($_POST[$key])){
            return true;
        }
        return false;
    }

    function getValueParam($param){
        if(!isset($_GET[$param]) && !isset($_POST[$param])){
            return null;
        }else{
            return $_GET[$param] ?? $_POST[$param];
        }
    }

    function password_confirmation(){
        if(! is_null($this->getValueParam('password')) && ! is_null($this->getValueParam('password_confirmation')) ){
            if($this->getValueParam('password') != $this->getValueParam('password_confirmation')){
                return false;
            }
        }
        return true;
    }

    function correct_password($param){
        if(! is_null($this->getValueParam($param))){
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

    function is_email($param){
        $value = $this->getValueParam($param);
        if(is_null($value)){
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_EMAIL) == ''){
            return false;
        }
        return true;
    }

    function exists($key, $table){
        
        $value = $this->getValueParam($key);
        if(is_null($value)){
            return false;
        }
        $conn = Container::getDB();
        $abstractGet = new AbstractGetData($conn);

        $data = $abstractGet->get($table, $key, $value);
        
        if(empty($data)){
            return false;
        }
        return true;
    }
        
    function validateRules(array $rules){
        $result = array();
        $result = array();
        
        foreach($rules as $key => $rule){
            $ruleType = explode(':', $rule);

            if($ruleType[0] == 'exists'){
                if(!$this->exists($key, $ruleType[1])){
                    array_push($result, [$key.'.'.$rule => true]);
                }
            }

            if($ruleType[0] == 'type' && $ruleType[1] == 'email'){
                if(!$this->is_email($key)){
                    array_push($result, [$key.'.'.$rule => true]);
                }
            }

            if($ruleType[0] == 'required'){
                if(! $this->required($key)){
                    array_push($result, [$key.'.'.$ruleType[0] => true]);
                }
            }

            if($ruleType[0] == 'password_confirmation'){
                if(! $this->password_confirmation()){
                    array_push($result, [$key.'.'.$ruleType[0] => true]);
                }
            }

            if($ruleType[0] == 'correct_password'){
                if(! $this->correct_password($key)){
                    array_push($result, [$key.'.'.$ruleType[0] => true]);
                }
            }

        }
        return $result;
    }

   
}
