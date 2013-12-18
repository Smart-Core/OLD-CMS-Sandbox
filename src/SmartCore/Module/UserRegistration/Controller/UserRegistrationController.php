<?php

namespace SmartCore\Module\UserRegistration\Controller;

//use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SmartCore\Bundle\EngineBundle\Module\Controller;
use SmartCore\Bundle\EngineBundle\Module\RouterResponse;
use SmartCore\Bundle\EngineBundle\Response;

class UserRegistrationController extends Controller
{
    protected function init()
    {
        $this->View->setOptions([
            'engine' => 'echo',
        ]);
    }

    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return new RedirectResponse($this->getRequest()->getBaseUrl() . '/user/');
        } else {
            $this->View->register = $this->forward('SmartCoreFOSUserBundle:Registration:register')->getContent();
        }
        
        return new Response($this->View);
    }

    public function routerAction($slug)
    {
        $Response = new RouterResponse();
        $slug_parts = explode('/', $slug);
        
        switch ($slug_parts[0]) {
            case 'check-email':
                $Response->setAction('checkEmail');
                break;
            case 'confirm':        
                if (isset($slug_parts[1]) and strlen($slug_parts[1]) > 0) {
                    $Response->setAction('confirm');
                    $Response->setArgument('token', $slug_parts[1]);
                }
                break;
            default;
        }

        return $Response;
    }

    public function confirmedAction()
    {
        $this->View->register = $this->forward('FOSUserBundle:Registration:confirmed')->getContent();

        return new Response($this->View);
    }

    public function checkEmailAction()
    {
        if ($this->container->get('session')->has('fos_user_send_confirmation_email/email')) {
            $this->View->register = $this->forward('FOSUserBundle:Registration:checkEmail')->getContent();
        } else {
            return new RedirectResponse($this->getRequest()->getBaseUrl() . '/user/');
        }

        return new Response($this->View);
    }

    public function confirmAction($token)
    {
        $this->View->register = $this->forward('SmartCoreFOSUserBundle:Registration:confirm', ['token' => $token])->getContent();
        
        return new Response($this->View);
    }
}
