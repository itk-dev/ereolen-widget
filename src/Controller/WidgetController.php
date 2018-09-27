<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller;

use App\Entity\Widget;
use App\Service\WidgetStatisticsService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/widget")
 */
class WidgetController extends AbstractController
{
    /** @var \Psr\Cache\CacheItemPoolInterface */
    private $cacheItemPool;

    /** @var array */
    private $parameterBag;

    /** @var \App\Service\WidgetStatisticsService */
    private $statistics;

    public function __construct(CacheItemPoolInterface $cacheItemPool, ParameterBagInterface $parameterBag, WidgetStatisticsService $statistics)
    {
        $this->cacheItemPool = $cacheItemPool;
        $this->parameterBag = $parameterBag;
        $this->statistics = $statistics;
    }

    /**
     * Wrapper around (ie. proxy for) the real search on ereolen.dk.
     *
     * @Route("/search", name="widget_search")
     */
    public function search(Request $request)
    {
        $baseUrl = $this->parameterBag->get('search_ereol_url');
        $cacheTtl = (int) $this->parameterBag->get('search_cache_ttl');
        $query = $request->query->all();
        $cacheKey = md5(json_encode($query));
        $cachedItem = $this->cacheItemPool->getItem($cacheKey);

        if ($cachedItem->isHit()) {
            $data = $cachedItem->get();

            return new JsonResponse(...$data);
        }

        try {
            $client = new Client([
                    'base_uri' => $baseUrl,
                ]);
            $response = $client->get('widget/search', [
                    'query' => $request->query->all(),
                ]);
            $cachedItem->set([
                    (string) $response->getBody(),
                    $response->getStatusCode(),
                    $response->getHeaders(),
                    true,
                ])->expiresAfter($cacheTtl);
            $this->cacheItemPool->save($cachedItem);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
        }

        if (empty($response)) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse(
                (string) $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders(),
                true
            );
    }

    /**
     * @Route("/{id}", name="widget_show")
     */
    public function show(Request $request, Widget $widget)
    {
        $this->statistics->addRequest($widget, $request);

        return $this->render('widget/show.html.twig', ['widget' => $widget]);
    }

    /**
     * @Route("/{id}/redirect", name="widget_redirect")
     */
    public function doRedirect(Request $request, Widget $widget)
    {
        $url = $request->get('url');
        if (empty($url)) {
            throw new BadRequestHttpException('Invalid url: '.($url ?? '(empty)'));
        }

        $this->statistics->addRedirectRequest($widget, $url, $request);

        return $this->redirect($url);
    }

    /**
     * @Route("/{id}/statistics", name="widget_statistics")
     */
    public function statistics(Request $request, Widget $widget)
    {
        $statistics = $this->statistics->getStatistics($widget);

        return new JsonResponse($statistics);
    }
}
