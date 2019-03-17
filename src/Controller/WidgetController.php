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
use App\Repository\WidgetRepository;
use App\Service\EreolenSearch;
use App\Service\SearchException;
use App\Service\WidgetContextService;
use App\Service\WidgetStatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/widget")
 */
class WidgetController extends AbstractController
{
    /** @var \App\Repository\WidgetRepository */
    private $repository;

    /** @var \App\Service\WidgetContextService */
    private $contextService;

    /** @var \App\Service\EreolenSearch */
    private $search;

    /** @var \App\Service\WidgetStatisticsService */
    private $statistics;

    /** @var \Symfony\Component\Serializer\SerializerInterface */
    private $serializer;

    public function __construct(WidgetRepository $repository, WidgetContextService $contextService, EreolenSearch $search, WidgetStatisticsService $statistics, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->contextService = $contextService;
        $this->search = $search;
        $this->statistics = $statistics;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/export.{_format}", name="widget_export",
     *     defaults={"_format": "html"},
     *     requirements={
     *         "_format": "html|csv"
     *     }
     * )
     *
     * @param mixed $_format
     */
    public function export($_format)
    {
        $widgets = $this->repository->findBy([], ['title' => 'ASC']);
        $data = array_map(function (Widget $widget) {
            $createdBy = $widget->getCreatedBy() ? $widget->getCreatedBy()->getUsername() : null;

            return [
                'id' => $widget->getId(),
                'title' => $widget->getTitle(),
                'statistics' => $this->statistics->getStatistics($widget),
                'preview_url' => $this->generateUrl('widget_embed', ['id' => $widget->getId(), 'preview' => true], UrlGeneratorInterface::ABSOLUTE_URL),
                'created_by' => $createdBy,
                'created_at' => $widget->getCreatedAt(),
                'updated_at' => $widget->getUpdatedAt(),
            ];
        }, $widgets);

        if ('csv' === $_format) {
            $serialized = $this->serializer->serialize($data, 'csv');

            $response = new Response($serialized);
            $contentType = 'text/csv';
            $contentType = 'text/plain';
            $filename = 'widgets.csv';
            $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
            $response->headers->set('content-type', $contentType);
            $response->headers->set('content-disposition', $disposition);

            return $response;
        }

        return $this->render('widget/export.html.twig', ['data' => $data]);
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
        if (empty($request->query->get('preview'))) {
            $this->statistics->addRequest($widget, $request);
        }

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

        // Add additional widget information to url (for web statistics).
        $query = ['widget' => $widget->getId()];
        foreach ($widget->getContent() as $item) {
            if ($url === $item['url']) {
                if (isset($item['isbn']) && \is_array($item['isbn'])) {
                    $query['isbn'] = array_values($item['isbn'])[0];
                }

                break;
            }
        }

        $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?').http_build_query($query);

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
