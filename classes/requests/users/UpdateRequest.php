<?php
namespace classes\requests\users;
include $GLOBALS['PATH'] . '/helpers/ValidateHelper.php';
use helpers\ValidateHelper;

class UpdateRequest {

    public function rules(){
        return [
            'id' => 'required',
            'id' => 'exists:users',
            'name' => 'required',
            'email' => 'type:email',
            'email' => 'required',
            'old-password' => 'required',
            'password_confirmation' => 'password_confirmation',
            'password' => 'correct_password'
        ];
    }

    public function validate(){
        $errors = new ValidateHelper;
        $errors = $errors->validateRules($this->rules());

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
                if($err[1] == 'exists:users'){
                    $messages[$err[0]] = 'ID inválido ou usuário não encontrado';
                }
                if($err[1] == 'type:email'){
                    $messages[$err[0]] = 'Este campo não contem um email válido';
                } 
                if($err[1] == 'password_confirmation'){
                    $messages[$err[0]] = 'A confirmação da senha não coincide';
                } 
                if($err[1] == 'correct_password'){
                    $messages[$err[0]] = 'Senha incorreta';
                }
                
            }
        }
        return $messages;
    }

}