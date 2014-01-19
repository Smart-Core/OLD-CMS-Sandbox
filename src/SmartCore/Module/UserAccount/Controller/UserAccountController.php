<?php

namespace SmartCore\Module\UserAccount\Controller;

use SmartCore\Bundle\CMSBundle\Module\Controller;
use SmartCore\Bundle\CMSBundle\Response;

class UserAccountController extends Controller
{
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->View->forward('FOSUserBundle:Profile:show');
        } else {
            $this->View->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()]);
        }

        return new Response($this->View);
    }
    
    /**
     * Редактирование.
     */
    public function editAction()
    {
        // @todo $Response->addBreadcrumb('edit/', 'Редактирование');

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->View->forward('FOSUserBundle:Profile:edit');
        } else {
            $this->View->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()]);
        }

        return new Response($this->View);
    }
    
    public function changePasswordAction()
    {
        // @todo $Response->addBreadcrumb('change-password/', 'Смена пароля');

        $this->View->forward('FOSUserBundle:ChangePassword:changePassword');
        
        return new Response($this->View);
    }
    
    public function resettingRequestAction()
    {
        // @todo $Response->addBreadcrumb('resetting/', 'Восстановление пароля');

        $this->View->forward('FOSUserBundle:Resetting:request');
        
        return new Response($this->View);
    }
    
    public function resettingSendEmailAction()
    {
        $this->View->forward('FOSUserBundle:Resetting:sendEmail');
        
        return new Response($this->View);
    }
    
    public function resettingCheckEmailAction()
    {
        $this->View->forward('FOSUserBundle:Resetting:checkEmail');
        
        return new Response($this->View);
    }
        
    public function resettingResetAction($params)
    {
        $this->View->forward('FOSUserBundle:Resetting:reset', ['token' => $params['token']]);
        
        return new Response($this->View);
    }
}
