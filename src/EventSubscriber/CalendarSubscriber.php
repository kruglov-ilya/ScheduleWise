<?php

namespace App\EventSubscriber;

use App\Controller\Admin\TimeslotCrudController;
use App\Entity\Timeslot;
use App\Repository\TimeslotRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TimeslotRepository    $timeslotRepository,
        private UrlGeneratorInterface $router
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        /**
         * @var Timeslot[] $timeslots
         */
        $timeslots = $this->timeslotRepository
            ->createQueryBuilder('timeslot')
            ->where('timeslot.start BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($timeslots as $timeslot) {
            // this create the events with your data (here booking data) to fill calendar
            $event = new Event(
                '',
                $timeslot->getStart(),
                $timeslot->getStart()->add(\DateInterval::createFromDateString('60 minutes')) // If the end date is null or not defined, a all day event is created.
            );

            $event->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $event->addOption(
                'url',
                $this->router->generate('admin_dashboard', [
                    'entityId' => $timeslot->getId(),
                    'crudAction' => 'edit',
                    'crudControllerFqcn' => TimeslotCrudController::class,
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($event);
        }
    }
}