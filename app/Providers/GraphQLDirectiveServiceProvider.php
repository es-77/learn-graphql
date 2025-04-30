<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;

class GraphQLDirectiveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $directivePath = app_path('GraphQL/Directives');
        
        if (File::exists($directivePath)) {
            $directiveFiles = File::files($directivePath);
            
            foreach ($directiveFiles as $file) {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $class = "App\\GraphQL\\Directives\\{$className}";
                
                if (class_exists($class) && is_subclass_of($class, BaseDirective::class)) {
                    $this->app->singleton($class);
                }
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 