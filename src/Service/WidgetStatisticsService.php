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

    private $statistics;

    public function __construct(WidgetRequestItemRepository $requestItemRepository, EntityManagerInterface $entityManager)
    {
        $this->requestItemRepository = $requestItemRepository;
        $this->entityManager = $entityManager;
    }

    public function getStatistics(Widget $widget = null)
    {
        $statistics = $this->loadStatistics();

        if (null !== $widget) {
            return $statistics[$widget->getId()] ?? $this->getDefaultStatistics();
        }

        return $statistics;
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

    private function getDefaultStatistics()
    {
        return [
            WidgetRequestItem::TYPE_REDIRECT => 0,
            WidgetRequestItem::TYPE_SHOW => 0,
        ];
    }

    private function loadStatistics()
    {
        if (null === $this->statistics) {
            $query = $this->requestItemRepository->createQueryBuilder('e')
                ->innerJoin('e.widget', 'w')
                ->select('w.id AS widget_id')
                ->addSelect('e.type')
                ->addSelect('COUNT(e) AS count')
                ->groupBy('e.widget')
                ->addGroupBy('e.type')
                ->getQuery();

            $data = [];
            foreach ($query->execute() as $item) {
                $id = $item['widget_id'];
                $data[$id][$item['type']] = (int) $item['count'];
            }
            foreach ($data as &$item) {
                $item += $this->getDefaultStatistics();
                ksort($item);
            }

            $this->statistics = $data;
        }

        return $this->statistics;
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
