<?php

namespace App\Helpers;

class LettersConverter
{
    public static function run($words)
    {
        $words = mb_strtolower(trim($words));
        $from = [
            'a', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л',
            'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш',
            'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ',
        ];
        $to = [
            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'y', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh',
            'sch', '', 'i', '', 'e', 'yu', 'ya', '_',
        ];
        $transwords = str_replace($from, $to, $words);
        return preg_replace('/[^0-9a-z_]/', '', $transwords);
    }
}
