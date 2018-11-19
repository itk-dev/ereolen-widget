<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EreolenSearch
{
    /** @var \Psr\Cache\CacheItemPoolInterface */
    private $cacheItemPool;

    /** @var array */
    private $parameterBag;

    public function __construct(
        CacheItemPoolInterface $cacheItemPool,
        ParameterBagInterface $parameterBag
    ) {
        $this->cacheItemPool = $cacheItemPool;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param array $query
     *
     * @throws \App\Service\SearchException
     *
     * @return null|array
     */
    public function search(array $query, array $context = null)
    {
        $url = $context['ereol_widget_search_url'] ?? $this->parameterBag->get('ereol_widget_search_url');
        $cacheTtl = (int) $this->parameterBag->get('search_cache_ttl');
        $cacheKey = 'app_widget_search_'.md5(json_encode($query));
        $cachedItem = $this->cacheItemPool->getItem($cacheKey);
        $data = null;

        if ($cachedItem->isHit()) {
            $data = $cachedItem->get();
        } else {
            try {
                $client = new Client();
                $response = $client->get($url, [
                    'query' => $query,
                ]);

                $data = json_decode((string) $response->getBody(), true);
                if (!empty($data)) {
                    $cachedItem->set($data)->expiresAfter($cacheTtl);
                    $this->cacheItemPool->save($cachedItem);
                }
            } catch (ClientException $exception) {
                throw new SearchException($exception->getMessage(), $exception->getCode(), $exception);
            }
        }

        return $data;
    }
}
