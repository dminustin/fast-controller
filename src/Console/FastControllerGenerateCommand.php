<?php

declare(strict_types=1);

namespace FastController\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class FastControllerGenerateCommand extends Command
{
    protected const ROUTER_SEPARATOR = '//Do not delete this line';

    protected $pathArray = [];
    protected $path = '';
    protected $uri = '';
    protected $fileName = '';
    protected $className = '';
    protected $nameSpace = '';
    protected $segments = [];


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fast-controller:create {path}';

    public function handle()
    {
        $this->preparePath();
        $this->displaySetupInfo();
        $data = $this->setupGenerator();
        $this->saveCode($data);
    }

    protected function displaySetupInfo()
    {
        $this->info(str_repeat('=', 64));
        $this->info('URI: ' . $this->uri);
        $this->info('Filename: ' . $this->fileName);
        $this->info('Class: ' . $this->className);
        $this->info('Namespace: ' . $this->nameSpace);
        $this->info(str_repeat('=', 64));

    }

    protected function preparePath()
    {
        $path = $this->argument('path');
        $this->uri = config('fast-controller.uri_prefix') . $path;
        $this->path = ucwords($path, '/');
        $this->segments = $this->pathArray = explode('/', $this->path);
        array_pop($this->segments);

        $this->generateClassName();
        $this->generateNameSpace();
        $this->generateFileName();

        if(file_exists($this->fileName) && !config('fast-controller.override_existing')) {
            $this->error('File ' . $this->fileName . ' already exists');
            exit(0);
        }

    }

    protected function setupGenerator(): array
    {
        $data['method'] = $this->choice(
            'Request method',
            config('fast-controller.default.available_methods'),
            config('fast-controller.default.method')
        );
        //
        $auth = $this->choice(
            'Need auth',
            ['y','n'],
            config('fast-controller.default.need_auth') ? 'y' : 'n'
        );
        if($auth) {
            $data['middleware'] = config('fast-controller.default.auth_middlewares');
        } else {
            $data['middleware'] = [];
        }
        $data['params'] = $this->askForParams();
        return $data;
    }

    protected function askForParams(): array
    {
        $data = [];
        for (;;) {
            $name = '';
            while(empty($name)) {
                $name = $this->ask('Param name, empty to skip');
                if (empty($name)) {
                    $skip = $this->choice('Skip adding new params?', ['y', 'n'], 'y');
                    if ($skip == 'y') {
                        return $data;
                    }
                }
            }
            $type = $this->choice(
                'Param type',
                config('fast-controller.default.available_params'),
                config('fast-controller.default.param')
            );
            $data[] = ['name'=>$name, 'type'=>$type];
        }
    }

    protected function generateNameSpace()
    {
        $this->nameSpace = 'App\\' . config('fast-controller.namespace') . join('\\', $this->segments);
    }

    protected function generateClassName()
    {
        $this->className = join('', $this->pathArray) . 'Controller';
    }

    protected function generateFileName()
    {
        $basePath = App::basePath() . '/app/' . config('fast-controller.controller_path');
        $baseName = $this->className . '.php';
        $this->fileName = $basePath . join('/', $this->segments) . '/' . $baseName;
    }

    protected function saveCode($data): bool
    {

        $middleware = '';
        if(!empty($data['middleware'])) {
            $middleware = "'" . join("', '", $data['middleware']) . "'";
        }

        $rules = [];
        foreach($data['params'] as $param) {
            $rules[] = sprintf("'%s' => '%s|required'", $param['name'], $param['type']);
        }
        $rules = join(',' . PHP_EOL , $rules);

        $content = '<?'.'php' . PHP_EOL  . PHP_EOL . view(config('fast-controller.views_path') . '/controller-template', [
            'nameSpace'=>$this->nameSpace,
            'className'=>$this->className,
            'middleware' => $middleware,
            'rules' => $rules
            ]) . '';

        $dir = dirname($this->fileName);
        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($this->fileName, $content);

        $route = view(config('fast-controller.views_path') . '/router-template', [
            'pathArray'=>$this->pathArray,
            'uri'=>$this->uri,
            'className'=>$this->className,
            'method'=>strtolower($data['method']),
            ]);
        $routerName = base_path('routes'  . '/' . config('fast-controller.router_name'));

        list($header, $footer) = explode(self::ROUTER_SEPARATOR, file_get_contents($routerName));
        $header .= 'use ' . $this->nameSpace . '\\' . $this->className . ';';
        $footer .= $route;
        file_put_contents($routerName, $header . PHP_EOL . self::ROUTER_SEPARATOR . $footer);
        dd('xx');
    }
}
