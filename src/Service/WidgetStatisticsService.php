<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Service;

use App\Entity\Widget;
use App\Entity\WidgetRequestItem;
use App\Repository\WidgetRequestItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class WidgetStatisticsService
{
    /** @var \App\Repository\WidgetRequestItemRepository */
    private $requestItemRepository;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(WidgetRequestItemRepository $requestItemRepository, EntityManagerInterface $entityManager)
    {
        $this->requestItemRepository = $requestItemRepository;
        $this->entityManager = $entityManager;
    }

    public function getStatistics(Widget $widget)
    {
        $views = $this->requestItemRepository->findBy(['widget' => $widget, 'type' => WidgetRequestItem::TYPE_SHOW]);
        $clicks = $this->requestItemRepository->findBy(['widget' => $widget, 'type' => WidgetRequestItem::TYPE_REDIRECT]);

        return [
            'views' => \count($views),
            'clicks' => \count($clicks),
        ];
    }

    public function addRequest(Widget $widget, Request $request)
    {
        $this->log($widget, $request, WidgetRequestItem::TYPE_SHOW);
    }

    public function addRedirectRequest(Widget $widget, string $url, Request $request)
    {
        $this->log($widget, $request, WidgetRequestItem::TYPE_REDIRECT, [
            'url' => $url,
        ]);
    }

    private function log(Widget $widget, Request $request, string $type, array $data = [])
    {
        $item = new WidgetRequestItem();
        $item->setWidget($widget)
            ->setType($type)
            ->setData($data + [
                    'referer' => $request->headers->get('referer'),
                ]);
        $this->entityManager->persist($item);
        $this->entityManager->flush($item);
    }
}
