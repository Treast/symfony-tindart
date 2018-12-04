<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class AuthenticationController extends ApiController {

    /** @var EntityManager  */
    private $entityManager;
    /** @var UserRepository  */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @SWG\Response(
     *     response=200,
     *     description="Authenticate an user",
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="User email"
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="User plain password (not hashed)"
     * )
     * @SWG\Tag(name="Authentication")
     */
    public function postLoginAction(Request $request) {
        $user = $this->userRepository->findByCredentials($request->get('email'), $request->get('password'));

        if($user) {
            return $this->renderJson($user);
        }

        return $this->renderJson(['success' => false]);
    }
}