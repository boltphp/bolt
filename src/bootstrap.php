<?php

/**
 * include our vendor autoload
 * provided by composer
 */
require __DIR__."/../vendor/autoload.php";

/**
 * inialize our bolt instance
 *
 * @var $env string environment name
 * @var $root root source directory to load resources from
 *              all paths are considered relative to this
 *
 * @return \bolt\application
 */
$bolt = bolt::init([
        'env' => 'dev',
        'root' => __DIR__,
        'bootstrap' => __DIR__.'/bootstrap'
    ]);

/**
 * Plugin our http (http) class
 *
 * you can access this plugin by using
 * $bolt['http']
 *
 */
$bolt->plug('http', 'bolt\http');


/**
 * Plugin the components we need for
 * our http instance
 */
$bolt['http']->plug([

        // handle asset managment
        ['assets', 'bolt\http\assets', [
            'root' => 'assets',  // relative to $root,
            'path' => '/a/{path}',  // to serve assets locally
            'filters' => [
                    'css' => [
                        new Assetic\Filter\LessphpFilter()
                    ]
                ],
            'ready' => function($assets){
                $assets->set('app', $assets->collection([
                        new Assetic\Asset\HttpAsset('http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css'),
                        new Assetic\Asset\HttpAsset('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css'),
                        $assets->glob('css/*css')
                    ]));
            }
        ]],

        // handle all routing request
        ['router', 'bolt\http\router'],

        // handle view management
        ['views', 'bolt\http\views', [
            'dirs' => [
                'views' // relative to $root above
            ],
            'engines' => [
                ['hbr', 'bolt\render\handlebars'] // any file with .hbr will be render with the handlebars engine
            ]
        ]]
    ]);

/**
 * if we in dev, we want to autoload
 * all of our controllers with route
 * definitions
 */
$bolt->env('dev', function(){
    $this['http']['router']->loadFromControllers([
        b::fs('rdir', $this->path('/controllers'), '^.+\.php$') // glob everything in $root/controllers
    ]);
});


/**
 * we want to plugin our cli functions
 * give it a try: `./bolt app:test 'hello world' --yell`
 */
$bolt->plug('cli', 'bolt\cli')['cli']->plug('test', 'app\cli\test');


/**
 * return our runnable instance
 */
return $bolt;
