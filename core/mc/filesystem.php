<?php

namespace mc;

class filesystem
{
    public const US = "/";
    public const WS = "\\";

    public static function normalize(string $path, string $separator = self::US): string
    {
        if ($separator === self::US) {
            return self::to_unix($path);
        } elseif ($separator === self::WS) {
            return self::to_windows($path);
        }
        return $path;
    }

    public static function to_unix(string $path): string
    {
        return str_replace(self::WS, self::US, $path);
    }

    public static function to_windows(string $path): string
    {
        return str_replace(self::US, self::WS, $path);
    }

    public static function root(string $path, string $separator = self::US): string
    {
        $path = self::normalize($path, $separator);
        $chunks = explode($separator, $path);
        $last = array_pop($chunks);
        return implode($separator, $chunks);
    }

    public static function children(string $path, string $separator = self::US): string
    {
        $path = self::normalize($path, $separator);
        $chunks = explode($separator, $path);
        return end($chunks);
    }

    public static function fileName(string $path, string $separator = self::US): string {
        return self::children($path, $separator);
    }

    public static function extension(string $fileName): string {
        // TODO: can be file without extention or linux config file!
        $chunks = explode(".", $fileName);
        return end($chunks);
    }

    public static function implode(string $left, string $right, string $separator = self::US): string
    {
        $left = self::normalize($left, $separator);
        $right = self::normalize($right, $separator);
        $chunks = explode($separator, $left);
        foreach (explode($separator, $right) as $chunk) {
            $chunks[] = $chunk;
        }
        return implode($separator, $chunks);
    }

    public static function copy(string $from, string $to, string $separator = self::US)
    {
        $from = self::normalize($from, $separator);
        $to = self::normalize($to, $separator);

        foreach ($iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($from, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        ) as $item) {
            if ($item->isDir()) {
                if (!file_exists($to . $separator . $iterator->getSubPathname())) {
                    mkdir($to . $separator . $iterator->getSubPathname());
                }
            } else {
                copy($item, $to . $separator . $iterator->getSubPathname());
            }
        }
    }

    public static function unlink(string $path)
    {
        if (\is_dir($path)) {
            $files = \array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                self::unlink($path . DIRECTORY_SEPARATOR . $file);
            }
            \rmdir($path);
        } else {
            \unlink($path);
        }
    }
}
