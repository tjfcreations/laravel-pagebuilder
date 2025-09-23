<?php
namespace Tjall\Pagebuilder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tjall\Pagebuilder\Models\Page;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tjall\Pagebuilder\Http\Controllers\DynamicPageController;

class DynamicPageServiceProvider extends ServiceProvider {
    protected const ROUTES_CACHE_KEY = 'pagebuilder_routes';

    public function boot(): void {
        try {
            $this->cacheRoutes();
        } catch(\Exception $e) {
            // Database may nog be available, ignore error
        }
    }

    public function cacheRoutes(): void {
        $this->invalidateRouteCache();

        $routes = Cache::rememberForever(static::ROUTES_CACHE_KEY, function () {
            return Page::all()->map->only(['id', 'path'])->toArray();
        });

        foreach ($routes as $route) {
            Route::get($route['path'], [DynamicPageController::class, 'show'])
                ->defaults('pageId', $route['id']);
        }
    }

    public function invalidateRouteCache() {
        Cache::forget(static::ROUTES_CACHE_KEY);
    }
}