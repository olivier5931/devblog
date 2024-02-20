<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DebuggingRepository")
 */
class Debugging {

  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $title;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $category;

  /**
   * @ORM\Column(type="text")
   */
  private $solution;

  /**
   * @ORM\Column(type="text")
   */
  private $solutioneng;

  /**
   * @ORM\Column(type="datetime")
   */
  private $createdAt;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="debugging")
   */
  private $comments;

  public function __construct() {
    $this->comments = new ArrayCollection();
  }

  public function getId() {
    return $this->id;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;

    return $this;
  }

  public function getCategory() {
    return $this->category;
  }

  public function setCategory($category) {
    $this->category = $category;

    return $this;
  }

  public function getSolution() {
    return $this->solution;
  }

  public function setSolution($solution) {
    $this->solution = $solution;

    return $this;
  }

  public function getSolutioneng() {
    return $this->solutioneng;
  }

  public function setSolutioneng($solutioneng) {
    $this->solutioneng = $solutioneng;

    return $this;
  }

  public function getCreatedAt() {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt) {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * @return Collection|Comment[]
   */
  public function getComments(): Collection {
    return $this->comments;
  }

  public function addComment(Comment $comment): self {
    if (!$this->comments->contains($comment)) {
      $this->comments[] = $comment;
      $comment->setDebugging($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): self {
    if ($this->comments->contains($comment)) {
      $this->comments->removeElement($comment);
      // set the owning side to null (unless already changed)
      if ($comment->getDebugging() === $this) {
        $comment->setDebugging(null);
      }
    }

    return $this;
  }
}
