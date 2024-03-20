<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ServiceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ServiceCategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'service_category:item']),
        new GetCollection(normalizationContext: ['groups' => 'service_category:list']),
        new Post(denormalizationContext: ['groups' => 'service_category:write']),
        new Put(denormalizationContext: ['groups' => 'service_category:write'])
    ],
    paginationEnabled: false,
)]
class ServiceCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['service_category:list', 'service_category:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['service:item', 'service_category:list', 'service_category:item', 'service_category:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['service_category:list', 'service_category:item', 'service_category:write'])]
    private ?string $description = null;

    /**
     * @var Collection<Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'category', cascade: ["persist"], orphanRemoval: true)]
    #[ApiProperty(writable: false)]
    #[Groups(['service_category:write', 'service_category:item'])]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setCategory($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getCategory() === $this) {
                $service->setCategory(null);
            }
        }

        return $this;
    }
}
