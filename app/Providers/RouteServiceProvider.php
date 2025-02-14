<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define o namespace base dos controllers.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Boot para definir configurações de rota.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define os arquivos de rotas carregados pelo Laravel.
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
