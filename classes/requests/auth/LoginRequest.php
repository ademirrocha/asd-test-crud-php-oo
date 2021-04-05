<?php
namespace classes\requests\auth;
include $GLOBALS['PATH'] . '/helpers/ValidateHelper.php';
use helpers\ValidateHelper;

class LoginRequest {

    public function rules(){
        return [
            'email' => 'type:email',
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function validate(){
        $errors = new ValidateHelper;
        $errors = $errors->validateRules($this->rules(), 'POST');

        return $this->messages($errors);
    }

    public function messages($errors){
        $messages = array();
        foreach($errors as $key => $erro){
            foreach($erro as $index => $is_validate){
                $err = explode('.', $index);
                if($err[1] == 'required'){
                    $messages[$err[0]] = 'Obrigatório o envio do '. $err[0];
                }
                if($err[1] == 'type:email'){
                    $messages[$err[0]] = 'Este campo não contem um email válido';
                } 
                
            }
        }
        return $messages;
    }

}