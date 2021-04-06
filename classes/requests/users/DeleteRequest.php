<?php
namespace classes\requests\users;
include $GLOBALS['PATH'] . '/helpers/ValidateHelper.php';
use helpers\ValidateHelper;

class DeleteRequest {

    //params para validação
    public function rules(){
        return [
            'id' => 'required',
            'id' => 'exists:users',
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

                if($err[1] == 'required'){
                    $messages[$err[0]] = 'Obrigatório o envio do '. $err[0];
                }
                
                if($err[1] == 'exists:users'){
                    $messages[$err[0]] = 'ID inválido ou usuário não encontrado';
                }
            }
        }
        return $messages;
    }

}