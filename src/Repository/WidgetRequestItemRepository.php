<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Repository;

use App\Entity\WidgetRequestItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
}
