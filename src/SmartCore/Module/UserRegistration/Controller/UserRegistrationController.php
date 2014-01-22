<?php

namespace SmartCore\Module\UserRegistration\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use SmartCore\Bundle\CMSBundle\Module\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationController extends Controller
{
    public function indexAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return new RedirectResponse($request->getBaseUrl() . '/user/');
        }

        return $this->forward('FOSUserBundle:Registration:register');
    }

    public function confirmedAction()
    {
        return $this->forward('FOSUserBundle:Registration:confirmed');
    }

    public function checkEmailAction(Request $request)
    {
        if ($this->container->get('session')->has('fos_user_send_confirmation_email/email')) {
            return $this->forward('FOSUserBundle:Registration:checkEmail');
        }

        return new RedirectResponse($request->getBaseUrl() . '/user/');
    }

    public function confirmAction($token)
    {
        return $this->forward('FOSUserBundle:Registration:confirm', ['token' => $token]);
    }
}
