<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 15:03
 */

namespace SlimApi\Config;

use SlimApi\Config\Exception\ConfigException;
use Symfony\Component\Yaml\Parser;

/**
 * Class Config
 *
 * @package SlimApi\Config
 */
class Config
{

    /** @var array key => value store of configurations */
    protected $config = [];

    /**
     * @param string $env
     * @param string $configPath
     * @throws ConfigException  if it is not able to load a file
     */
    public function __construct($env, $configPath)
    {
        $file = $configPath . '/' . $env . '.yml';
        if (file_exists($file)) {
            $parser = new Parser();
            $this->config = $parser->parse(file_get_contents($file));
        } else {
            throw new ConfigException('Not able to load the config file: ' . $file);
        }
    }

    /**
     * Getter
     *
     * @param string $key
     * @return mixed
     * @throws ConfigException in case the key doesn't exist
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        } else {
            throw new ConfigException('No configuration value found for ' . $key);
        }
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Get all config keys and values
     *
     * @return array
     */
    public function getAll()
    {
        return $this->config;
    }
}
