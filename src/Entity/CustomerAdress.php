<?php

namespace App\Entity;

use App\Repository\CustomerAdressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerAdressRepository::class)]
class CustomerAdress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Type = null;

    #[ORM\ManyToOne(inversedBy: 'customerAdresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Name = null;

    #[ORM\ManyToOne(inversedBy: 'customerAdresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $FirstName = null;

    #[ORM\ManyToOne(inversedBy: 'customerAdresses')]
    private ?User $Phone = null;

    #[ORM\Column(length: 50)]
    private ?string $Address = null;

    #[ORM\Column(length: 15)]
    private ?string $Cp = null;

    #[ORM\Column(length: 50)]
    private ?string $City = null;

    #[ORM\Column(length: 50)]
    private ?string $Country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getName(): ?User
    {
        return $this->Name;
    }

    public function setName(?User $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getFirstName(): ?User
    {
        return $this->FirstName;
    }

    public function setFirstName(?User $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getPhone(): ?User
    {
        return $this->Phone;
    }

    public function setPhone(?User $Phone): static
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->Cp;
    }

    public function setCp(string $Cp): static
    {
        $this->Cp = $Cp;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): static
    {
        $this->City = $City;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): static
    {
        $this->Country = $Country;

        return $this;
    }
}
