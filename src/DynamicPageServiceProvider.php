<?php
namespace Tjall\Pagebuilder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Tjall\Pagebuilder\Models\Page;
use Illuminate\Support\Facades\Route;
use Tjall\Pagebuilder\Http\Controllers\DynamicPageController;

class DynamicPageServiceProvider extends ServiceProvider {
    protected const ROUTES_CACHE_KEY = 'pagebuilder_routes';

    public function boot(): void {
        $this->cacheRoutes();
    }

    public function cacheRoutes(): void {
        $this->invalidateRouteCache();

        try {
            $routes = Cache::rememberForever(static::ROUTES_CACHE_KEY, function () {
                return Page::all()->map->only(['id', 'path'])->toArray();
            });
        } catch(\Exception $e) {
            // Database is not ready, ignore error
        }

        foreach ($routes as $route) {
            Route::get($route['path'], [DynamicPageController::class, 'show'])
                ->defaults('pageId', $route['id']);
        }
    }

    public function invalidateRouteCache() {
        Cache::forget(static::ROUTES_CACHE_KEY);
    }
}