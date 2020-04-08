<?php

namespace App\Utils\Form;

use Symfony\Component\Form\Form;

/**
 * format error from Form object to simple array where key is field and value is error message
 * 
 * @author Sebastian Chmiel <s.chmiel2@gmail.com>
 */
class ErrorFormatter
{
    /**
     * format errors from form
     *
     * @param Form $form
     * 
     * @return array
     */
    public static function format(Form $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            /** @var Form $child */
            if ($err = self::format($child)) {
                $errors[$key] = $err;
            }
        }

        return $errors;
    }
}
