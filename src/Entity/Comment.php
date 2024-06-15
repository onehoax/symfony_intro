<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $comment;

  /**
   * @ORM\Column(type="datetime")
   */
  private $publish_date;

  /**
   * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comment")
   */
  private $user;

  /**
   * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comment")
   */
  private $post;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getComment(): ?string
  {
    return $this->comment;
  }

  public function setComment(string $comment): self
  {
    $this->comment = $comment;

    return $this;
  }

  public function getPublishDate(): ?\DateTimeInterface
  {
    return $this->publish_date;
  }

  public function setPublishDate(\DateTimeInterface $publish_date): self
  {
    $this->publish_date = $publish_date;

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

  public function getPost(): ?Post
  {
    return $this->post;
  }

  public function setPost(?Post $post): self
  {
    $this->post = $post;

    return $this;
  }
}
