<?php

if (!function_exists('load_static_strings')) {
    function load_static_strings($to_translate) {
        $lang_dir = get_template_directory() . '/resources/lang';
        $locale = 'it';
        $json_file = "{$lang_dir}/{$locale}.json";
        if (file_exists($json_file)) {
            $translations = json_decode((string) file_get_contents($json_file), true);

            if (!is_array($translations)) {
                return $to_translate;
            }

            foreach ($translations as $key => $value) {
                if ($key === $to_translate){
                    return $value;
                }
            }
        }
        return $to_translate;
    }
}
