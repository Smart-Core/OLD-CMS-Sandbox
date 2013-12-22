<?php

namespace SmartCore\Module\UserAccount;

use SmartCore\Bundle\CMSBundle\Module\Bundle;
use SmartCore\Bundle\CMSBundle\Module\RouterResponse;

class UserAccountModule extends Bundle
{
    /**
     * Router.
     *
     * @param string $slug
     * @return RouterResponse
     */
    public function router($node, $slug)
    {
        $Response = new RouterResponse();
        $slug_parts = explode('/', $slug);

        switch ($slug_parts[0]) {
            case 'edit':
                $Response->setAction('edit');
                $Response->addBreadcrumb('edit/', 'Редактирование');
                break;
            case 'change-password':
                $Response->setAction('changePassword');
                $Response->addBreadcrumb('change-password/', 'Смена пароля');
                break;
            case 'resetting':
                $Response->addBreadcrumb('resetting/', 'Восстановление пароля');
                if (isset($slug_parts[1])) {
                    switch ($slug_parts[1]) {
                        case 'send-email':
                            $Response->setAction('resettingSendEmail');
                            break;
                        case 'check-email':
                            $Response->setAction('resettingCheckEmail');
                            break;
                        case 'reset':
                            if (isset($slug_parts[2]) and !empty($slug_parts[2])) {
                                $Response->setAction('resettingReset');
                                $Response->setArgument('token', $slug_parts[2]);
                            }
                            break;
                        default;
                    }
                }

                $Response->setAction('resettingRequest');
                break;
            default;
        }

        return $Response;
    }
}
