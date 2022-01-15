<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (!function_exists('route_name')) {
    /**
     * get route name by url
     *
     * @param $url
     * @return string|null
     */
    function route_name($url)
    {
        return Route::match([
            'get', 'post', 'delete', 'patch', 'put'
        ], $url)->getName();
    }
}

if (!function_exists('uuidstr')) {
    function uuidstr($delim = '-')
    {
        return str_replace('-', $delim, Str::uuid());
    }
}

if (!function_exists('query_statement')) {
    function query_statement($query, $dump = false)
    {
        $sql_str = $query->toSql();
        $bindings = $query->getBindings();

        $wrapped_str = str_replace('?', "'?'", $sql_str);

        return Str::replaceArray('?', $bindings, $wrapped_str);
    }
}

if (!function_exists('sub_query')) {
    function sub_query($query, $alias = null, $parenthesis = true)
    {
        $query = query_statement($query);
        $query = $parenthesis
            ? '(' . $query . ')'
            : $query;
        $query = is_null($alias)
            ? $query
            : $query . '`' . $alias . '`';

        return DB::raw($query);
    }
}

if (!function_exists('is_current_route')) {
    function is_current_route($routeName, $mustMatch = true)
    {
        if ($mustMatch) {
            return Route::currentRouteName() == $routeName;
        }

        return Str::contains(Route::currentRouteName(), $routeName);
    }
}

if (!function_exists('lang')) {
    /**
     * get current language
     *
     * @param null $language
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed|null
     */
    function lang($language = null)
    {
        if (!empty($language)) {
            session()->put('lang', $language);
        }

        if (!session()->has('lang')) {
            session()->put('lang', 'id');
        }

        $language = session('lang')[0];
        $language = (strlen($language) == 1) ? session('lang') : $language;

        return $language;
    }
}

if (!function_exists('clean_text')) {
    function clean_text($text)
    {
        $text = preg_replace('/[^A-Za-z0-9\- ]/', '', $text);

        return $text;
    }
}

if (!function_exists('badge')) {
    /**
     * badge html
     *
     * @param $text
     * @param null $type
     * @return string
     */
    function badge($text, $type = null)
    {
        if (empty($type)) {
            $badges = collect(['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark']);
            $type = $badges->random();
        }

        $type = 'badge-' . $type;

        return '<span class="badge ' . $type . '">' . $text . '</span>';
    }
}

if (!function_exists('highlight')) {
    function highlight($text, $sentence, $match = true, $clean = true, $open = '<span style="background-color: yellow">', $close = '</span>')
    {
        if (empty($sentence))
            return $text;

        $sentence = $clean
            ? clean_text($sentence)
            : $sentence;

        $words = $match
            ? [$sentence]
            : explode(' ', $sentence);

        $pattern = '#(?<=^|\W)('
            . implode('|', array_map('preg_quote', $words))
            . ')(?=$|\W)#i';

        $text = preg_replace($pattern, "$open$1$close", $text);

        return $text;
    }
}

if (!function_exists('array_to_string')) {
    /**
     * convert list to readable
     *
     * @param $array
     * @param string $splitter
     * @param string $lastSplitter
     * @return string|string[]
     */
    function array_to_string($array, $splitter = ', ', $lastSplitter = ' and ', $prefix = '', $suffix = '')
    {
        $string = implode($splitter, $array);
        $string = str_replace($splitter . last($array), $lastSplitter . last($array), $string);

        return $prefix . $string . $suffix;
    }
}

if (!function_exists('disable_foreign_key_checks_mysql')) {
    function disable_foreign_key_checks_mysql()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}

if (!function_exists('enable_foreign_key_checks_mysql')) {
    function enable_foreign_key_checks_mysql()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

if (!function_exists('readable_datetime')) {
    /**
     * Melakukan formatting tanggal
     *
     * @param Carbon $carbon
     * @return string
     */
    function readable_datetime($datetime, $locale = 'id', $withTime = true, $withDayName = true)
    {
        $format = 'dddd, MMMM Do YYYY, HH:mm:ss';
        $localeFormats = [
            'jv' => 'dddd, DD MMMM YYYY, HH:mm:ss',
            'id' => 'dddd, DD MMMM YYYY, HH:mm:ss',
            'en' => 'dddd, MMMM Do YYYY, HH:mm:ss'
        ];
        $format = $localeFormats[$locale] ?? $format;
        $format = !$withTime
            ? str_replace(', HH:mm:ss', '', $format)
            : $format;
        $format = !$withDayName
            ? str_replace('dddd, ', '', $format)
            : $format;

        return Carbon::parse($datetime)
            ->locale($locale)
            ->isoFormat($format);
    }
}

if (!function_exists('number_to_roman')) {
    function number_to_roman($number)
    {
        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10,
            'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}

if (!function_exists('number_to_alpha')) {
    function number_to_alpha($num)
    {
        return chr(substr("000".($num+65),-3));
    }
}

if (!function_exists('money_to_words')) {
    function money_to_words($money, $locale = 'id_ID', $decimalWordFrom = 'titik', $decimalWordTo = 'koma')
    {
        $formatter = new NumberFormatter('id_ID', NumberFormatter::SPELLOUT);

        return str_replace($decimalWordFrom, $decimalWordTo, $formatter->format($money));
    }
}

if (!function_exists('chained_method_call')) {
    function chained_method_call($object, $methods)
    {
        $callStr = 'return $object->';
        foreach($methods as $method => $param){
            $callStr.= "$method($param)->";
        }
        $callStr = substr($callStr, 0, -2);
        $callStr.= ';';

        return eval($callStr);
    }
}

if (!function_exists('method_from_doc_code')) {
    function method_from_doc_code($line)
    {
        $exploded = explode('(', $line);
        $exploded[1] = explode(')', $exploded[1])[0].')';

        $name = explode(' ', $exploded[0]);
        $name = end($name);

        $method = $name.'('.$exploded[1];

        $params = explode(',', str_replace(')', '', $exploded[1]));
        $params = collect($params)
            ->map(function ($param) use ($line, $exploded) {
                if (str_contains($param, '$')) {
                    $param = explode('$', $param);
                    $name = explode(' ', $param[1])[0];
                    $default = explode('=', $param[1]);
                    $default = count($default) == 1
                        ? null
                        : trim($default[1]);

                    return (object)[
                        'name' => $name,
                        'default' => $default
                    ];
                }

                return [
                    'name' => '',
                    'default' => ''
                ];
            })
            ->where('name', '!=', '');

        $paramStr = $params->pluck('default')->join(', ');

        return [
            'name' => $name,
            'params' => $params->toArray(),
            'param_str' => $paramStr,
            'full' => $method,
            'raw' => trim($line)
        ];
    }
}
