<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 28/08/18
 * Time: 22:50
 */

namespace SlimApi\Tests;

use Pimple\Container;
use SlimApi\Config\Config;
use SlimApi\DependencyInjection\ApiProvider;

/**
 * Trait ContainerAwareTestTrait
 * @package SlimApi\Tests
 */
trait ContainerTestTrait
{
    /** @var  Container */
    protected $container;

    /**
     * @before
     */
    public function setUpContainer()
    {
        $this->container = new Container((['config' => new Config('test', __DIR__ . '/../config')]));
        $this->container->register(new ApiProvider());

        return $this->container;
    }
}