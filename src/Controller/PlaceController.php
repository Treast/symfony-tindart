<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlaceController extends ApiController {

    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var PlaceRepository */
    private $placeRepository;
    /** @var ValidatorInterface  */
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, PlaceRepository $placeRepository, ValidatorInterface $validator) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->placeRepository = $placeRepository;
        $this->validator = $validator;
    }

    public function getPlacesAction() {
        $places = $this->placeRepository->findAll();

        return $this->renderJson($places);
    }

    /**
     * @param Place $place
     * @return Response
     */
    public function getPlaceAction(Place $place) {
        return $this->renderJson($place);
    }

    /**
     * @ParamConverter("bodyPlace", converter="fos_rest.request_body")
     * @param Place $bodyPlace
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postPlacesAction(Place $bodyPlace) {
        $errors = $this->validator->validate($bodyPlace);
        if($errors->count() === 0) {
            $place = new Place();
            $place->setName($bodyPlace->getName());
            $place->setAddress($bodyPlace->getAddress());
            $place->setLatitude($bodyPlace->getLatitude());
            $place->setLongitude($bodyPlace->getLongitude());

            $this->entityManager->persist($place);
            $this->entityManager->flush();
            return $this->renderJson($place);
        }

        return $this->renderJson((string)$errors);
    }

    /**
     * @ParamConverter("bodyPlace", converter="fos_rest.request_body")
     * @param Place $place
     * @param Place $bodyPlace
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putPlacesAction(Place $place, Place $bodyPlace) {
        $place->setName($bodyPlace->getName());
        $place->setAddress($bodyPlace->getAddress());
        $place->setLatitude($bodyPlace->getLatitude());
        $place->setLongitude($bodyPlace->getLongitude());

        $this->entityManager->persist($place);
        $this->entityManager->flush();

        return $this->renderJson($place);
    }

    /**
     * @param Place $place
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deletePlacesAction(Place $place) {
        $this->entityManager->remove($place);
        $this->entityManager->flush();

        return $this->renderJson(['success' => true]);
    }
}