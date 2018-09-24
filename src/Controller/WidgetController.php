<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Widget;

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
        
        /*return new Response('Checkout this cool widget '.$widget->getTitle());*/
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
