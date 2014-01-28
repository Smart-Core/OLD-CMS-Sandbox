<?php

namespace SmartCore\Module\Feedback\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function indexAction()
    {
        return new Response('@todo ' . __METHOD__);
    }
}
