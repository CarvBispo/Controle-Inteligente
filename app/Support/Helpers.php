<?php

if (!function_exists('getDisplayQtyValues')) {


    /**
     * Obtem a lista de quantide de linhas a serem exibidas no filtro
     * @return number[]
     */
    function getDisplayQtyValues()
    {
        return [10, 25, 50, 100];
    }
}

if (!function_exists('checkOrderBy')) {

    /**
     * Verifica se a coluna de ordenaÃ§Ã£o existe no array de colunas da tabela
     * @param unknown $arr
     * @param unknown $column
     * @param unknown $default
     * @return $value
     */
    function checkOrderBy($arr, $column, $default)
    {

        if (!empty($arr) && !empty($column) && in_array($column, $arr)) {
            return $column;
        }

        return $default;
    }
}

if (!function_exists('isEdit')) {

    /**
     * Verifica se o mÃ©todo Ã© de ediÃ§Ã£o
     * @return boolean
     */
    function isEdit()
    {

        $uri = request()->route()->uri();
        $ep = explode('/', $uri);
        return is_array($ep) ? end($ep) == "edit" : false;
    }
}



if (!function_exists('formatDate')) {

    /**
     * Retorna uma data (string) formatada
     * @param string $dateString
     * @param string $format
     * @return string
     */
    function formatDate($dateString, $format = "br")
    {

        //Somente se existir uma data.
        if ($dateString) {

            if ($format == "br") {
                $format = "d/m/Y H:i:s";
            } elseif ($format == "en") {
                $format = "Y-m-d H:i:s";
            }

            return (new DateTime($dateString))->format($format);
        }

        return '';
    }
}

if (!function_exists('convertDate')) {

    /**
     * Converte o formato de uma data
     * @param string $date
     * @param string $format
     * @param boolean $removeSeconds
     * @return NULL|NULL|string|string
     */
    function convertDate($date, $format = null, $removeSeconds = false)
    {

        if (!$date) {
            return null;
        }

        $ad = explode(' ', $date);

        if ($format == null) {
            $dateFormat = implode('/', array_reverse(explode('-', $ad[0])));
        } else if ($format == "db") {
            $dateFormat = implode('-', array_reverse(explode('/', $ad[0])));
        } else {
            $dateFormat = null;
        }

        if (count($ad) == 1) {
            return $dateFormat;
        } else if (count($ad) == 2) {

            if ($removeSeconds) {

                $date = $dateFormat;
                $time = explode(":", $ad[1]);
                array_pop($time);

                return $date . " " . implode(":", $time);
            } else {
                return $dateFormat . ' ' . $ad[1];
            }
        }
    }
}

if (!function_exists('isInteger')) {

    /**
     * Retorna se determinado valor Ã© um inteiro vÃ¡lido
     * @param $val
     * @return boolean
     */
    function isInteger($val)
    {
        return (is_numeric($val) && $val == intval($val) && intval($val) <= 2147483647);
    }
}

if (!function_exists('floatPtBr')) {

    /**
     * Converte um float em formato pt-BR
     * @param $number
     * @return number
     */
    function floatPtBr($number)
    {
        return floatval(str_replace(",", ".", str_replace(".", "", $number)));
    }
}


if (!function_exists('mask')) {

    /**
     * Retorna uma string formatada para uma mascara especificada
     * @param string $val
     * @param string $mask
     * @return string
     */
    function mask($val, $mask)
    {

        $maskared = '';
        $k = 0;

        for ($i = 0; $i <= strlen($mask) - 1; $i++) {

            if ($mask[$i] == '#') {
                if (isset($val[$k])) $maskared .= $val[$k++];
            } elseif (isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }

        return $maskared;
    }
}

if (!function_exists(('object2array'))) {

    /**
     * Converte um objeto para array
     * @param $object
     * @return []
     */
    function object2array($object)
    {
        return @json_decode(@json_encode($object), 1);
    }
}


if (!function_exists(('slugnize')) ) {

    /**
     * Recebe uma string e tranforma em uma slug
     */
    function slugnize($string) {
        $slug = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [:Nonspacing Mark:] Remove; [:Punctuation:] Remove; Lower();',
            $string
        );

        if (false == $slug) {
            throw new \RuntimeException('Unable to sluggize: ' . $slug);
        }

        $slug = preg_replace('/\W/', ' ', $slug); //remove remaining nonword characters
        $slug = preg_replace('/[-\s]+/', '-', $slug);
        return $slug;
    }
}
