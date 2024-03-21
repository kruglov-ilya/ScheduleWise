<?php
namespace App\EventSubscriber;

use App\Controller\Admin\BookingCrudController;
use App\Entity\Booking;
use App\Repository\BookingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private BookingRepository $bookingRepository;
    private UrlGeneratorInterface $router;

    public function __construct(
        BookingRepository     $bookingRepository,
        UrlGeneratorInterface $router
    )
    {
        $this->bookingRepository = $bookingRepository;
        $this->router = $router;
    }

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
         * @var Booking[] $bookings
         */
        $bookings = $this->bookingRepository
            ->createQueryBuilder('booking')
            ->join('booking.timeslot', 'timeslot')
            ->where('timeslot.start BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getService()->getName(),
                $booking->getTimeslot()->getStart(),
                $booking->getTimeslot()->getStart()->add(\DateInterval::createFromDateString('60 minutes')) // If the end date is null or not defined, a all day event is created.
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
                $this->router->generate('admin_dashboard', [
                    'entityId' => $booking->getId(),
                    'crudAction' => 'edit',
                    'crudControllerFqcn' => BookingCrudController::class,
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
}