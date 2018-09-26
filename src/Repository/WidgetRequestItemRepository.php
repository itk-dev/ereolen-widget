<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Repository;

use App\Entity\Widget;
use App\Entity\WidgetRequestItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method null|WidgetRequestItem find($id, $lockMode = null, $lockVersion = null)
 * @method null|WidgetRequestItem findOneBy(array $criteria, array $orderBy = null)
 * @method WidgetRequestItem[]    findAll()
 * @method WidgetRequestItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WidgetRequestItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WidgetRequestItem::class);
    }

    public function logWidgetRequest(Widget $widget, Request $request)
    {
        $this->log($widget, $request, WidgetRequestItem::TYPE_SHOW);
    }

    public function logWidgetRedirectRequest(Widget $widget, string $url, Request $request)
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

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush($item);
    }
}
