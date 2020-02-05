<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FoodRepository")
 */
class Food
{
    const STATUS_BASKET = 'basket';
    const STATUS_PUBLISH = 'publish';

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
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\JoinTable(name="views")
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    private $views;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="foods")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="likes")
     */
    private $likes;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FoodProduct", mappedBy="food")
     */
    private $foodProducts;

    public function __construct()
    {
        $this->views = new ArrayCollection();
        $this->likes = new ArrayCollection();
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

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

    /**
     * @return Collection|User[]
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(User $view): self
    {
        if (!$this->views->contains($view)) {
            $this->views[] = $view;
        }

        return $this;
    }

    public function removeView(User $view): self
    {
        if ($this->views->contains($view)) {
            $this->views->removeElement($view);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(User $user): self
    {
        if (!$this->likes->contains($user)) {
            $this->likes[] = $user;
            $user->addLike($this);
        }

        return $this;
    }

    public function removeLike(User $user): self
    {
        if ($this->likes->contains($user)) {
            $this->likes->removeElement($user);
            $user->removeLike($this);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice()
    {
        $price = 0;

        foreach ($this->foodProducts as $entity) {
            $result = $entity->getProduct()->getPrice() * $entity->getQuantity();
            $price += $result;
        }

        return $price;
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
            $foodProduct->setFood($this);
        }

        return $this;
    }

    public function removeFoodProduct(FoodProduct $foodProduct): self
    {
        if ($this->foodProducts->contains($foodProduct)) {
            $this->foodProducts->removeElement($foodProduct);
            // set the owning side to null (unless already changed)
            if ($foodProduct->getFood() === $this) {
                $foodProduct->setFood(null);
            }
        }

        return $this;
    }
}
