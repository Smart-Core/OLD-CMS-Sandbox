<?php

namespace SmartCore\Module\WebForm\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\WebForm\Entity\Message;
use SmartCore\Module\WebForm\Entity\WebForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebFormController extends Controller
{
    use NodeTrait;

    /** @var int|null */
    protected $webform_id;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if (null === $this->webform_id) {
            return new Response('Module WebForms not yet configured. Node: '.$this->node->getId().'<br />');
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $webForm = $em->find('WebFormModule:WebForm', $this->webform_id);

        $form = $this->getForm($webForm);

        $feedback_data = $this->get('session')->getFlashBag()->get('feedback_data');

        if (!empty($feedback_data)) {
            $form->submit(new Request($feedback_data[0]));
            $form->isValid();
        }

        return $this->render('WebFormModule::index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postAction(Request $request)
    {
        if (null === $this->webform_id) {
            return new Response('Module WebForms not yet configured. Node: '.$this->node->getId().'<br />');
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $webForm = $em->find('WebFormModule:WebForm', $this->webform_id);

        $form = $this->getForm($webForm);

        $form->handleRequest($request);

        $session = $this->get('session')->getFlashBag();

        if ($form->isValid()) {
            $message = new Message();
            $message
                ->setData($form->getData())
                ->setUserId($this->getUser())
                ->setWebForm($webForm)
            ;
            $this->persist($message, true);

            if ($webForm->getSendNoticeEmails()) {
                $addresses = [];

                foreach (explode(',', $webForm->getSendNoticeEmails()) as $email) {
                    $addresses[] = trim($email);
                }

                $mailer = $this->get('mailer');

                $message = \Swift_Message::newInstance()
                    ->setSubject('Сообщение с веб-формы &laquo;'.$webForm->getTitle().'&raquo; с сайта '.$this->container->getParameter('base_url'))
                    ->setFrom($webForm->getFromEmail())
                    ->setTo($addresses)
                    ->setBody($this->renderView('WebFormModule:Email:notice.email.twig', ['web_form' => $webForm, 'message' => $message]))
                ;
                $mailer->send($message);
            }

            $session->add('success', $webForm->getFinalText() ? $webForm->getFinalText() : 'Сообщение отправлено.');
        } else {
            $session->add('error', 'При заполнении формы допущены ошибки.');
            $session->add('feedback_data', $request->request->all());
        }

        return $this->redirect($request->getRequestUri());
    }

    /**
     * @param WebForm $webForm
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm(WebForm $webForm)
    {
        $fb = $this->createFormBuilder();

        foreach ($webForm->getFields() as $field) {
            $fb->add($field->getName(), $field->getType(), [
                'required' => $field->getIsRequired(),
            ]);
        }

        if ($webForm->isIsUseCaptcha()) {
            $fb->add('captcha', 'genemu_captcha', ['mapped' => false]);
        }

        $fb->add('send', 'submit', [
            'attr' => ['class' => 'btn btn-success'],
            'label' => $webForm->getSendButtonTitle(),
        ]);

        return $fb->getForm();
    }
}
