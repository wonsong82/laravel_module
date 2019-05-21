<?php
namespace Module\Application;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Module\Application\Commands\DB\Backup;
use Module\Application\Commands\DB\Restore;
use Module\Application\Commands\Module\ModuleGenerate;
use Module\Application\Commands\Module\ModuleList;
use Module\Application\Commands\Module\ModuleMake;
use Module\Application\Commands\Module\ModuleTest;
use Module\Application\Commands\Module\ModuleUpdate;
use Module\Application\Commands\Module\ModuleUpdateOrder;
use Module\Application\Commands\RefreshConstants;
use Module\Application\Commands\VendorPatch;
use Module\Application\Controllers\Logic\DBController;
use Module\Application\Controllers\Logic\ModuleController;
use Module\Application\Controllers\Logic\PermissionController;
use Module\Application\Database\Seeds\App\ConstantsSeeder;
use Module\Application\Database\Seeds\App\LocalesSeeder;
use Module\Application\Database\Seeds\App\PermissionSeeder;
use Module\Application\Database\Seeds\App\UsersSeeder;
use Module\Application\EventListeners\LocaleEventListener;
use Module\Application\EventListeners\PermissionEventListener;
use Module\Application\EventListeners\RoleEventListener;
use Module\Application\EventListeners\SerializedModelEventListener;
use Module\Application\EventListeners\UserEventListener;
use Module\Application\Middleware\RemoveNullFromRequest;
use Module\Application\Middleware\SetLocale;
use Module\Application\Validation\UniqueMultiple;

class ApplicationServiceProvider extends ServiceProvider
{
    protected $packagePath;
    protected $packageName;


    // Cli Commands
    protected $commands = [
        VendorPatch::class,
        ModuleList::class,
        ModuleGenerate::class,
        ModuleMake::class,
        ModuleUpdate::class,
        ModuleUpdateOrder::class,
        RefreshConstants::class,
        Backup::class,
        Restore::class,
        ModuleTest::class,
    ];


    // Custom validators
    protected $validators = [
        UniqueMultiple::class
    ];


    // Events & Listeners
    protected $listeners = [
        'LocaleCreated' => LocaleEventListener::class,
        'LocaleUpdated' => LocaleEventListener::class,
        'LocaleDeleted' => LocaleEventListener::class,
        'UserCreated' => UserEventListener::class,
        'UserUpdated' => UserEventListener::class,
        'UserDeleted' => UserEventListener::class,
        'RoleCreated' => RoleEventListener::class,
        'RoleUpdated' => RoleEventListener::class,
        'RoleDeleted' => RoleEventListener::class,
        'PermissionCreated' => PermissionEventListener::class,
        'PermissionUpdated' => PermissionEventListener::class,
        'PermissionDeleted' => PermissionEventListener::class,
        'SerializedModelCreating' => SerializedModelEventListener::class,
        'SerializedModelCreated'  => SerializedModelEventListener::class,
    ];


    // Database Seeders
    protected $seeders = [
        ConstantsSeeder::class,
        LocalesSeeder::class,
        UsersSeeder::class,
        PermissionSeeder::class,
    ];


    // Permissions
    protected $permissions = [
        'filemanager.read',
        'filemanager.write',
        'permission.read',
        'permission.write',
        'locale.read',
        'locale.write',
        'role.read',
        'role.write',
        'user.read',
        'user.write',
        'activity_log.read'
    ];


    // Global middlewares
    protected $middlewares = [
        // classes

    ];


    // Route middlewares
    protected $routeMiddlewares = [
        //'key' => 'middleware class',
        'admin' => [
            SetLocale::class,
            //RemoveNullFromRequest::class,
        ],
        'locale' => SetLocale::class,
    ];


    // Cron jobs
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('command or class')->daily();
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


    public function register()
    {
        $this->packagePath = realpath(__DIR__ . '/..');
        $this->packageName = trim(strtolower(preg_replace('/([A-Z])/', '_$1', basename($this->packagePath))), '_');

        app()->singleton(DBController::class);
        app()->singleton(PermissionController::class);
        app()->singleton(ModuleController::class);

        $this->enableModule();
        $this->setupHelpers();
        $this->commands($this->commands);
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
            $middlewares = is_array($middleware)? $middleware : [$middleware];
            foreach($middlewares as $class){
                $this->app->router->pushMiddlewareToGroup($key, $class);
            }
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
     * __('package_name:file_name.field')
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
