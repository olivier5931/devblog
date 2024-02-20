<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment {

  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $username;

  /**
   * @ORM\Column(type="text")
   * Assert\NotBlank()
   * @Assert\Length(max=800)
   */
  private $content;

  /**
   * @ORM\Column(type="datetime")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $language;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
   */
  private $article;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\Debugging", inversedBy="comments")
   */
  private $debugging;

  public function getId() {
    return $this->id;
  }

  public function getUsername() {
    return $this->username;
  }

  public function setUsername($username) {
    $this->username = $username;

    return $this;
  }

  public function getContent() {
    return $this->content;
  }

  public function setContent($content) {
    $this->content = $content;

    return $this;
  }

  public function getCreatedAt() {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt) {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getLanguage() {
    return $this->language;
  }

  public function setLanguage($language) {
    $this->language = $language;

    return $this;
  }

  public function getArticle(): Article {
    return $this->article;
  }

  public function setArticle(Article $article): self {
    $this->article = $article;

    return $this;
  }

  public function getDebugging(): Debugging {
    return $this->debugging;
  }

  public function setDebugging(Debugging $debugging): self {
    $this->debugging = $debugging;

    return $this;
  }
}
