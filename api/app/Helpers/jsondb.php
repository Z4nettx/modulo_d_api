<?php

namespace App\Helpers;

class JsonDB {
    public static function read(string $file): array
    {
        $path = storage_path("app/{$file}.json");
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }
    public static function write(string $file, array $data): bool
    {
        $path = storage_path("app/{$file}.json");
        $result = file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT)
        );
        return $result !== false;
    } 
    public static function store(string $file, array $novaTarefa): bool
    {
        $dadosAtuais = self::read($file);
        $dadosAtuais[] = $novaTarefa;

        return self::write($file, $dadosAtuais);
    }
    public static function update(string $file, string $campo, $valor, array $novosDados): bool
    {
        $data = self::read($file);
        $foiAlterado = false;

        foreach ($data as &$item) {
            if (isset($item[$campo]) && $item[$campo] == $valor) {
                $item = array_merge($item, $novosDados);
                $foiAlterado = true;
                break;
            }
        }
        return $foiAlterado ? self::write($file, $data) : false;
    }
    public static function delete(string $file, string $key, $value): bool
    {
        $data = self::read($file);
        $filteredData = array_filter($data, function($item) use ($key, $value) {
            return isset($item[$key]) && $item[$key] != $value;
        });
        return self::write($file, array_values($filteredData));
    }
}