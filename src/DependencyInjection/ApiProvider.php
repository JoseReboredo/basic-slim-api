<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 13:01
 */

namespace SlimApi\DependencyInjection;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;

/**
 * Class ApiProvider
 *
 * @package SlimApi\DependencyInjection
 */
class ApiProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['slim_app'] = function ($c) {
            return new App();
        };
    }
}
