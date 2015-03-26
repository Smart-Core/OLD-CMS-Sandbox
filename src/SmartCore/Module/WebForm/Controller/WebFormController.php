<?php

namespace SmartCore\Module\WebForm\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\WebForm\Entity\Message;
use SmartCore\Module\WebForm\Entity\WebForm;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'node_id' => $this->node->getId(),
            'web_form' => $webForm,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function ajaxAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $webForm = $em->find('WebFormModule:WebForm', $this->webform_id);

        $form = $this->getForm($webForm);

        // @todo продумать момент с _node_id
        $data = $request->request->all();
        $node_id = null;
        foreach ($data as $key => $value) {
            if ($key == '_node_id') {
                $node_id = $data['_node_id'];
                unset($data['_node_id']);
                break;
            }

            if (is_array($value) and array_key_exists('_node_id', $value)) {
                $node_id = $data[$key]['_node_id'];
                unset($data[$key]['_node_id']);
                break;
            }
        }

        foreach ($data as $key => $value) {
            $request->request->set($key, $value);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = new Message();
            $message
                ->setData($form->getData())
                ->setUserId($this->getUser())
                ->setWebForm($webForm)
            ;
            $this->persist($message, true);

            $this->sendNoticeEmails($webForm, $message);

            return new JsonResponse([
                'status'  => 'success',
                'message' => $webForm->getFinalText() ? $webForm->getFinalText() : 'Сообщение отправлено.',
                'data'    => [],
            ], 200);
        }

        $errors = [];
        foreach ($form->getErrors() as $err) {
            $errors[] = $err->getMessage();
        }

        return new JsonResponse([
            'status'  => 'error',
            'message' => 'При заполнении формы допущены ошибки.',
            'data'    => [
                'request_data' => $request->request->all(),
                'form_errors'  => $errors,
                'form_errors_as_string'  => $form->getErrorsAsString(),
            ],
        ], 400);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function ajaxGetFormAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $webForm = $em->find('WebFormModule:WebForm', $this->webform_id);

        $form = $this->getForm($webForm);

        // @todo продумать момент с _node_id
        $data = $request->request->all();
        $node_id = null;
        foreach ($data as $key => $value) {
            if ($key == '_node_id') {
                $node_id = $data['_node_id'];
                unset($data['_node_id']);
                break;
            }

            if (is_array($value) and array_key_exists('_node_id', $value)) {
                $node_id = $data[$key]['_node_id'];
                unset($data[$key]['_node_id']);
                break;
            }
        }

        foreach ($data as $key => $value) {
            $request->request->set($key, $value);
        }

        $form->handleRequest($request);

        return $this->render('WebFormModule::index.html.twig', [
            'form' => $form->createView(),
            'node_id' => $this->node->getId(),
            'web_form' => $webForm,
        ]);
    }
    
    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postAction(Request $request)
    {
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

            $this->sendNoticeEmails($webForm, $message);

            $session->add('success', $webForm->getFinalText() ? $webForm->getFinalText() : 'Сообщение отправлено.');
        } else {
            $session->add('error', 'При заполнении формы допущены ошибки.');
            $session->add('feedback_data', $request->request->all());
        }

        return $this->redirect($request->getRequestUri());
    }

    /**
     * @param WebForm $webForm
     * @param Message $message
     */
    protected function sendNoticeEmails(WebForm $webForm, Message $message)
    {
        if ($webForm->getSendNoticeEmails()) {
            $addresses = [];

            foreach (explode(',', $webForm->getSendNoticeEmails()) as $email) {
                $addresses[] = trim($email);
            }

            $mailer = $this->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('Сообщение с веб-формы «'.$webForm->getTitle().'» ('.$this->container->getParameter('base_url').')')
                ->setFrom($webForm->getFromEmail())
                ->setTo($addresses)
                ->setBody($this->renderView('WebFormModule:Email:notice.email.twig', ['web_form' => $webForm, 'message' => $message]))
            ;
            $mailer->send($message);
        }
    }
    
    /**
     * @param WebForm $webForm
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm(WebForm $webForm)
    {
        $fb = $this->get('form.factory')->createNamedBuilder('web_form_'.$webForm->getName());
        $fb
            //->setAttribute('id', 'web_form_'.$webForm->getName())
            ->setErrorBubbling(false)
        ;
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
