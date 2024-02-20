<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Nom d'utilisateur déjà pris")
 */
class User implements UserInterface
{
		const ROLE_GITHUB = 'ROLE_GITHUB';
		const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

		const DEFAULT_ROLES = [self::ROLE_GITHUB];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(unique=true, type="string", length=180)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @Assert\Length(max=4096)
     * add length max 4096 and delete ORM\Column(type="string")
     * to not save in database
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Statistic::class, mappedBy="user")
     */
    private $statistics;

    public function __construct(array $githubData = [])
    {
				$this->roles = self::DEFAULT_ROLES;
        if ($githubData) {
            $this->username = $githubData['login'];
        }
        $this->statistics = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    // modify $plainPassword to $password
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

		public function __toString(): string
		{
			return $this->username;
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
          $statistic->setUser($this);
      }

      return $this;
  }

  public function removeStatistic(Statistic $statistic): self
  {
      if ($this->statistics->contains($statistic)) {
          $this->statistics->removeElement($statistic);
          // set the owning side to null (unless already changed)
          if ($statistic->getUser() === $this) {
              $statistic->setUser(null);
          }
      }

      return $this;
  }
}
