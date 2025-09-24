<?php

namespace Tjall\Pagebuilder;

use Tjall\Pagebuilder\Support\Block;
use Tjall\Pagebuilder\Support\Shortcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Throwable;

class Registry
{
    public static function blocks(): array
    {
        return self::cache('pagebuilder.blocks', fn () =>
            self::discoverAppClasses(Block::class)
        );
    }

    public static function shortcodes(): array
    {
        return self::cache('pagebuilder.shortcodes', fn () =>
            self::discoverAppClasses(Shortcode::class)
        );
    }

    public static function models(): array
    {
        return self::cache('app.models', fn () =>
            self::discoverAppClasses(Model::class)
        );
    }

    protected static function cache(string $key, callable $callback): array
    {
        if (app()->isLocal()) {
            // in local: no caching, just run the callback
            return $callback();
        }

        // in production: cache forever
        return cache()->rememberForever($key, $callback);
    }

    /**
     * Discover all classes that optionally extend $parentClass.
     */
    protected static function discoverAppClasses(?string $parentClass = null): array
    {
        $classes = self::getAppClasses();

        if (blank($parentClass)) {
            return $classes->all();
        }

        // Filter by parent class / interface
        return $classes->filter(function ($class) use ($parentClass) {
            try {
                if (! is_subclass_of($class, $parentClass)) {
                    return false;
                }

                $ref = new ReflectionClass($class);
                return ! $ref->isAbstract();
            } catch (Throwable) {
                return false;
            }
        })->values()->all();
    }

    /**
     * Get all app classes (using Composer classmap in production, filesystem in local).
     */
    protected static function getAppClasses()
    {
        $namespace = 'App\\';

        if (app()->isLocal()) {
            // scan filesystem, map to FQCNs
            return collect(File::allFiles(app_path()))
                ->map(function ($file) use ($namespace) {
                    $relative = Str::after($file->getPathname(), app_path() . DIRECTORY_SEPARATOR);
                    $class = $namespace . str_replace(
                        [DIRECTORY_SEPARATOR, '.php'],
                        ['\\', ''],
                        $relative
                    );
                    return $class;
                })
                ->filter(fn(string $class) => class_exists($class))
                ->values();
        }

        // production: use composer optimized classmap
        $classLoader = require base_path('vendor/autoload.php');

        return collect($classLoader->getClassMap())
            ->keys()
            ->filter(fn(string $class) => Str::startsWith($class, $namespace))
            ->values();
    }
}
