<?php

namespace Candydate\Controller\OAuth;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthController extends AbstractFOSRestController
{
    private $clientManager;

    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * @Rest\Post("/oauth/v2/create")
     * @param Request $request
     * @return View
     */
    public function createClientAction(Request $request): View
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['redirect-uri']) || empty($data['grant-type'])) {
            return View::create($data, Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }
        $clientManager = $this->clientManager;
        $client = $clientManager->createClient();
        $client->setRedirectUris([$data['redirect-uri']]);
        $client->setAllowedGrantTypes([$data['grant-type']]);
        $clientManager->updateClient($client);
        $rows = [
            'client_id' => $client->getPublicId(), 'client_secret' => $client->getSecret()
        ];

        return View::create($rows, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
