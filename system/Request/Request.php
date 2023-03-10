<?php

namespace System\Request;

use System\Request\Traits\HasRunValidation;
use System\Request\Traits\HasValidationRules;
use System\Request\Traits\HasFileValidationRules;

class Request
{
    use HasValidationRules, HasFileValidationRules, HasRunValidation;

    protected $errorExist = false;
    protected $request;
    protected $files = null;
    protected $errorVariablesName = [];

    public function __construct()
    {
        if(isset($_POST))
        {
            return $this->postAttributes();
        }
        if(!empty($_FILES))
        {
            $this->files =  $_FILES;
        }
        $rules = $this->rules();
        empty($rules) ? : $this->run($rules);

        $this->errorRedirect();
    }

    protected function rules()
    {
        return [];
    }

    protected function run($rules)
    {
        // faire l'ensemble de l'upload de l'image
        foreach($rules as $att => $values)
        {
            // Nous allons séparer les champs par le pipe
            $ruleArray = explode('|', $values);
            if(in_array('file', $ruleArray))
            {
                // Nous allons détruire les éléments dans le tableau

                // Je vais rechercher dans le tableau
                unset($ruleArray[array_search('file', $ruleArray)]);

                //Nous allons passer dans une nouvelle fonction pour la validation
                $this->fileValidation($att, $ruleArray);

            }
            // Pour la validation des nombres (peut importe l'origin du nombre)
            elseif(in_array('number', $ruleArray))
            {
                $this->numberValidation($att, $ruleArray);
            }
            else
            {
                // Validation dite normal
                $this->normalValidation($att, $ruleArray);
            }
        }
    }

    protected function file($name)
    {
        return isset($this->files[$name]) ? $this->files[$name] : false;
    }

    protected function postAttributes()
    {
        foreach($_POST as $key => $value)
        {
            $this->$key = htmlentities($value);
            $this->request[$key] = htmlentities($value);        
        }
    }

    public function all()
    {
        return $this->request;
    }
}