<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 11:24
 */

namespace SlimApi;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

/**
 * Class Application
 *
 * @package SlimApi
 */
class Application
{
    /**
     * @var App
     */
    protected $slimApp;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->app = new App();
    }

    /**
     * Run a basic Slim API
     *
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function runSlimApi()
    {
        $this->app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
            $name = $args['name'];
            $response->getBody()->write("Hello, $name");

            return $response;
        });
        $this->app->run();
    }
}
