<?php

namespace App\Entity;

use App\Repository\CartLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartLineRepository::class)]
class CartLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $Product = null;

    #[ORM\Column]
    private ?int $Quantity = null;

    #[ORM\ManyToOne(inversedBy: 'cartLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $CartId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): static
    {
        $this->Product = $Product;

        return $this;
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

    public function getCartId(): ?Cart
    {
        return $this->CartId;
    }

    public function setCartId(?Cart $CartId): static
    {
        $this->CartId = $CartId;

        return $this;
    }
}
