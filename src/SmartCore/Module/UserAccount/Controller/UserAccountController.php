<?php

namespace SmartCore\Module\UserAccount\Controller;

use SmartCore\Bundle\EngineBundle\Module\Controller;
use SmartCore\Bundle\EngineBundle\Response;

class UserAccountController extends Controller
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
            $this->View->show = $this->forward('FOSUserBundle:Profile:show')->getContent();
        } else {
            $this->View->login = $this->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()])->getContent();
        }

        return new Response($this->View);
    }
    
    /**
     * Редактирование.
     */
    public function editAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->View->edit = $this->forward('FOSUserBundle:Profile:edit')->getContent();
        } else {
            $this->View->login = $this->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()])->getContent();
        }
        
        return new Response($this->View);
    }
    
    public function changePasswordAction()
    {
        $this->View->login = $this->forward('FOSUserBundle:ChangePassword:changePassword')->getContent();
        
        return new Response($this->View);
    }
    
    public function resettingRequestAction()
    {
        $this->View->login = $this->forward('FOSUserBundle:Resetting:request')->getContent();
        
        return new Response($this->View);
    }
    
    public function resettingSendEmailAction()
    {
        $this->View->login = $this->forward('FOSUserBundle:Resetting:sendEmail')->getContent();
        
        return new Response($this->View);
    }
    
    public function resettingCheckEmailAction()
    {
        $this->View->login = $this->forward('FOSUserBundle:Resetting:checkEmail')->getContent();
        
        return new Response($this->View);
    }
        
    public function resettingResetAction($params)
    {
        $this->View->login = $this->forward('FOSUserBundle:Resetting:reset', ['token' => $params['token']])->getContent();
        
        return new Response($this->View);
    }
}
