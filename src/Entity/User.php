<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  public const REGISTER_SUCCESS = "You have registered successfully !!";

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
   */
  private $email;

  /**
   * @ORM\Column(type="json")
   */
  private $roles = [];

  /**
   * @var string The hashed password
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\Column(type="boolean")
   */
  private $banned;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
   */
  private $comment;

  /**
   * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
   */
  private $post;

  /**
   * @ORM\OneToMany(targetEntity=Profession::class, mappedBy="user")
   */
  private $profession;

  public function __construct()
  {
    $this->roles = ["ROLE_USER"];
    $this->banned = false;
    $this->comment = new ArrayCollection();
    $this->post = new ArrayCollection();
    $this->profession = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * @deprecated since Symfony 5.3, use getUserIdentifier instead
   */
  public function getUsername(): string
  {
    return (string) $this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = "ROLE_USER";

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Returning a salt is only needed, if you are not using a modern
   * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
   *
   * @see UserInterface
   */
  public function getSalt(): ?string
  {
    return null;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function isBanned(): ?bool
  {
    return $this->banned;
  }

  public function setBanned(bool $banned): self
  {
    $this->banned = $banned;

    return $this;
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
   * @return Collection<int, Comment>
   */
  public function getComment(): Collection
  {
    return $this->comment;
  }

  public function addComment(Comment $comment): self
  {
    if (!$this->comment->contains($comment)) {
      $this->comment[] = $comment;
      $comment->setUser($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): self
  {
    if ($this->comment->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getUser() === $this) {
        $comment->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Post>
   */
  public function getPost(): Collection
  {
    return $this->post;
  }

  public function addPost(Post $post): self
  {
    if (!$this->post->contains($post)) {
      $this->post[] = $post;
      $post->setUser($this);
    }

    return $this;
  }

  public function removePost(Post $post): self
  {
    if ($this->post->removeElement($post)) {
      // set the owning side to null (unless already changed)
      if ($post->getUser() === $this) {
        $post->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Profession>
   */
  public function getProfession(): Collection
  {
    return $this->profession;
  }

  public function addProfession(Profession $profession): self
  {
    if (!$this->profession->contains($profession)) {
      $this->profession[] = $profession;
      $profession->setUser($this);
    }

    return $this;
  }

  public function removeProfession(Profession $profession): self
  {
    if ($this->profession->removeElement($profession)) {
      // set the owning side to null (unless already changed)
      if ($profession->getUser() === $this) {
        $profession->setUser(null);
      }
    }

    return $this;
  }
}
