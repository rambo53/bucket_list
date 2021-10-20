<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce nom de catégorie existe déjà.")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Wish::class, mappedBy="categories")
     * @Assert\NotBlank(message="Veuillez renseigner un nom de categorie.")
     * @Assert\Length(max=50,
     *     maxMessage="Trop long ! Maximum {{ limit }}  caractères !"
     *     )
     */
    private $wish;

    public function __construct()
    {
        $this->wish = new ArrayCollection();
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

    /**
     * @return Collection|Wish[]
     */
    public function getWish(): Collection
    {
        return $this->wish;
    }

    public function addWish(Wish $wish): self
    {
        if (!$this->wish->contains($wish)) {
            $this->wish[] = $wish;
            $wish->setCategories($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): self
    {
        if ($this->wish->removeElement($wish)) {
            // set the owning side to null (unless already changed)
            if ($wish->getCategories() === $this) {
                $wish->setCategories(null);
            }
        }

        return $this;
    }
}
