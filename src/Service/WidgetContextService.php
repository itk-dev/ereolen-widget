<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Service;

use App\Repository\WidgetContextRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class WidgetContextService
{
    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /** @var \App\Repository\WidgetContextRepository */
    private $repository;

    public function __construct(WidgetContextRepository $repository, RequestStack $requestStack)
    {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
    }

    public function getContext()
    {
        $request = $this->requestStack->getCurrentRequest();
        $name = $request->get('context');
        $context = $this->repository->findOneByName($name);
        if (null !== $context) {
            return $context;
        }

        $host = $request->getHost();
        $context = $this->repository->findOneByHost($host);
        if (null !== $context) {
            return $context;
        }

        return null;
    }

    public function getContextByName($name)
    {
        return $this->repository->findOneByName($name);
    }

    public function getContexts()
    {
        return $this->repository->findAll();
    }
}
