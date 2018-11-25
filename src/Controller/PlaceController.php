<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends ApiController {

    private $entityManager;
    /** @var PlaceRepository */
    private $placeRepository;

    public function __construct(EntityManagerInterface $entityManager, PlaceRepository $placeRepository) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->placeRepository = $placeRepository;
    }

    public function getPlacesAction() {
        $places = $this->placeRepository->findAll();

        return $this->renderJson($places);
    }
}