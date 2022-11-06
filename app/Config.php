<?php

namespace App;

use App\Exceptions\FileExistsException;

final class Config
{
    private const CONFIG_FILE_PATH = __DIR__ . '/../config.json';

    private static $instance;

    private array $config;

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        if (!file_exists(self::CONFIG_FILE_PATH)) {
            throw new FileExistsException('Can\'t find config file' . self::CONFIG_FILE_PATH);
        }
        $this->config = json_decode(file_get_contents(self::CONFIG_FILE_PATH), true);
    }

    public function getDataBase(): array
    {
        return $this->config['database'];
    }
}
