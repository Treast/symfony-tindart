<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Place;
use App\Repository\EventRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends ApiController {

    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var EventRepository  */
    private $eventRepository;
    /** @var ValidatorInterface  */
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, EventRepository $eventRepository, ValidatorInterface $validator) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
        $this->validator = $validator;
    }

    /**
     * @param Place $place
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns all events in a place",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Event::class, groups={"default"}))
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Token")
     */
    public function getEventsAction(Place $place) {
        return $this->renderJson($place->getEvents());
    }

    /**
     * @param Place $place
     * @param Event $event
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns an event in a place",
     *     @SWG\Schema(
     *         ref=@Model(type=Event::class, groups={"default"})
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Token")
     */
    public function getEventAction(Place $place, Event $event) {
        return $this->renderJson($event);
    }

    /**
     * @ParamConverter("bodyEvent", converter="fos_rest.request_body")
     * @param Place $place
     * @param Event $bodyEvent
     * @return \Symfony\Component\HttpFoundation\Response
     * @SWG\Response(
     *     response=200,
     *     description="Create an event in a place",
     *     @SWG\Schema(
     *         ref=@Model(type=Event::class, groups={"default"})
     *     )
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Event name"
     * )
     * @SWG\Parameter(
     *     name="description",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Event description"
     * )
     * @SWG\Parameter(
     *     name="event_date",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Event date"
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Token")
     */
    public function postEventsAction(Place $place, Event $bodyEvent) {
        $errors = $this->validator->validate($bodyEvent);
        if($errors->count() === 0) {
            $event = new Event();
            $event->setPlace($place);
            $event->setName($bodyEvent->getName());
            $event->setDescription($bodyEvent->getDescription());
            $event->setEventDate($bodyEvent->getEventDate());


            $this->entityManager->persist($event);
            $this->entityManager->flush();
            return $this->renderJson($event);
        }

        return $this->renderErrors($errors);
    }

    /**
     * @ParamConverter("bodyEvent", converter="fos_rest.request_body")
     * @param Place $place
     * @param Event $bodyEvent
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     * @SWG\Response(
     *     response=200,
     *     description="Update a event in a place",
     *     @SWG\Schema(
     *         ref=@Model(type=Event::class, groups={"default"})
     *     )
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Event name"
     * )
     * @SWG\Parameter(
     *     name="description",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Event description"
     * )
     * @SWG\Parameter(
     *     name="event_date",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Event date"
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Token")
     */
    public function putEventAction(Place $place, Event $bodyEvent, Event $event) {
        $errors = $this->validator->validate($bodyEvent);
        if($errors->count() === 0) {
            $event->setPlace($place);
            $event->setName($bodyEvent->getName());
            $event->setDescription($bodyEvent->getDescription());
            $event->setEventDate($bodyEvent->getEventDate());

            $this->entityManager->persist($place);
            $this->entityManager->flush();

            return $this->renderJson($place);
        }

        return $this->renderErrors($errors);
    }

    /**
     * @param Place $place
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     * @SWG\Response(
     *     response=200,
     *     description="Delete an event in a place",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="success", type="boolean"),
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Token")
     */
    public function deleteEventsAction(Place $place, Event $event) {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        return $this->renderJson(['success' => true]);
    }
}