<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $ofOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Produit
    {
        return $this->product;
    }

    public function setProduct(?Produit $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOfOrder(): ?Order
    {
        return $this->ofOrder;
    }

    public function setOfOrder(?Order $ofOrder): self
    {
        $this->ofOrder = $ofOrder;

        return $this;
    }
}
