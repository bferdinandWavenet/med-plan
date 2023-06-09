<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $unavailable_days = [];

    #[ORM\Column(nullable: true)]
    private ?int $total_points = null;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Shift::class)]
    private Collection $shifts;

    public function __construct()
    {
        $this->shifts = new ArrayCollection();
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

    public function getUnavailableDays(): array
    {
        return $this->unavailable_days;
    }

    public function setUnavailableDays(?array $unavailable_days): static
    {
        $this->unavailable_days = $unavailable_days;

        return $this;
    }

    public function getTotalPoints(): ?int
    {
        return $this->total_points;
    }

    public function setTotalPoints(?int $total_points): static
    {
        $this->total_points = $total_points;

        return $this;
    }

    /**
     * @return Collection<int, Shift>
     */
    public function getShifts(): Collection
    {
        return $this->shifts;
    }

    public function addShift(Shift $shift): static
    {
        if (!$this->shifts->contains($shift)) {
            $this->shifts->add($shift);
            $shift->setDoctor($this);
        }

        return $this;
    }

    public function removeShift(Shift $shift): static
    {
        if ($this->shifts->removeElement($shift)) {
            // set the owning side to null (unless already changed)
            if ($shift->getDoctor() === $this) {
                $shift->setDoctor(null);
            }
        }

        return $this;
    }
}
