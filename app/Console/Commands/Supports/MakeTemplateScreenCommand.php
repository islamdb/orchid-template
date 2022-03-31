<?php

namespace App\Console\Commands\Supports;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeTemplateScreenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orchid:screen-new
                            {class : name of class screen}
                            {model : name of class model in the screen}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New Screen Using template';

    /** @var Filesystem $files */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = $this->argument('model');
        $class = $this->argument('class');
        $class = Str::replace("//", "/", $class);
        $class = Str::replace("/", "\\", $class);
        $class = Str::replace("\\\\", "\\", $class);
        $path = app_path("Orchid\Screens\\" . $class . ".php");
        $path = Str::replace("\\\\", "\\", $path);

        if (!$this->filesystem->exists($path)) {
            $folder = Str::of($path)->explode("\\");
            $folder->pop(1);
            $folder = $folder->join("\\");

            if (!$this->filesystem->exists($folder)) {
                $this->filesystem->makeDirectory($folder);
            }

            $stub = $this->filesystem->get(base_path('stubs\platform.screen.stub'));
            $stub = $this->populateStub($stub, $class, $model);

            $this->filesystem->put($path, $stub);

            $stubRoute = $this->filesystem->get(base_path('stubs\platform.routes.screen.stub'));
            $stubRoute = $this->populateRouteStub($stubRoute, $class);

            $routePlatform = $this->filesystem->append(base_path('routes\platform.php'), $stubRoute);

            $stubMenu = $this->filesystem->get(base_path('stubs\platform.menu.screen.stub'));
            $stubMenu = $this->populateMenuStub($stubMenu, $class);
            $this->info($stubMenu);
            $platformProvider = $this->filesystem
                ->replaceInFile("->title('Main'),", $stubMenu, app_path("Orchid\PlatformProvider.php"));

            $this->info($platformProvider);
        }
    }

    public function populateStub(string $stub, $class, $model)
    {
        $class = Str::of($class)->explode("\\");
        $newClass = $class->pop(1);
        $model = Str::of($model)->title();
        $stub = str_replace('DummyNamespace', "App\Orchid\Screens" . $class->join("\\"), $stub);
        $stub = str_replace('DummyClass', $newClass, $stub);
        $stub = str_replace('DummyModel', $model, $stub);

        return $stub;
    }

    public function populateRouteStub(string $stub, $class)
    {
        $class = Str::of($class)->explode("\\");
        $newClass = $class->pop(1);
        $name = Str::of($newClass)->remove('Screen')->lower();
        $stub = str_replace('DummyNamespace', "\App\Orchid\Screens" . $class->join("\\"), $stub);
        $stub = str_replace('DummyClass', $newClass . "::class", $stub);
        $stub = str_replace('DummyUrlName', $name, $stub);
        $stub = str_replace('DummyName', $name, $stub);
        $stub = str_replace('DummyBreadcrumbsName', $name->title(), $stub);

        return $stub;
    }

    public function populateMenuStub(string $stub, $class)
    {
        $class = Str::of($class)->explode("\\");
        $newClass = $class->pop(1);
        $name = Str::of($newClass)->remove('Screen')->lower();

        $stub = str_replace('DummyMenuName', $name->title(), $stub);
        $stub = str_replace('DummyName', $name, $stub);
        $stub = Str::replaceLast("\n", "", $stub);
        $stub = "->title('Main')," . PHP_EOL . $stub;

        return $stub;
    }
}
