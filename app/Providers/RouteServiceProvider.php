<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        /*HostExperiencePHPCommentStart*/
        $this->mapHostExperiencesRoutes();
        /*HostExperiencePHPCommentEnd*/
        $this->mapAdminRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
    
    /**
     * Define the "Admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        $admin_prefix = "admin";

        if (\Schema::hasTable('site_settings')) {
            $admin_prefix = \DB::table('site_settings')->where('name', 'admin_prefix')->first()->value;
        }

        Route::middleware('web')
             ->namespace($this->namespace.'\Admin')
             ->prefix($admin_prefix)
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "Host Experience Web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapHostExperiencesRoutes()
    {
        Route::namespace($this->namespace)
             ->group(base_path('routes/host_experiences.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->namespace($this->namespace.'\Api')
             ->group(base_path('routes/api.php'));
    }
}