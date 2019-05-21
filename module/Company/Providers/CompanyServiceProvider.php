<?php
namespace Module\Company;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Module\Application\Controllers\Logic\DBController;
use Module\Application\Controllers\Logic\ModuleController;
use Module\Application\Controllers\Logic\PermissionController;
use Module\Company\Commands\GenerateOffdays;
use Module\Company\Database\Seeds\App\CompanySeeder;
use Module\Company\EventListeners\CompanyEventListener;
use Module\Company\EventListeners\CompanyUserEventListener;
use Module\Company\EventListeners\CurrencyEventListener;
use Module\Company\EventListeners\MarginRateEventListener;
use Module\Company\EventListeners\OffDayEventListener;
use Module\Company\EventListeners\PaytermEventListener;
use Module\Company\EventListeners\RoleEventListener;
use Module\Company\EventListeners\UnitEventListener;
use Module\Company\EventListeners\UOMEventListener;

class CompanyServiceProvider extends ServiceProvider
{
    protected $packagePath;
    protected $packageName;


    // Cli Commands
    protected $commands = [
        GenerateOffdays::class,
    ];

    // Custom validators
    protected $validators = [
        // Validator::class,
    ];

    // Events & Listeners
    protected $listeners = [
        'CompanyCreated' => CompanyEventListener::class,
        'CompanyDeleted' => CompanyEventListener::class,
        'CompanyUpdated' => CompanyEventListener::class,
        'CompanyUserCreated' => CompanyUserEventListener::class,
        'CompanyUserDeleted' => CompanyUserEventListener::class,
        'CompanyUserUpdated' => CompanyUserEventListener::class,
        'OffDayCreated' => OffDayEventListener::class,
        'OffDayDeleted' => OffDayEventListener::class,
        'CurrencyCreated' => CurrencyEventListener::class,
        'CurrencyDeleted' => CurrencyEventListener::class,
        'CurrencyUpdated' => CurrencyEventListener::class,
        'PaytermCreated' => PaytermEventListener::class,
        'PaytermDeleted' => PaytermEventListener::class,
        'PaytermUpdated' => PaytermEventListener::class,
        'UOMCreated' => UOMEventListener::class,
        'UOMDeleted' => UOMEventListener::class,
        'UOMUpdated' => UOMEventListener::class,
        'UnitCreated' => UnitEventListener::class,
        'UnitDeleted' => UnitEventListener::class,
        'UnitUpdated' => UnitEventListener::class,
        'RoleCreated' => RoleEventListener::class,
        'RoleDeleted' => RoleEventListener::class,
        'RoleUpdated' => RoleEventListener::class,
        'MarginRateUpdated' => MarginRateEventListener::class,

    ];

    // Database Seeders
    protected $seeders = [
        CompanySeeder::class,
    ];

    // Permissions
    protected $permissions = [
        'company.read',
        'company.write',
        'company_user.read',
        'company_user.write',
        'company_role.read',
        'company_role.write',
        'payterm.read',
        'payterm.write',
        'currency.read',
        'currency.write',
        'unit.read',
        'unit.write',
    ];


    // Global middlewares
    protected $middlewares = [
        // classes
    ];

    // Route middlewares
    protected $routeMiddlewares = [
        //'key' => 'middleware class',
    ];

    // Cron jobs
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('module:company:offday:generate')->monthly();
    }




    // NO NEED TO TOUCH FROM HERE

    public function register()
    {
        $this->packagePath = realpath(__DIR__ . '/..');
        $this->packageName = $this->packageName = trim(strtolower(preg_replace('/([A-Z])/', '_$1', basename($this->packagePath))), '_');

        $this->enableModule();
        $this->setupHelpers();
        $this->commands($this->commands);
    }


    public function boot()
    {
        $this->setupMiddlewares();
        $this->setupRoutes();
        $this->setupMigrations();
        $this->setupConfigs();
        $this->setupViews();
        $this->setupTranslations();
        $this->setupValidators();
        $this->setupEventListeners();
        $this->setupSchedules();
        $this->setupSeeders();
        $this->setupPermissions();
    }


    public function enableModule()
    {
        $module = [
            'name' => basename($this->packagePath),
            'path' => $this->packagePath
        ];

        app(ModuleController::class)->modules[$module['name']] = $module;
    }


    public function setupMiddlewares()
    {
        $kernel = $this->app->make(Kernel::class);
        foreach($this->middlewares as $middleware){
            $kernel->pushMiddleware($middleware);
        }

        foreach($this->routeMiddlewares as $key => $middleware){
            $this->app->router->pushMiddlewareToGroup($key, $middleware);
        }
    }


    public function setupRoutes()
    {
        foreach(glob($this->packagePath . '/Routes/*.php') as $path){
            $this->loadRoutesFrom($path);
        }
    }


    public function setupMigrations()
    {
        $this->loadMigrationsFrom($this->packagePath . '/Database/migrations');
    }


    /***
     * You can load configs
     * config(package_name.config_name.config_field)
     */
    public function setupConfigs()
    {
        foreach(glob($this->packagePath . '/Configs/*.php') as $path){
            $name = str_replace('.php', '', basename($path));

            $this->mergeConfigFrom($path, $this->packageName . '.' . $name);
        }
    }


    /***
     * You can load views
     * view('package_name::view_name')
     */
    public function setupViews()
    {
        $this->loadViewsFrom($this->packagePath . '/Views', $this->packageName);
    }


    /***
     * You can load translations
     * __('package_name::type.field')
     */
    public function setupTranslations()
    {
        $this->loadTranslationsFrom($this->packagePath . '/Translations', $this->packageName);
    }


    public function setupValidators()
    {
        foreach($this->validators as $class){
            $class::register();
        }
    }


    public function setupEventListeners()
    {
        foreach($this->listeners as $event => $listeners){
            $listeners = is_array($listeners)? $listeners : [$listeners];
            foreach ($listeners as $listener) {
                Event::listen(__NAMESPACE__ . '\\Events\\' . $event , $listener);
            }
        }
    }


    public function setupSchedules()
    {
        $this->app->booted(function(){
            $schedule = $this->app->make(Schedule::class);
            $this->schedule($schedule);
        });
    }


    public function setupSeeders()
    {
        $DBController = app(DBController::class);
        foreach($this->seeders as $seeder){
            $DBController->addSeeder($seeder);
        }
    }


    public function setupPermissions()
    {
        $controller = app(PermissionController::class);
        $controller->permissions = array_merge($controller->permissions, $this->permissions);
    }


    public function setupHelpers()
    {
        foreach(glob($this->packagePath . '/Helpers/*.php') as $path){
            require_once $path;
        }
    }

}
