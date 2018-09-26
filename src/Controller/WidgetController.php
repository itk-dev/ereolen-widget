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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WidgetController extends AbstractController
{
    /**
     * @Route("/widget/{id}", name="widget_show")
     */
    public function show(Widget $widget)
    {
        /* return $this->render('widget/index.html.twig', [
            'controller_name' => 'WidgetController',
        ]); */

        /*$repository = $this->getDoctrine()->getRepository(Widget::class);

        $widget = $this->getDoctrine()
            ->getRepository(Widget::class)
            ->find($id);

        if (!$widget) {
            throw $this->createNotFoundException(
                'NO widget found for id '.$id
            );
        }*/

        // return new Response('Checkout this cool widget '.$widget->getTitle());
        return $this->render('widget/show.html.twig', ['widget' => $widget]);
        /*$entityManager = $this->getDoctrine()->getManager();

        $widget = new Widget();
        $widget->setTitle('My first widget');


        // tell Doctrine you want to (eventually) save the widget (no queries yet)
        $entityManager->persist($widget);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new widget with id '.$widget->getId());*/
    }
}
