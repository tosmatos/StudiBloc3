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
    private ?int $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $total_price = null;

    #[ORM\Column(length: 500)]
    private ?string $key = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(targetEntity: OrderOffer::class, mappedBy: 'Orderr')]
    private Collection $orderOffers;

    public function __construct()
    {
        $this->orderOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->total_price;
    }

    public function setTotalPrice(int $total_price): static
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, OrderOffer>
     */
    public function getOrderOffers(): Collection
    {
        return $this->orderOffers;
    }

    public function addOrderOffer(OrderOffer $orderOffer): static
    {
        if (!$this->orderOffers->contains($orderOffer)) {
            $this->orderOffers->add($orderOffer);
            $orderOffer->setOrderr($this);
        }

        return $this;
    }

    public function removeOrderOffer(OrderOffer $orderOffer): static
    {
        if ($this->orderOffers->removeElement($orderOffer)) {
            // set the owning side to null (unless already changed)
            if ($orderOffer->getOrderr() === $this) {
                $orderOffer->setOrderr(null);
            }
        }

        return $this;
    }
}
