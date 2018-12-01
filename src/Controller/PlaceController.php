<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use App\Utils\GeocodingInterface;
use Swagger\Annotations as SWG;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
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
    /** @var GeocodingInterface  */
    private $geocoder;

    public function __construct(EntityManagerInterface $entityManager, PlaceRepository $placeRepository, ValidatorInterface $validator, GeocodingInterface $geocoder) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->placeRepository = $placeRepository;
        $this->validator = $validator;
        $this->geocoder = $geocoder;
    }

    /**
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns all places",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items()
     *     )
     * )
     * @SWG\Tag(name="Places")
     * @Security(name="Token")
     */
    public function getPlacesAction() {
        $places = $this->placeRepository->findAll();

        return $this->renderJson($places);
    }

    /**
     * @param Place $place
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns a place",
     * )
     * @SWG\Tag(name="Places")
     * @Security(name="Token")
     */
    public function getPlaceAction(Place $place) {
        return $this->renderJson($place);
    }

    /**
     * @ParamConverter("bodyPlace", converter="fos_rest.request_body")
     * @param Place $bodyPlace
     * @return \Symfony\Component\HttpFoundation\Response
     * @SWG\Response(
     *     response=200,
     *     description="Create a place",
     * )
     * @SWG\Tag(name="Places")
     * @Security(name="Token")
     */
    public function postPlacesAction(Place $bodyPlace) {
        $errors = $this->validator->validate($bodyPlace);
        if($errors->count() === 0) {
            $place = new Place();
            $place->setName($bodyPlace->getName());
            $place->setAddress($bodyPlace->getAddress());

            $coordinates = $this->geocoder->geocode($place->getAddress());

            if(!is_null($coordinates)) {
                $place->setLongitude($coordinates->getLongitude());
                $place->setLatitude($coordinates->getLatitude());
            }

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
     * @SWG\Response(
     *     response=200,
     *     description="Update a place",
     * )
     * @SWG\Tag(name="Places")
     * @Security(name="Token")
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
     * @SWG\Response(
     *     response=200,
     *     description="Delete a place",
     * )
     * @SWG\Tag(name="Places")
     * @Security(name="Token")
     */
    public function deletePlacesAction(Place $place) {
        $this->entityManager->remove($place);
        $this->entityManager->flush();

        return $this->renderJson(['success' => true]);
    }
}