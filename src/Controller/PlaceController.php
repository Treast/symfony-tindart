<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\FOSRestBundle;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends FOSRestBundle {

    /** @var EntityManager */
    private $entityManager;
    /** @var PlaceRepository */
    private $placeRepository;
    /** @var \JMS\Serializer\SerializerInterface  */
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, PlaceRepository $placeRepository) {
        $this->entityManager = $entityManager;
        $this->placeRepository = $placeRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function getPlacesAction() {
        $places = $this->placeRepository->findAll();

        return new Response($this->serializer->serialize($places, 'json'));
    }
}