<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 13:01
 */

namespace SlimApi\DependencyInjection;

use MongoDB\Collection;
use MongoDB\Driver\Manager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;
use SlimApi\Controller\StylesController;
use SlimApi\Models\StylesRepository;

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
            $config = [
                'settings' => [
                    'displayErrorDetails' => true
                ],
            ];
            return new App($config);
        };

        $pimple['styles_repository'] = function ($c) {
            return new StylesRepository(
                new Collection(
                    new Manager(
                        $c['config']->get('db.dsn')
                    ),
                    $c['config']->get('db.dbName'),
                    $c['config']->get('db.stylesRepository'),
                    [
                        'typeMap' => [
                            'root' => 'array',
                            'document' => 'array',
                            'array' => 'array'
                        ]
                    ]
                )
            );
        };

        $pimple['styles_controller'] = function ($c) {
            return new StylesController($c['styles_repository']);
        };
    }
}
