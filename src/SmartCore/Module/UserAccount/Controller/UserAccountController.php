<?php

namespace SmartCore\Module\UserAccount\Controller;

use SmartCore\Bundle\CMSBundle\Module\Controller;
use SmartCore\Bundle\CMSBundle\Response;

class UserAccountController extends Controller
{
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->view->forward('FOSUserBundle:Profile:show');
        } else {
            $this->view->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()]);
        }

        return new Response($this->view);
    }
    
    public function editAction()
    {
        $this->get('cms.breadcrumbs')->add('edit', 'Редактирование');

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->view->forward('FOSUserBundle:Profile:edit');
        } else {
            $this->view->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()]);
        }

        return new Response($this->view);
    }
    
    public function changePasswordAction()
    {
        $this->get('cms.breadcrumbs')->add('change-password', 'Смена пароля');

        $this->view->forward('FOSUserBundle:ChangePassword:changePassword');
        
        return new Response($this->view);
    }
    
    public function resettingRequestAction()
    {
        $this->get('cms.breadcrumbs')->add('resetting', 'Восстановление пароля');

        $this->view->forward('FOSUserBundle:Resetting:request');
        
        return new Response($this->view);
    }
    
    public function resettingSendEmailAction()
    {
        $this->view->forward('FOSUserBundle:Resetting:sendEmail');

        return new Response($this->view);
    }
    
    public function resettingCheckEmailAction()
    {
        $this->view->forward('FOSUserBundle:Resetting:checkEmail');
        
        return new Response($this->view);
    }
        
    public function resettingResetAction($params)
    {
        $this->view->forward('FOSUserBundle:Resetting:reset', ['token' => $params['token']]);
        
        return new Response($this->view);
    }
}
