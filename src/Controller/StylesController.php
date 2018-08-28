<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 11:34
 */

namespace SlimApi\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use SlimApi\Models\RepositoryException\RepositoryException;
use SlimApi\Models\StylesInterface;

/**
 * Class StylesController
 *
 * @package SlimApi\Controller
 */
class StylesController
{
    /**
     * @var StylesInterface
     */
    protected $stylesRepository;

    public function __construct(StylesInterface $repository)
    {
        $this->stylesRepository = $repository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getStyles(Request $request, Response $response, array $args)
    {
        $params = $request->getParams();

        try {
            if (isset($params['search'])) {
                $styles = $this->stylesRepository->getStylesBySearch($params['search']);
            } elseif (isset($params['tag'])) {
                $styles = $this->stylesRepository->getStylesByTag($params['tag']);
            } else {
                $styles = $this->stylesRepository->getAllStyles();
            }
        } catch (RepositoryException $e) {
            return $response->withStatus(500, 'Issue with the DB: ' . $e->getMessage());
        } catch (\Exception $e) {
            return $response->withStatus(500, 'Unknown issue: ' . $e->getMessage());
        }

        $styles = json_encode($styles);
        if (json_last_error() != 0) {
            return $response->withStatus(500, 'Issue with the json encoding: ' . json_last_error_msg());
        }

        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($styles);
        return $response->withBody($stream);
    }
}
