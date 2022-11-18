<?php

namespace App\HelperClasses;

class CreateSlug
{
    public function createSlug($str, $delimiter = '-')
    {
        $unwanted_array = ['é'=>'e', 'à' => 'a', 'ç' => 'c', 'Ç' => 'c', 'Ê' => 'e', 'ê' => 'e', 'À' => 'q', 'Ô' => 'o', 'ô' => 'o', 'È' => 'e', 'è' => 'e', 'É' => 'e']; // French letters
        $str = strtr( $str, $unwanted_array );
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    }
}
