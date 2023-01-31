<?php

namespace System\Request\Traits;

trait HasFileValidationRules
{
    // fonction qui permet de refuser une taille trop grande
    protected function maxFile($name, $size)
    {
        $size = $size * 1024;
        if($this->checkFirstError($name) && $this->checkFileExist($name))
        {
            if($this->files[$name]['size'] > $size) 
            {
                $this->setError($name, "$name size must be lower than" . ($size/1024) . " kb");
            }
        }
    }

    // fonction qui permet de refuser une taille trop petite
    protected function minFile($name, $size)
    {
        $size = $size * 1024;
        if($this->checkFirstError($name) && $this->checkFileExist($name))
        {
            if($this->files[$name]['size'] < $size) 
            {
                $this->setError($name, "$name size must be greater than" . ($size/1024) . " kb");
            }
        }
    }
    // Pour autoriser certain type mime (format du fichier)
    private function fileType($name, $typesArray)
    {
        if($this->checkFirstError($name) && $this->checkFileExist($name))
        {
            // On va chercher le type d'imgage que je vais autoriser
            $currentTypeFile = explode("/", $this->files[$name]['type'])[1];
            if(!in_array($currentTypeFile, $typesArray))
            {
                // Message d'erreur
                $this->setError($name, "$name type must be". implode(",", $typesArray));
            }
        }
    }

    protected function fileRequired($name)
    {
        // Fonction pour le défaut des images (sans nom)
        if(!isset($this->files[$name]['name']) || empty($file[$name]['name']) && $this->checkFirstError($name))
        {
            $this->setError($name, "$name is required");
        }
    }

    protected function fileValidation($name, $ruleArray)
    {
        foreach($ruleArray as $rule)
        {
            // Aller chercher si le nom est correcte
            if($rule == 'required')
            {
                $this->fileRequired($name);
            }
            elseif(strpos($rule, "mimes:") === 0)
            {
                // Aller chercher la deuxieme partie du type mime (extension)
                $rule = str_replace("mimes:", "", $rule);
                $rule = explode(",", $rule);
                $this->fileType($name, $rule);
            }
            elseif(strpos($rule, "max:") === 0)
            {
                // Gerer la taille des photos maximum
                $rule = str_replace("max:", "", $rule);
                $this->maxFile($name, $rule);
            }
            elseif(strpos($rule, "min:") === 0)
            {
                // Gerer la taille des photos minmum
                $rule = str_replace("min:", "", $rule);
                $this->minFile($name, $rule);
            }
        }
    }
}