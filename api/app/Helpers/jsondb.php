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
        $existingData = self::read($file);
        $existingData[] = $data;
        $result = file_put_contents(
            $path,
            json_encode($existingData, JSON_PRETTY_PRINT)
        );
        return $result !== false;
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