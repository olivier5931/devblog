<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OpinionRepository")
 */
class Opinion
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180)
   */
  private $name;

  /**
   * @ORM\Column(type="bigint", nullable=false)
   */
  private $votes;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\Poll", inversedBy="opinions")
   * @ORM\JoinColumn(nullable=true)
   */
  private $poll;

  /**
   * @ORM\Column(type="float", nullable=false)
   */
  private $votesPercentage;

  /**
   * @ORM\OneToMany(targetEntity=Statistic::class, mappedBy="opinion")
   */
  private $statistics;


  public function __construct()
  {
    $this->votes = 0;
    $this->votesPercentage = 0;
    $this->statistics = new ArrayCollection();
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  public function getVotes()
  {
    return $this->votes;
  }

  public function setVotes($votes)
  {
    $this->votes = $votes;

    return $this;
  }

  public function getPoll()
  {
    return $this->poll;
  }

  public function setPoll($poll)
  {
    $this->poll = $poll;

    return $this;
  }

  public function __toString()
  {
    if ($this->id) {
      return $this->name;
    }
    return 'New Choice';
  }

  public function getVotesPercentage()
  {
    if ($this->votesPercentage) {
      return $this->votesPercentage;
    }

    if ($this->poll->getTotalVotes() > 0) {
      return $this->votesPercentage = round($this->votes / $this->poll->getTotalVotes() * 100);
    }

    return 0;
  }

  public function setVotesPercentage($votesPercentage)
  {
    $this->votesPercentage = $votesPercentage;

    return $this;
  }

  /**
   * @return Collection|Statistic[]
   */
  public function getStatistics(): Collection
  {
      return $this->statistics;
  }

  public function addStatistic(Statistic $statistic): self
  {
      if (!$this->statistics->contains($statistic)) {
          $this->statistics[] = $statistic;
          $statistic->setOpinion($this);
      }

      return $this;
  }

  public function removeStatistic(Statistic $statistic): self
  {
      if ($this->statistics->contains($statistic)) {
          $this->statistics->removeElement($statistic);
          // set the owning side to null (unless already changed)
          if ($statistic->getOpinion() === $this) {
              $statistic->setOpinion(null);
          }
      }

      return $this;
  }
}
