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
use SlimApi\Models\StylesRepository;

/**
 * Class StylesController
 *
 * @package SlimApi\Controller
 */
class StylesController
{
    /**
     * @var StylesRepository
     */
    protected $stylesRepository;

    public function __construct(StylesRepository $repository)
    {
        $this->stylesRepository = $repository;
    }

    /**
     * @param Request $request
     * @return Response $response
     */
    public function getStyles(Request $request, Response $response)
    {
        $params = $request->getParams();
        if (isset($params['search'])) {

        } elseif (isset($params['tag'])) {

        } else {
            $styles = $this->stylesRepository->getStyles();
        }

        $styles = json_encode($styles);

        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($styles);
        return $response->withBody($stream);
    }
}
