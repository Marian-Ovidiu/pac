<?php
/**
 * Minimal root .env loader used before WordPress and its plugins are available.
 *
 * Real process environment variables always take precedence over .env values.
 */

if (!function_exists('pac_read_env_file')) {
    /**
     * @return array<string, string>
     */
    function pac_read_env_file(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        if ($lines === false) {
            throw new RuntimeException('PAC cannot read the root .env file.');
        }

        $values = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $line = preg_replace('/^export\s+/', '', $line, 1) ?? $line;
            $separator = strpos($line, '=');

            if ($separator === false) {
                continue;
            }

            $key = trim(substr($line, 0, $separator));

            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
                throw new RuntimeException(sprintf(
                    'PAC found an invalid .env key on line %d.',
                    $index + 1
                ));
            }

            $rawValue = trim(substr($line, $separator + 1));

            if ($rawValue === '') {
                $values[$key] = '';
                continue;
            }

            if ($rawValue[0] === '"') {
                if (!preg_match('/^"((?:\\\\.|[^"\\\\])*)"\s*(?:#.*)?$/', $rawValue, $matches)) {
                    throw new RuntimeException(sprintf(
                        'PAC found an invalid double-quoted .env value on line %d.',
                        $index + 1
                    ));
                }

                $values[$key] = preg_replace_callback(
                    '/\\\\([nrt"\\\\$])/',
                    static fn (array $match): string => match ($match[1]) {
                        'n' => "\n",
                        'r' => "\r",
                        't' => "\t",
                        '"' => '"',
                        '$' => '$',
                        '\\' => '\\',
                    },
                    $matches[1]
                ) ?? $matches[1];

                continue;
            }

            if ($rawValue[0] === "'") {
                if (!preg_match("/^'([^']*)'\\s*(?:#.*)?$/", $rawValue, $matches)) {
                    throw new RuntimeException(sprintf(
                        'PAC found an invalid single-quoted .env value on line %d.',
                        $index + 1
                    ));
                }

                $values[$key] = $matches[1];
                continue;
            }

            $values[$key] = rtrim((string) preg_replace('/\s+#.*$/', '', $rawValue));
        }

        return $values;
    }
}

if (!function_exists('get_data_env')) {
    function get_data_env(string $key, string $default = ''): string
    {
        $runtimeValue = getenv($key);

        if (is_string($runtimeValue)) {
            return $runtimeValue;
        }

        foreach ([$_ENV, $_SERVER] as $source) {
            if (isset($source[$key]) && is_scalar($source[$key])) {
                return (string) $source[$key];
            }
        }

        static $fileValues = null;

        if ($fileValues === null) {
            $fileValues = pac_read_env_file(__DIR__ . '/.env');
        }

        return array_key_exists($key, $fileValues) ? $fileValues[$key] : $default;
    }
}
