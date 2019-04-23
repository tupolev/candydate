<?php

namespace Candydate\Controller\Rest;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TestController extends AbstractFOSRestController
{

    /**
     * @Rest\Post("/test")
     * @param Request $request
     * @return View
     */
    public function testAction(Request $request): View
    {
        var_dump($this->get('security.token_storage')->getToken());
        die();
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('/api/test');
        }
        $data = json_decode($request->getContent(), true);

        return View::create($data);
    }
}
