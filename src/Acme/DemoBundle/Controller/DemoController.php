<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DemoController extends Controller
{
    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/hello/{name}", name="_demo_hello")
     * @Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailer = $this->get('mailer');

            // .. setup a message and send it
            // http://symfony.com/doc/current/cookbook/email.html

            $request->getSession()->getFlashBag()->set('notice', 'Message sent!');

            return new RedirectResponse($this->generateUrl('_demo'));
        }

        return array('form' => $form->createView());
    }

    /**
     * @Template()
     */
    public function formAction()
    {
        $builderWithoutType = $this->createFormBuilder(null, array('data_class' => '\\Acme\\DemoBundle\\Entity\\Member'));
        $builderWithoutType->add('password');

        $builderWithType = $this->createFormBuilder(null, array('data_class' => '\\Acme\\DemoBundle\\Entity\\Member'));
        $builderWithType->add('password', 'password');

        $builderWithTextType = $this->createFormBuilder(null, array('data_class' => '\\Acme\\DemoBundle\\Entity\\Member'));
        $builderWithTextType->add('password', 'text');

        return array(
            'formWithoutType' => $builderWithoutType->getForm()->createView(),
            'formWithType' => $builderWithType->getForm()->createView(),
            'formWithTextType' => $builderWithTextType->getForm()->createView(),
        );
    }
}
