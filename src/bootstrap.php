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
 * Plugin our browser (http) class
 *
 * you can access this plugin by using
 * $bolt['browser']
 *
 */
$bolt->plug('browser', 'bolt\browser');


/**
 * Plugin the components we need for
 * our browser instance
 */
$bolt['browser']->plug([

        // handle asset managment
        ['assets', 'bolt\browser\assets', [
            'dirs' => ['assets'],  // relative to $root,
            'path' => '/a/{path}',  // to serve assets locally
            'filters' => [
                ['less', '\Assetic\Filter\LessphpFilter'], // any .less file should be run through a filter

                // when we're compileing
                ['js', '\Assetic\Filter\JSMinFilter', ['when' => 'compile']],
                ['less,css', 'Assetic\Filter\CssMinFilter', ['when' => 'compile']]
            ]
        ]],

        // handle all routing request
        ['router', 'bolt\browser\router'],

        // handle view management
        ['views', 'bolt\browser\views', [
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
    $this['browser']['router']->loadFromControllers([
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
