<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiController extends FOSRestController {

    /** @var \JMS\Serializer\SerializerInterface  */
    protected $serializer;
    /** @var \JMS\Serializer\Context  */
    protected $serializerContext;

    public function __construct() {
        $this->serializer = SerializerBuilder::create()->build();
        $this->serializerContext = SerializationContext::create()->enableMaxDepthChecks()->setGroups(['default']);
    }

    public function renderJson($data, $httpCode = Response::HTTP_OK) {
        return new Response($this->serializer->serialize($data, 'json', $this->serializerContext), $httpCode);
    }

    public function renderErrors(ConstraintViolationListInterface $errorsList) {
       $errors = [];

       foreach ($errorsList as $error) {
           $errors[$error->getPropertyPath()] = $error->getMessage();
       }

       return new Response($this->serializer->serialize($errors, 'json', $this->serializerContext), Response::HTTP_BAD_REQUEST);
    }
}