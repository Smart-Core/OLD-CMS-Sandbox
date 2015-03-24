<?php

namespace SmartCore\Module\Feedback\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Feedback\Form\Type\FeedbackFormType;
use Symfony\Component\HttpFoundation\Request;

class FeedbackController extends Controller
{
    use NodeTrait;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $form = $this->createForm(new FeedbackFormType());

        $feedback_data = $this->get('session')->getFlashBag()->get('feedback_data');

        if (!empty($feedback_data)) {
            $form->submit(new Request($feedback_data[0]));
            $form->isValid();
        }

        return $this->render('FeedbackModule::feedback.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(new FeedbackFormType());
        $form->handleRequest($request);

        $session = $this->get('session')->getFlashBag();

        if ($form->isValid()) {
            $this->persist($form->getData(), true);
            $session->add('success', 'Сообщение отправлено.');
        } else {
            $session->add('error', 'При заполнении формы допущены ошибки.');
            $session->add('feedback_data', $request->request->all());
        }

        return $this->redirect($request->getRequestUri());
    }
}
