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
use Symfony\Component\Routing\Generator\UrlGenerator;

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
        $json = '{"meta":{"page":1,"count":4},"links":{"self":"http:\/\/127.0.0.1:8000\/widget\/search?query=jussi%20adler"},"data":[{"id":"870970-basis:51348788","title":"Liv for liv","cover":"https:\/\/stg.ereolen.dk\/sites\/default\/files\/ting\/covers\/ODcwOTcwLWJhc2lzOjUxMzQ4Nzg4.jpg","abstract":false,"ac_source":"eReolen","classification":"","contributors":["Jussi Adler-Olsen"],"creators":[],"date":"2011","description":"Downloades i EPUB-format","extent":"233 sider","isPartOf":[],"isbn":["9788740002140"],"language":"Dansk","localId":"isbn_9788740002140","ownerId":"870970","relations":[[],[]],"serieDescription":"","serieNumber":null,"serieTitle":[],"subjects":["krimi"],"type":"Ebog","url":false},{"id":"870970-basis:52397979","title":"Over gr\u00e6nsen","cover":"https:\/\/stg.ereolen.dk\/sites\/default\/files\/ting\/covers\/ODcwOTcwLWJhc2lzOjUyMzk3OTc5.jpg","abstract":false,"ac_source":"eReolen","classification":"","contributors":["Jussi Adler-Olsen"],"creators":[],"date":"2016","description":"Downloades i EPUB-format","extent":false,"isPartOf":[],"isbn":["9788740033960"],"language":"Dansk","localId":"isbn_9788740033960","ownerId":"870970","relations":[],"serieDescription":"","serieNumber":null,"serieTitle":[],"subjects":["krimi"],"type":"Ebog","url":false},{"id":"870970-basis:52445086","title":"Over gr\u00e6nsen","cover":"https:\/\/stg.ereolen.dk\/sites\/default\/files\/ting\/covers\/ODcwOTcwLWJhc2lzOjUyNDQ1MDg2.jpg","abstract":false,"ac_source":"Netlydbog","classification":"","contributors":["Dan Schlosser","Jussi Adler-Olsen"],"creators":[],"date":"2016","description":"Downloades i MP3-format","extent":"5 t., 39 min.","isPartOf":[],"isbn":["9788740033946"],"language":"Dansk","localId":"isbn_9788740033946","ownerId":"870970","relations":[[],[]],"serieDescription":"","serieNumber":null,"serieTitle":[],"subjects":["krimi"],"type":"Lydbog (net)","url":false},{"id":"870970-basis:51269705","title":"Liv for liv","cover":"https:\/\/stg.ereolen.dk\/sites\/default\/files\/ting\/covers\/ODcwOTcwLWJhc2lzOjUxMjY5NzA1.jpg","abstract":false,"ac_source":"Netlydbog","classification":"","contributors":["Lars Junker Thiesgaard","Jussi Adler-Olsen"],"creators":[],"date":"2013","description":"Downloades i MP3-format","extent":"7 t., 10 min.","isPartOf":[],"isbn":["9788740009811"],"language":"Dansk","localId":"isbn_9788740009811","ownerId":"870970","relations":[[],[]],"serieDescription":"","serieNumber":null,"serieTitle":[],"subjects":["krimi"],"type":"Lydbog (net)","url":false}]}';
        $data = json_decode($json, true);

        foreach ($data['data'] as &$item) {
            $item['url'] = 'https://ereolen.dk/ting/object/' . $item['id'];
            $item['coverUrl'] = str_replace('/sites/default/files/ting/covers/', '/sites/default/files/styles/ereol_cover_base/public/ting/covers/', $item['cover']);
        }

        return new JsonResponse($data, 200, [], false);

        $baseUrl = $this->parameterBag->get('search_ereol_url');
        $cacheTtl = (int) $this->parameterBag->get('search_cache_ttl');
        $query = $request->query->all();
        $cacheKey = 'app_widget_search_'.md5(json_encode($query));
        $cachedItem = $this->cacheItemPool->getItem($cacheKey);
        $data = null;

        if ($cachedItem->isHit()) {
            $data = $cachedItem->get();
        } else {
            try {
                $client = new Client([
                    'base_uri' => $baseUrl,
                ]);
                $response = $client->get('widget/search', [
                    'query' => $request->query->all(),
                ]);

                // Change url in "links" to point to proxy.
                $result = json_decode((string) $response->getBody(), true);
                if (isset($result['links'])) {
                    $result['links'] = array_map(function ($url) {
                        $info = parse_url($url);
                        parse_str($info['query'] ?? '', $query);

                        return $this->generateUrl('widget_search', $query, UrlGenerator::ABSOLUTE_URL);
                    }, $result['links']);
                }

                $data = [
                    json_encode($result),
                    $response->getStatusCode(),
                    [],
                    true,
                ];
                $cachedItem->set($data)->expiresAfter($cacheTtl);
                $this->cacheItemPool->save($cachedItem);
            } catch (ClientException $exception) {
                $response = $exception->getResponse();

                return new JsonResponse(
                    (string) $response->getBody(),
                    $response->getStatusCode(),
                    [],
                    true
                );
            }
        }

        if (empty($data)) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse(...$data);
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
