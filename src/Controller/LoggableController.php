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
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\Loggable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Loggable controller.
 *
 * @Route("/admin/loggable/{entity}")
 * @ Security("has_role('ROLE_SUPER_ADMIN')")
 */
class LoggableController extends Controller
{
    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/{id}", name="admin_loggable_entity", methods={"GET"})
     *
     * @param $entity
     * @param $id
     *
     * @return array
     */
    public function indexAction($entity, $id)
    {
        $entityType = $entity;
        $className = $this->getClassName($entityType);
        $entity = $this->manager->getRepository($className)->find($id);

        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$entity instanceof Loggable) {
            throw new BadRequestHttpException('Entity '.\get_class($entity).' is not loggable');
        }
        $changes = $this->manager->getRepository(LogEntry::class)->getLogEntries($entity);

        return $this->render('loggable/index.html.twig', [
            'entityType' => $entityType,
            'entity' => $entity,
            'changes' => $changes,
        ]);
    }

    /**
     * @Route("/{id}/revert/{version}", name="admin_loggable_entity_revert", methods={"PUT"})
     *
     * @param mixed $entity
     * @param mixed $id
     * @param mixed $version
     */
    public function revert($entity, $id, $version)
    {
        $entityType = $entity;
        $className = $this->getClassName($entityType);
        $entity = $this->manager->getRepository($className)->find($id);
        $repository = $this->manager->getRepository(LogEntry::class);
        $changes = $repository->getLogEntries($entity);
        $repository->revert($entity, $version);
        $this->manager->persist($entity);
        $this->manager->flush();

        return $this->redirectToRoute('admin_loggable_entity', [
            'entity' => $entityType,
            'id' => $id,
        ]);
    }

    private function getClassName($entityType)
    {
        switch ($entityType) {
            case 'Widget':
                return Widget::class;
        }

        throw new BadRequestHttpException('Invalid entity type: '.$entityType);
    }
}
