<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // length min and max

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */

class Article {

  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $image;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $title;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $titleeng;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $category;

  /**
   * @ORM\Column(type="text")
   */
  private $content;

  /**
   * @ORM\Column(type="text")
   */
  private $contenteng;

  /**
   * @ORM\Column(type="text")
   */
  private $fullcontent;

  /**
   * @ORM\Column(type="text")
   */
  private $fullcontenteng;

  /**
   * @ORM\Column(type="datetime")
   */
  private $createdAt;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article")
   */
  private $comments;

  public function __construct() {
    $this->comments = new ArrayCollection();
  }

  public function getId() {
    return $this->id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage($image) {
    $this->image = $image;

    return $this;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;

    return $this;
  }

  public function getTitleeng() {
    return $this->titleeng;
  }

  public function setTitleeng($titleeng) {
    $this->titleeng = $titleeng;

    return $this;
  }

  public function getCategory() {
    return $this->category;
  }

  public function setCategory($category) {
    $this->category = $category;

    return $this;
  }

  public function getContent() {
    return $this->content;
  }

  public function setContent($content) {
    $this->content = $content;

    return $this;
  }

  public function getContenteng() {
    return $this->contenteng;
  }

  public function setContenteng($contenteng) {
    $this->contenteng = $contenteng;

    return $this;
  }

  public function getFullcontent() {
    return $this->fullcontent;
  }

  public function setFullcontent($fullcontent) {
    $this->fullcontent = $fullcontent;

    return $this;
  }

  public function getFullcontenteng() {
    return $this->fullcontenteng;
  }

  public function setFullcontenteng($fullcontenteng) {
    $this->fullcontenteng = $fullcontenteng;

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
      $comment->setArticle($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): self {
    if ($this->comments->contains($comment)) {
      $this->comments->removeElement($comment);
      // set the owning side to null (unless already changed)
      if ($comment->getArticle() === $this) {
        $comment->setArticle(null);
      }
    }

    return $this;
  }

}
