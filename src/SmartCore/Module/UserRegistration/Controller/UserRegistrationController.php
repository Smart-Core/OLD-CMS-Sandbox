<?php

namespace SmartCore\Module\UserRegistration\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use SmartCore\Bundle\CMSBundle\Module\Controller;
use SmartCore\Bundle\CMSBundle\Response;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationController extends Controller
{
    protected function init()
    {
        $this->View->setOptions([
            'engine' => 'echo',
        ]);
    }

    public function indexAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return new RedirectResponse($request->getBaseUrl() . '/user/');
        } else {
            $this->View->forward('FOSUserBundle:Registration:register');
        }
        
        return new Response($this->View);
    }

    public function confirmedAction()
    {
        $this->View->forward('FOSUserBundle:Registration:confirmed');

        return new Response($this->View);
    }

    public function checkEmailAction(Request $request)
    {
        if ($this->container->get('session')->has('fos_user_send_confirmation_email/email')) {
            $this->View->forward('FOSUserBundle:Registration:checkEmail');
        } else {
            return new RedirectResponse($request->getBaseUrl() . '/user/');
        }

        return new Response($this->View);
    }

    public function confirmAction($token)
    {
        $this->View->forward('FOSUserBundle:Registration:confirm', ['token' => $token]);
        
        return new Response($this->View);
    }
}
