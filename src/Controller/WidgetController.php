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
use App\Service\EreolenSearch;
use App\Service\SearchException;
use App\Service\WidgetContextService;
use App\Service\WidgetStatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    /** @var \App\Service\WidgetContextService */
    private $contextService;

    /** @var \App\Service\EreolenSearch */
    private $search;

    /** @var \App\Service\WidgetStatisticsService */
    private $statistics;

    public function __construct(WidgetContextService $contextService, EreolenSearch $search, WidgetStatisticsService $statistics)
    {
        $this->contextService = $contextService;
        $this->search = $search;
        $this->statistics = $statistics;
    }

    /**
     * Wrapper around (ie. proxy for) the real search on ereolen.dk.
     *
     * @Route("/search", name="widget_search")
     */
    public function search(Request $request)
    {
        $query = $request->query->all();
        unset($query['context']);

        try {
            $context = $this->contextService->getContext();
            $result = $this->search->search($query, $context);

            if (isset($result['links'])) {
                $result['links'] = array_map(function ($url) {
                    $info = parse_url($url);
                    parse_str($info['query'] ?? '', $query);

                    return $this->generateUrl(
                        'widget_search',
                        $query,
                        UrlGenerator::ABSOLUTE_URL
                    );
                }, $result['links']);
            }

            return new JsonResponse($result);
        } catch (SearchException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="widget_show")
     */
    public function show(Request $request, Widget $widget)
    {
        $this->statistics->addRequest($widget, $request);
        $context = $this->getWidgetContext($widget);

        return $this->render('widget/show.html.twig', ['widget' => $widget, 'context' => $context]);
    }

    /**
     * @Route("/{id}/edit", name="widget_edit")
     */
    public function edit(Request $request, Widget $widget)
    {
        $context = $this->getWidgetContext($widget);

        return $this->render('default/index.html.twig', ['widget' => $widget, 'context' => $context]);
    }

    /**
     * @Route("/{id}/embed", name="widget_embed")
     */
    public function embed(Request $request, Widget $widget)
    {
        $this->statistics->addRequest($widget, $request);

        $configuration = $widget->getConfiguration();
        $context = $this->getWidgetContext($widget);

        if (isset($configuration['search']['type'], $configuration['search']['url']) && 'url' === $configuration['search']['type']) {
            try {
                $result = $this->search->search(['url' => $configuration['search']['url']], $context);
                if (isset($result['data'])) {
                    $widget->setContent($result['data']);
                }
            } catch (SearchException $exception) {
            }
        }

        // Rewrite content urls to local redirect urls.
        $content = $widget->getContent();
        foreach ($content as &$item) {
            $item['original_url'] = $item['url'];
            $item['url'] = $this->generateUrl(
                'widget_redirect',
                ['id' => $widget->getId(), 'url' => $item['url']],
                UrlGenerator::ABSOLUTE_URL
            );
        }
        $widget->setContent($content);

        return $this->render('widget/embed.html.twig', ['widget' => $widget, 'context' => $context]);
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

    private function getWidgetContext(Widget $widget)
    {
        return $this->contextService->getContextByName($widget->getContext());
    }
}
