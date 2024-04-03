<?php

namespace App\Entity;

use App\Repository\OrderOfferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderOfferRepository::class)]
class OrderOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offer $Offer = null;

    #[ORM\ManyToOne(inversedBy: 'orderOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $Orderr = null;

    #[ORM\Column]
    private ?int $Type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffer(): ?Offer
    {
        return $this->Offer;
    }

    public function setOffer(?Offer $Offer): static
    {
        $this->Offer = $Offer;

        return $this;
    }

    public function getOrderr(): ?Order
    {
        return $this->Orderr;
    }

    public function setOrderr(?Order $Orderr): static
    {
        $this->Orderr = $Orderr;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->Type;
    }

    public function setType(int $Type): static
    {
        $this->Type = $Type;

        return $this;
    }
}
