<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Utils\GeocodingInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /** @var GeocodingInterface  */
    private $geocoder;

    public function __construct(GeocodingInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [];
        $places = [];
        $events = [];

        $usersFixtures = [
            [
                'email' => 'hello@spawn.fr',
                'password' => 'testing'
            ],
            [
                'email' => 'test@test.fr',
                'password' => 'test'
            ]
        ];

        $placesFixtures = [
            [
                'name' => 'Bonlieu Scène Nationale Annecy',
                'address' => '1 Rue Jean Jaurès, 74000 Annecy',
                'image' => 'http://bibliotheques.agglo-annecy.fr/images/stories/Annecy_/Bonlieu/equipement/Bonlieu-Annecy.jpg'
            ],
            [
                'name' => 'Les Escholiers',
                'address' => '26 Rue Sommeiller, 74000 Annecy',
                'image' => 'https://i.imgur.com/aBj7ysw.jpg'
            ],
            [
                'name' => 'Château d\'Annecy',
                'address' => '1 Place du Château, 74000 Annecy',
                'image' => 'https://i.imgur.com/RJWVUn4.jpg'
            ],
        ];

        $eventsFixtures = [
            [
                'name' => 'Diffusion de Saint Seiya - Le film sur Grand Ecran',
                'event_date' => '2018-12-30 18:00:00',
                'description' => 'Profitez de l\'écran géant placé pour fêter ensemble les fêtes de fin d\'année avec un chef d\'oeuvre du film d\'animation japonais.'
            ],
            [
                'name' => 'Anniversaire de Pablo Picasso',
                'event_date' => '2018-12-22 12:00:00',
                'description' => 'Célébrons ensemble l\'art et la vision de Pablo Picasso, le peintre du 20ème siècle.'
            ],
            [
                'name' => 'Exposition oeuvres mortes',
                'event_date' => '2010-01-15 08:00:00',
                'description' => 'L\'association de dessin d\'Annecy sont fiers de vous convier à l\'anuelle exposition d\'oeuvres mortes réalisée par les membres. Entrée gratuit.'
            ]
        ];

        foreach ($usersFixtures as $userFixture) {
            $user = new User();
            $user->setEmail($userFixture['email']);
            $user->setPassword($userFixture['password']);

            $manager->persist($user);
            $users[] = $user;
        }

        foreach ($placesFixtures as $placeFixture) {
            $place = new Place();
            $place->setName($placeFixture['name']);
            $place->setAddress($placeFixture['address']);
            $place->setImage($placeFixture['image']);

            $coordinates = $this->geocoder->geocode($placeFixture['address']);

            if(!is_null($coordinates)) {
                $place->setLongitude($coordinates->getLongitude());
                $place->setLatitude($coordinates->getLatitude());
            }

            $manager->persist($place);
            $places[] = $place;
        }

        foreach ($eventsFixtures as $eventFixture) {
            $event = new Event();
            $event->setName($eventFixture['name']);
            $event->setEventDate(new \DateTime($eventFixture['event_date']));
            $event->setDescription($eventFixture['description']);
            $event->setPlace($places[array_rand($places)]);

            $manager->persist($event);
            $events[] = $event;
        }

        for($i = 0; $i < 3; $i++) {
            $event = $events[array_rand($events)];
            $event->addParticipant($users[array_rand($users)]);

            $manager->persist($event);
        }

        $manager->flush();
    }
}
