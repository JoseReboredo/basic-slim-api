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

/**
 * Class ApiProvider
 *
 * @package SlimApi\DependencyInjection
 */
class ApiProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        // register some services and parameters
        // on $pimple
    }
}