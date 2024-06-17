<?php

namespace App\EventSubscriber;


use App\Entity\Evenement;

use App\Repository\PersonneRepository;
use App\Repository\EvenementRepository;
use App\Repository\ActualiteRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $bookingRepository;
    private $router;
    private $eventRepository;
    private $actualiteRepository;
    public function __construct(
        
        EvenementRepository  $eventRepository,
       
        UrlGeneratorInterface $router
    ) {
       
        $this->eventRepository=$eventRepository;
      

        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();




        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $bookings = $this->bookingRepository->findAll();
        $events=$this->eventRepository->findAll();
        $actualites=$this->actualiteRepository->findAll();
        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getNom(),
            $booking->getDatenaissance(),
            $booking->getDatenaissance()// If the end date is null or not defined, a all day event is created.
            );


            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $bookingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);


            $bookingEvent->addOption(
                'url',
                $this->router->generate('app_personne_show', [
                    'id' => $booking->getId(),
                ])
            );


            // finally, add the event to the CalendarEvent to fill the calendar
           $calendar->addEvent($bookingEvent);

            //$calendar->addEvent($CalendarEvent);
        }


        // partie events


        foreach ($events as $event) {
            // this create the events with your data (here booking data) to fill calendar
            $EvenementEvent = new Event(
                $event->getNameevent(),
                $event->getDatedebut(),
                $event->getDatefin()// If the end date is null or not defined, a all day event is created.
            );


            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $EvenementEvent->setOptions([
                'backgroundColor' => 'blue',
                'borderColor' => 'blue',
            ]);


            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($EvenementEvent);

            //$calendar->addEvent($CalendarEvent);
        }
         // actualites
        foreach ($actualites as $actualite) {
            // this create the events with your data (here booking data) to fill calendar
            $ActualiteEvent = new Event(
                $actualite->getTitre(),
                $actualite->getDateajout(),
                $actualite->getDateajout()// If the end date is null or not defined, a all day event is created.
            );


            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $ActualiteEvent->setOptions([
                'backgroundColor' => 'green',
                'borderColor' => 'green',
            ]);


            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($ActualiteEvent);

            //$calendar->addEvent($CalendarEvent);
        }



    }
}