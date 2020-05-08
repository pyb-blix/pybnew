<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as MaintenanceMode;

class CheckForMaintenanceMode {

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next)
    {
        //admin url check 
        $admin_url = @$request->segment(1);              
        if ($this->app->isDownForMaintenance() &&  (@$admin_url != ADMIN_URL) ) {
            $maintenanceMode = new MaintenanceMode($this->app);
            return $maintenanceMode->handle($request, $next);
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }

}
