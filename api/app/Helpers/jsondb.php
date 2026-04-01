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
}