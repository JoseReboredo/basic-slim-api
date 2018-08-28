<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 11:24
 */

namespace SlimApi;

use Pimple\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\App;
use SlimApi\Config\Config;
use SlimApi\DependencyInjection\ApiProvider;
use SlimApi\Controller\StylesController;

/**
 * Class Application
 *
 * @package SlimApi
 */
class Application
{
    /**
     * Path to the folder where we have the config files per env
     */
    const CONFIG_PATH = __DIR__ . '/../config/';

    /**
     * @var App
     */
    protected $slimApp;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Application constructor.
     *
     * @param string $param
     */
    public function __construct($env)
    {
        $this->container = new Container((['config' => new Config($env, self::CONFIG_PATH)]));
        $this->container->register(new ApiProvider());

        $this->slimApp = $this->container['slim_app'];
    }

    /**
     * Run a basic Slim API
     *
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function runSlimApi()
    {
        $this->initRoutes();

        $this->slimApp->run();
    }

    /**
     * Init all the available routes for the Api
     */
    protected function initRoutes()
    {
        $stylesController = $this->container['styles_controller'];

        // Routes
        $this->slimApp->get('/styles', function (Request $request, Response $response, array $args) use ($stylesController) {
            return $stylesController->getStyles($request, $response, $args);
        });

        $response = new Response();
        $response->withStatus(404, 'Resource not found');
    }
}
