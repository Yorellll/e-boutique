<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $OrderNumber = null;

    #[ORM\Column]
    private ?bool $Valid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateTime = null;

    #[ORM\OneToMany(targetEntity: CommandLine::class, mappedBy: 'OrderNumber')]
    private Collection $commandLines;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

//    #[ORM\OneToOne(targetEntity: Cart::class)]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?Cart $cart;

    #[ORM\ManyToOne(targetEntity: Cart::class)]
    #[ORM\JoinColumn(name: "cart_id", referencedColumnName: "id", nullable: false)]
    private ?Cart $cart;

    public function __construct()
    {
        $this->commandLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?int
    {
        return $this->OrderNumber;
    }

    public function setOrderNumber(int $OrderNumber): static
    {
        $this->OrderNumber = $OrderNumber;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->Valid;
    }

    public function setValid(bool $Valid): static
    {
        $this->Valid = $Valid;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->DateTime;
    }

    public function setDateTime(\DateTimeInterface $DateTime): static
    {
        $this->DateTime = $DateTime;

        return $this;
    }

    /**
     * @return Collection<int, CommandLine>
     */
    public function getCommandLines(): Collection
    {
        return $this->commandLines;
    }

    public function addCommandLine(CommandLine $commandLine): static
    {
        if (!$this->commandLines->contains($commandLine)) {
            $this->commandLines->add($commandLine);
            $commandLine->setOrderNumber($this);
        }

        return $this;
    }

    public function removeCommandLine(CommandLine $commandLine): static
    {
        if ($this->commandLines->removeElement($commandLine)) {
            // set the owning side to null (unless already changed)
            if ($commandLine->getOrderNumber() === $this) {
                $commandLine->setOrderNumber(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }
}
