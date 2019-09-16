<?php

namespace Platform\App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $basePath = base_path('se');
        $folders = scandir($basePath);
        foreach (array_slice($folders, 2) as  $folder) {
            if($folder != 'App'){
                $dir = $basePath.'/'.$folder.'/Repositories/Contracts';
                if(is_dir($dir)){
                    $files = scandir($dir);
                    foreach (array_slice($files, 2) as $file) {
                        $file = rtrim($file,'.php');
                        $clas ='Platform\\'.$folder.'\Repositories\Eloquent\Eloquent'.$file;
                       if(class_exists($clas)){
                            $this->app->bind(
                                'Platform\\'.$folder.'\Repositories\Contracts\\'.$file,
                                $clas 
                            );
                       }
                    }
                }
            }
        }
    }
}
