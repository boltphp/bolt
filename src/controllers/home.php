<?php

/**
 * You should change the namespace 'app' to your apps global names
 */
namespace app\controllers;
use \b; // not required, but a helpful shortcut


/**
 * Any class that extends 'bolt\http\controller\route' is checked for
 * routes that it can process
 */
class home extends \bolt\http\controller\route {

    /**
     * the layout paramater should defined the
     * layout template used to wrap any content
     * produced by the action function
     */
    public $layout = "_layouts/layout.hbr";

    /**
     * because this class extends the 'bolt\http\controller\route' class
     * you can defined routes that this controller can opporate on. you can
     * also defined routes by defining a static 'getRoutes' method and returning
     * an array
     */
    public static $routes = [

        // this is the simplest way to define a route
        // path is always required
        ['path' => '/'],

        // here is a more complex route
        ['path' => '/hello/{message}',
            'require' => ['message' => '[a-zA-Z0-9]{4,}'],     // require a message param in the path
            'action' => 'hello',                               // call the {method}hello function
            'methods' => 'get',                                // only respond to the 'GET' method
            'formats' => '?html,json'                          // this route will return html or json. the ? in front of 'html'
                                                               // means the format is option and 'html' is the default
        ]

    ];

    public function init() {

        // always need the year
        $this->year = date("Y");

        // bolt version
        $this->bVersion = b::VERSION;

    }

    /**
     * this function is called for all GET requests to this
     * controller (defined in the first route in self::$routes), that don't have an action in the route
     */
    public function get() {

        // any paramater defined in an action function
        // will be available in the template
        $this->datetime = date('c');

        $vars = [
                'title' => 'Hello World',
                'marketing' => "We've setup a simple boilerplate app for you. Take a look in <code>src/controllers/home.php</code> for more information.",
            ];

        // return a view bound to this controller.
        // this is the same as calling:
        //  return new bolt\http\views\view(['file' => 'home.hbr', 'vars' => $vars, 'parent' => $this])
        return $this->format('html', $this->view('home.hbr', $vars));

    }

    /**
     * this function is called for all GET requests to this
     * controller (that match the second route in self::$routes)
     */
    public function getHello($message, \bolt\http\request $req) {

        // $message is provided by the route above
        // it is equivalent to calling:
        //  $this->request->attributes->get('message');
        //  $req->attributes->get('message')
        //
        // NOTE: by default, any route paramaters are not filtered
        // and should be considered insecure. you should use a filter
        // to verify imput. so this is a better method
        //  $this->request->attributes->getAlpha('message')

        return $this->format([
            'html' => "hello $message",
            'json' => [
                'hello' => $message,
                'user-agent' => $req->headers->get('user-agent')
            ]
        ]);
    }

    /**
     * these methods are not required.
     * by default, any method requested and not defined by
     * a route is ignored
     */
    public function post() {
        return $this->exception('MethodNotAllowedException');
    }
    public function put() {
        return $this->exception('MethodNotAllowedException');
    }
    public function delete() {
        return $this->exception('MethodNotAllowedException');
    }

}