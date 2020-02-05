<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FoodProduct", mappedBy="product")
     */
    private $foodProducts;

    public function __construct()
    {
        $this->foodProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection|FoodProduct[]
     */
    public function getFoodProducts(): Collection
    {
        return $this->foodProducts;
    }

    public function addFoodProduct(FoodProduct $foodProduct): self
    {
        if (!$this->foodProducts->contains($foodProduct)) {
            $this->foodProducts[] = $foodProduct;
            $foodProduct->setProduct($this);
        }

        return $this;
    }

    public function removeFoodProduct(FoodProduct $foodProduct): self
    {
        if ($this->foodProducts->contains($foodProduct)) {
            $this->foodProducts->removeElement($foodProduct);
            // set the owning side to null (unless already changed)
            if ($foodProduct->getProduct() === $this) {
                $foodProduct->setProduct(null);
            }
        }

        return $this;
    }
}
