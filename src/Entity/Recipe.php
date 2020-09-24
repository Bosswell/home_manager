<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 1,
     *     max = 255,
     *     maxMessage = "Recipe name cannot be longer than {{ limit }} characters",
     *     minMessage = "Recipe name cannot be empty"
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     * * @Assert\Length(
     *     min= 1,
     *     minMessage = "Recipe content cannot be empty"
     * )
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;

    public function __construct(string $name, string $content, UserInterface $user)
    {
        $this->name = $name;
        $this->content = $content;
        $this->user = $user;
        $this->isDeleted = false;
        $this->createdAt = new \DateTime();
    }

    public function update(string $name, string $content)
    {
        $this->name = $name;
        $this->content = $content;
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function delete(): void
    {
        $this->isDeleted = true;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
