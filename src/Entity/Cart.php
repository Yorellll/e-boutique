<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $Total = null;

    #[ORM\OneToMany(targetEntity: CartLine::class, mappedBy: 'CartId', cascade: ['persist', 'remove'])]
    private Collection $cartLines;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'cart')]
    private Collection $orders;

    public function __construct()
    {
        $this->cartLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(\DateTimeInterface $CreationDate): static
    {
        $this->CreationDate = $CreationDate;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->Total;
    }

    public function setTotal(?float $Total): static
    {
        $this->Total = $Total;

        return $this;
    }

    /**
     * @return Collection<int, CartLine>
     */
    public function getCartLines(): Collection
    {
        return $this->cartLines;
    }

    public function addCartLine(CartLine $cartLine): static
    {
        if (!$this->cartLines->contains($cartLine)) {
            $this->cartLines->add($cartLine);
            $cartLine->setCartId($this);
        }

        return $this;
    }

    public function removeCartLine(CartLine $cartLine): static
    {
        if ($this->cartLines->removeElement($cartLine)) {
            // set the owning side to null (unless already changed)
            if ($cartLine->getCartId() === $this) {
                $cartLine->setCartId(null);
            }
        }

        return $this;
    }

    public function updateTotal(): void
    {
        $total = 0.0;

        /** @var CartLine $cartLine */
        foreach ($this->cartLines as $cartLine) {
            $total += $cartLine->getProduct()->getPriceHT() * $cartLine->getQuantity();
        }

        $this->setTotal($total);
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }
}
