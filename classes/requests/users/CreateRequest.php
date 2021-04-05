<?php
namespace classes\requests\users;
include $GLOBALS['PATH'] . '/helpers/ValidateHelper.php';
use helpers\ValidateHelper;

class CreateRequest {

    public function rules(){
        return [
            'name' => 'required',
            'email' => ['required', 'type:email', 'unique:users'],
            'password_confirmation' => 'required',
            'password' => 'required',
            'password_confirmation' => 'password_confirmation',

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
                if($err[1] == 'exists:users'){
                    $messages[$err[0]] = 'ID inválido ou usuário não encontrado';
                }
                if($err[1] == 'type:email'){
                    if(isset($messages[$err[0]])){
                        array_push($messages[$err[0]], ['Precisamos de um email válido']);
                    }else{
                        $messages[$err[0]] = ['Precisamos de um email válido'];
                    }
                    
                } 
                if($err[1] == 'password_confirmation'){
                    $messages[$err[0]] = 'A confirmação da senha não coincide';
                } 
                
                if($err[1] == 'unique' && $is_validate != 1){
                   
                    if(isset($messages[$err[0]])){
                        array_push($messages[$err[0]], ['Já existe um registro usando este']);
                    }else{
                        $messages[$err[0]] = ['Já existe um registro usando este'];
                    }
                   
                }
                
                
            }
        }
        return $messages;
    }

}