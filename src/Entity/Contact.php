<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact {

  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180)
   * Assert\NotBlank()
   * Assert\Length(min=2, max=50)
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=255)
   * Assert\NotBlank()
   * Assert\Email()
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=180)
   * Assert\NotBlank()
   * Assert\Length(min=2, max=100)
   */
  private $subject;

  /**
   * @ORM\Column(type="text")
   * Assert\NotBlank()
   * Assert\Length(min=20, max=500)
   */
  private $body;

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

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;

    return $this;
  }

  public function getSubject() {
    return $this->subject;
  }

  public function setSubject($subject) {
    $this->subject = $subject;

    return $this;
  }

  public function getBody() {
    return $this->body;
  }

  public function setBody($body) {
    $this->body = $body;

    return $this;
  }

}
