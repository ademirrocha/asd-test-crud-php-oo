<?php
namespace classes\requests\users;
include $GLOBALS['PATH'] . '/helpers/ValidateHelper.php';
use helpers\ValidateHelper;

class CreateRequest {

    //params para validação
    public function rules(){
        return [
            'name' => 'required',
            'email' => ['required', 'type:email', 'unique:users'],
            'password_confirmation' => 'required',
            'password' => 'required',
            'password_confirmation' => 'password_confirmation',

        ];
    }

    //metodo de validaçao que é chamado no controller
    public function validate(){
        
        $errors = new ValidateHelper;
        $errors = $errors->validateRules($this->rules(), 'POST');
        
        return $this->messages($errors);
    }

    //Cria as menssagens de erros
    public function messages($errors){
        $messages = array();

        foreach($errors as $key => $erro){
            
            foreach($erro as $index => $is_validate){

                $err = explode('.', $index);
                
                //para validar campos obrigatórios
                if($err[1] == 'required'){
                    $messages[$err[0]] = 'Obrigatório o envio do '. $err[0];
                }

                //para verificar se existe um valor no banco de dados
                if($err[1] == 'exists:users'){
                    $messages[$err[0]] = 'ID inválido ou usuário não encontrado';
                }

                //verificar campo type email
                if($err[1] == 'type:email'){

                    if(isset($messages[$err[0]])){
                        array_push($messages[$err[0]], ['Precisamos de um email válido']);
                    }else{
                        $messages[$err[0]] = ['Precisamos de um email válido'];
                    }
                } 

                //verificar se as senhas são iguais
                if($err[1] == 'password_confirmation'){
                    $messages[$err[0]] = 'A confirmação da senha não coincide';
                } 
                
                //validar campo do tipo unique
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