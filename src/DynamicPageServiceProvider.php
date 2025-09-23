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
            $this->registerDynamicRoutes();
        } catch(\Exception $e) {
            // Database may not be available, ignore.
        }
    }

    public function registerDynamicRoutes(): void {
        if(app()->routesAreCached()) return;

        foreach (Page::all() as $page) {
            Route::get($page->path, [DynamicPageController::class, 'show'])
                ->defaults('pageId', $page->id);
        }
    }

    public function invalidateRouteCache() {
        Cache::forget(static::ROUTES_CACHE_KEY);
    }
}