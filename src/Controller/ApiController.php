<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends FOSRestController {

    /** @var \JMS\Serializer\SerializerInterface  */
    protected $serializer;
    /** @var \JMS\Serializer\Context  */
    protected $serializerContext;

    public function __construct() {
        $this->serializer = SerializerBuilder::create()->build();
        $this->serializerContext = SerializationContext::create()->setGroups(['default']);
    }

    public function renderJson($data) {
        return new Response($this->serializer->serialize($data, 'json', $this->serializerContext));
    }
}