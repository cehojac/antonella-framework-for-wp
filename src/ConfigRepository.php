<?php

namespace CH;

class ConfigRepository
{
    /** @var array<string, mixed> */
    protected array $items = [];

    /**
     * Get a configuration value using dot notation (file.key.subkey).
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        [$file, $path] = $this->splitKey($key);
        $data = $this->load($file);
        if ($path === null) {
            return $data ?? $default;
        }
        $value = $this->dataGet($data, $path);
        return $value !== null ? $value : $default;
    }

    /**
     * Determine if a configuration value exists.
     */
    public function has(string $key): bool
    {
        return $this->get($key, '__MISS__') !== '__MISS__';
    }

    /**
     * Set a configuration value at runtime (in-memory only).
     */
    public function set(string $key, $value): void
    {
        [$file, $path] = $this->splitKey($key);
        $data = $this->load($file);
        if ($path === null) {
            $this->items[$file] = $value;
            return;
        }
        $this->dataSet($this->items[$file], $path, $value);
    }

    /**
     * Return the whole configuration array for a given file.
     *
     * @return array<string, mixed>
     */
    public function all(string $file): array
    {
        $data = $this->load($file);
        return is_array($data) ? $data : [];
    }

    /**
     * Load a config file from /config only once.
     *
     * @param string $file
     * @return mixed
     */
    protected function load(string $file)
    {
        if (array_key_exists($file, $this->items)) {
            return $this->items[$file];
        }
        $base = dirname(__DIR__);
        $path = $base . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $file . '.php';
        if (is_readable($path)) {
            $data = require $path;
            $this->items[$file] = $data;
            return $data;
        }
        // Cache miss with null to avoid repeated disk checks
        $this->items[$file] = null;
        return null;
    }

    /** @return array{0:string,1:?string} */
    protected function splitKey(string $key): array
    {
        $parts = explode('.', $key, 2);
        return [$parts[0], $parts[1] ?? null];
    }

    protected function dataGet($target, string $path)
    {
        if (!is_array($target)) {
            return null;
        }
        $segments = explode('.', $path);
        foreach ($segments as $segment) {
            if (is_array($target) && array_key_exists($segment, $target)) {
                $target = $target[$segment];
            } else {
                return null;
            }
        }
        return $target;
    }

    protected function dataSet(&$target, string $path, $value): void
    {
        if (!is_array($target)) {
            $target = [];
        }
        $segments = explode('.', $path);
        $current =& $target;
        foreach ($segments as $segment) {
            if (!isset($current[$segment]) || !is_array($current[$segment])) {
                $current[$segment] = [];
            }
            $current =& $current[$segment];
        }
        $current = $value;
    }
}
