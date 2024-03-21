<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\API\TimeslotByServiceController;
use App\Repository\TimeslotRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeslotRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/timeslot/service/{id}',
            controller: TimeslotByServiceController::class
        )
    ],
    paginationEnabled: false,
)]
class Timeslot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $start = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $count = null;

    #[ORM\ManyToMany(targetEntity: Service::class)]
    private Collection $allowedServices;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'timeslot', orphanRemoval: true)]
    private Collection $bookings;

    public function __construct()
    {
        $this->allowedServices = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->start->format(DateTimeInterface::ATOM);
    }

    public function getTime()
    {
        return $this->start->format('Y-m-d H:i:s');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(\DateTimeImmutable $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getAllowedServices(): Collection
    {
        return $this->allowedServices;
    }

    public function addAllowedService(Service $allowedService): static
    {
        if (!$this->allowedServices->contains($allowedService)) {
            $this->allowedServices->add($allowedService);
        }

        return $this;
    }

    public function removeAllowedService(Service $allowedService): static
    {
        $this->allowedServices->removeElement($allowedService);

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setTimeslot($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getTimeslot() === $this) {
                $booking->setTimeslot(null);
            }
        }

        return $this;
    }
}
