<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PollRepository")
 */
class Poll
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
   * @ORM\Column(type="string", length=180)
   */
  private $category;

  /**
   * @ORM\Column(type="boolean")
   */
  private $published;

  /**
   * @ORM\Column(type="boolean")
   */
  private $closed;

  /**
   * @ORM\Column(type="datetime")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="bigint", nullable=false)
   */
  private $totalVotes = 0;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Opinion", mappedBy="poll", cascade={"persist", "remove"})
   */
  private $opinions;

  /**
   * @ORM\OneToMany(targetEntity=Statistic::class, mappedBy="poll")
   */
  private $statistics;


  public function __construct()
  {
    $this->opinions = new ArrayCollection();
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

  public function getCategory()
  {
    return $this->category;
  }

  public function setCategory($category)
  {
    $this->category = $category;

    return $this;
  }

  public function getPublished()
  {
    return $this->published;
  }

  public function setPublished($published)
  {
    $this->published = $published;

    return $this;
  }

  public function getClosed()
  {
    return $this->closed;
  }

  public function setClosed($closed)
  {
    $this->closed = $closed;

    return $this;
  }

  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getTotalVotes()
  {
    if ($this->totalVotes) {
      return $this->totalVotes;
    }

    $this->totalVotes = 0;

    foreach ($this->opinions as $opinion) {
      $this->totalVotes += $opinion->getVotes();
    }

    return $this->totalVotes;
  }

  public function setTotalVotes($totalVotes)
  {
    $this->totalVotes = $totalVotes;

    return $this;
  }

  /**
   * @return Collection|Opinion[]
   */
  public function getOpinions(): Collection
  {
    return $this->opinions;
  }

  public function addOpinion(Opinion $opinion): self
  {
    if (!$this->opinions->contains($opinion)) {
      $this->opinions[] = $opinion;
      $opinion->setPoll($this);
    }

    return $this;
  }

  public function removeOpinion(Opinion $opinion): self
  {
    if ($this->opinions->contains($opinion)) {
      $this->opinions->removeElement($opinion);
      // set the owning side to null (unless already changed)
      if ($opinion->getPoll() === $this) {
        $opinion->setPoll(null);
      }
    }

    return $this;
  }

	public function __toString(): string
               	{
               		return $this->name;
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
            $statistic->setPoll($this);
        }

        return $this;
    }

    public function removeStatistic(Statistic $statistic): self
    {
        if ($this->statistics->contains($statistic)) {
            $this->statistics->removeElement($statistic);
            // set the owning side to null (unless already changed)
            if ($statistic->getPoll() === $this) {
                $statistic->setPoll(null);
            }
        }

        return $this;
    }
}
