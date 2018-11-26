<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends ApiController {

    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var UserRepository  */
    private $userRepository;
    /** @var ValidatorInterface  */
    private $validator;

    /**
     * UserController constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction() {
        $users = $this->userRepository->findAll();

        return $this->renderJson($users);
    }

    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction(User $user) {
        return $this->renderJson($user);
    }

    /**
     * @ParamConverter("bodyUser", converter="fos_rest.request_body")
     * @param User $bodyUser
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUsersAction(User $bodyUser) {
        $errors = $this->validator->validate($bodyUser);

        if($errors->count() === 0) {
            $user = new User();
            $user->setEmail($bodyUser->getEmail());
            $user->setPassword($bodyUser->getPassword());
            $user->setRoles($bodyUser->getRoles());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->renderJson($user);
        }

        return $this->renderJson((string)$errors);
    }

    /**
     * @ParamConverter("bodyUser", converter="fos_rest.request_body")
     * @param User $user
     * @param User $bodyUser
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putUsersAction(User $bodyUser) {
        $user = $this->getUser();

        $user->setEmail($bodyUser->getEmail());
        $user->setPassword(password_hash($bodyUser->getPassword(), PASSWORD_BCRYPT));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->renderJson($user);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUsersAction() {
        $this->entityManager->remove($this->getUser());
        $this->entityManager->flush();

        return $this->renderJson(['success' => true]);
    }
}