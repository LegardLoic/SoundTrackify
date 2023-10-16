<?php

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class CustomValidatorForCommand
{
    /**
     * Validates an email entered by the user in the CLI
     *
     * @param string|null $emailEntered
     * @return string
     */
    public function validateEmail(?string $emailEntered): string
    {
        if(empty($emailEntered))
        {
            throw new InvalidArgumentException("Veuillez saisir un email.");
        }
        
        if(!filter_var($emailEntered, FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidArgumentException("Email saisi invalide.");
        }
        
        return $emailEntered;
    }

    /**
     * Validates a password entered by the user in the CLI
     *
     * @param string|null $plainPassword
     * @return string
     */
    public function validatePassword(?string $plainPassword): string
    {
        if(empty($plainPassword))
        {
            throw new InvalidArgumentException("Veuillez saisir un mot de passe.");
        }
        
        return $plainPassword;
    }
}