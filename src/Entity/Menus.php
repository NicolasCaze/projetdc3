<?php

namespace App\Entity;

use App\Repository\MenusRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenusRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Menus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Products::class)]
    private Collection $product_id;

    #[ORM\ManyToMany(targetEntity: Attachments::class)]
    private Collection $attachment_id;

    #[ORM\ManyToMany(targetEntity: Orders::class)]
    private Collection $order_id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
        public function updateTimestamps(): void
    {
        // Ne met à jour created_at que lors de la création de l'entité
        if ($this->created_at === null) {
            $this->created_at = new \DateTime();
            $this->updated_at = new \DateTime();
        }
    }

    public function __construct()
    {
        $this->product_id = new ArrayCollection();
        $this->attachment_id = new ArrayCollection();
        $this->order_id = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProductId(): Collection
    {
        return $this->product_id;
    }

    public function addProductId(Products $productId): static
    {
        if (!$this->product_id->contains($productId)) {
            $this->product_id->add($productId);
        }

        return $this;
    }

    public function removeProductId(Products $productId): static
    {
        $this->product_id->removeElement($productId);

        return $this;
    }

    /**
     * @return Collection<int, Attachments>
     */
    public function getAttachmentId(): Collection
    {
        return $this->attachment_id;
    }

    public function addAttachmentId(Attachments $attachmentId): static
    {
        if (!$this->attachment_id->contains($attachmentId)) {
            $this->attachment_id->add($attachmentId);
        }

        return $this;
    }

    public function removeAttachmentId(Attachments $attachmentId): static
    {
        $this->attachment_id->removeElement($attachmentId);

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrderId(): Collection
    {
        return $this->order_id;
    }

    public function addOrderId(Orders $orderId): static
    {
        if (!$this->order_id->contains($orderId)) {
            $this->order_id->add($orderId);
        }

        return $this;
    }

    public function removeOrderId(Orders $orderId): static
    {
        $this->order_id->removeElement($orderId);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
