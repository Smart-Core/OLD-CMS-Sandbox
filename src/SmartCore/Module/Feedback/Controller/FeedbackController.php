<?php

namespace SmartCore\Module\Feedback\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Feedback\Form\Type\FeedbackFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $form->submit($request);

        $item = $form->getData();

        $session = $this->get('session')->getFlashBag();

        if ($request->isMethod('POST') and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush($item);

            $session->add('success', 'Сообщение отправлено.');
        } else {
            $session->add('error', 'При заполнении формы допущены ошибки.');
            $session->add('feedback_data', $request->request->all());
        }

        return $this->redirect($request->getRequestUri());
    }
}
