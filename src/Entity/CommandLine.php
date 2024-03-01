<?php

namespace App\Entity;

use App\Repository\CommandLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandLineRepository::class)]
class CommandLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Quantity = null;

    #[ORM\ManyToOne(inversedBy: 'commandLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $OrderNumber = null;

    #[ORM\ManyToOne(inversedBy: 'commandLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $ProductName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getOrderNumber(): ?Order
    {
        return $this->OrderNumber;
    }

    public function setOrderNumber(?Order $OrderNumber): static
    {
        $this->OrderNumber = $OrderNumber;

        return $this;
    }

    public function getProductName(): ?Product
    {
        return $this->ProductName;
    }

    public function setProductName(?Product $ProductName): static
    {
        $this->ProductName = $ProductName;

        return $this;
    }
}
