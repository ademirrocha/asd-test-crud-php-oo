<?php
namespace helpers;

require_once $GLOBALS['PATH'] . '/helpers/AbstractGetData.php';

use helpers\AbstractGetData;
use classes\database\Container;
session_start();
class ValidateHelper{

    //metodo para validação de campos obrigatorios
    function required($param, $type){
        if( is_null($this->getValueParam($param, $type))){
            return false;
        }
        return true;
    }

    //metodo verificar se um campo é nulo
    function getValueParam($param, $type){
        
        if($type == 'GET' && isset($_GET[$param]) && $_GET[$param] != ''){
            
            return $_GET[$param];
            
        }else if($type == 'POST' && isset($_POST[$param]) && $_POST[$param] != ''){
            
            return $_POST[$param];

        }else{
            return null;
        }
    }

    //metodo para validar se as senha e a confirmação de senha são iguais
    function password_confirmation($rules, $type){
  
        if( !is_null($this->getValueParam('password', $type)) && 
            !is_null($this->getValueParam('password_confirmation', $type)) 
        ){
            
            if( $this->getValueParam('password', $type) != $this->getValueParam('password_confirmation', $type) ){
                
                return false;
            }
        }
        return true;
    }

    //metodo para validar se o user enviou a senha correta
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

    //metodo para validar se um campo possui um email válido
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

    //metodo para verificar se o valor de um campo está registrado no banco
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

    //metodo para validar campos do tipo unique
    function unique($key, $table, $type){
        
        $value = $this->getValueParam($key, $type);
        
        $conn = Container::getDB();
        $abstractGet = new AbstractGetData($conn);

        $data = $abstractGet->get($table, $key, $value);
        
        if(is_array($data) && isset($data['email']) && $data['email'] == $value){
            echo '<br> Não Passou na validacao<br>';
            return false;
        }
        echo '<br> Passou na validacao<br>';
        return true;
    }

    //metodo para verificar o tipo de validação do campo
    function resultValidate($rules, $key, $rule, $type, $result){
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
            if(! $this->password_confirmation($rules, $type)){
                array_push($result, [$key.'.'.$ruleType[0] => true]);
            }
        }

        if($ruleType[0] == 'correct_password'){
            if(! $this->correct_password($key, $type)){
                array_push($result, [$key.'.'.$ruleType[0] => true]);
            }
        }

        if($ruleType[0] == 'unique'){
            
            if($this->unique($key, $ruleType[1], $type) == false){
                array_push($result, [$key.'.'.$ruleType[0] => false]);
            }

        }

        return $result;
    }
    
    //metodo de validação chamado nos arquivos de *Request
    function validateRules(array $rules, $type){
        $result = array();
        
        foreach($rules as $key => $rule){
            if(is_array($rule)){
                foreach($rule as $option){
                    $result = $this->resultValidate($rules, $key, $option, $type, $result);
                }
            }else{
                $result = $this->resultValidate($rules, $key, $rule, $type, $result);
            }
        }

        return $result;
    }

   
}
