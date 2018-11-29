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

    private $contexts = [
        'ereolen' => [
            'name' => 'ereolen',
            'host' => 'widget.ereolen.dk',
            'label' => 'eReolen',
            'url' => 'https://www.ereolen.dk',
            'searchLink' => '<a href="https://ereolen.dk/search/ting" target="_blank">eReolen</a>',
            'logo' => '/images/eReolen_Logo.svg',
            'searchUrl' => 'https://www.ereolen.dk/search/ting',
            'ereol_widget_search_url' => 'https://itk:itk@stg.ereolen.dk/widget/search',
        ],
        'ereolengo' => [
            'name' => 'ereolengo',
            'host' => 'widget.ereolengo.dk',
            'label' => 'eReolen GO',
            'url' => 'https://www.ereolengo.dk',
            'searchLink' => '<a href="https://ereolengo.dk/search/ting" target="_blank">eReolen GO</a>',
            'logo' => '/images/eReolenGo_Logo.svg',
            'searchUrl' => 'https://www.ereolengo.dk/search/ting',
            'ereol_widget_search_url' => 'https://itk:itk@stg.ereolengo.dk/widget/search',
        ],
    ];

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
