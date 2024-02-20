<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatisticRepository")
 */
class Statistic
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity=User::class, inversedBy="statistics")
   */
  private $user;

  /**
   * @ORM\ManyToOne(targetEntity=Poll::class, inversedBy="statistics")
   */
  private $poll;

  /**
   * @ORM\ManyToOne(targetEntity=Opinion::class, inversedBy="statistics")
   */
  private $opinion;

  public function getId()
  {
    return $this->id;
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

	public function getPoll(): ?Poll
	{
		return $this->poll;
	}

	public function setPoll(?Poll $poll): self
	{
		$this->poll = $poll;

		return $this;
	}

	public function getOpinion(): ?Opinion
	{
		return $this->opinion;
	}

	public function setOpinion(?Opinion $opinion): self
	{
		$this->opinion = $opinion;

		return $this;
	}
}
