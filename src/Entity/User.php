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
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $disponibility;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="users")
     */
    private $competences;

    /**
     * @ORM\OneToMany(targetEntity=Experiences::class, mappedBy="user")
     */
    private $experience;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="user")
     */
    private $doc;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->experience = new ArrayCollection();
        $this->doc = new ArrayCollection();
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
        $roles[] = 'ROLE_USER';

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDisponibility(): ?bool
    {
        return $this->disponibility;
    }

    public function setDisponibility(bool $disponibility): self
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    /**
     * @return Collection|Experiences[]
     */
    public function getExperience(): Collection
    {
        return $this->experience;
    }

    public function addExperience(Experiences $experience): self
    {
        if (!$this->experience->contains($experience)) {
            $this->experience[] = $experience;
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experiences $experience): self
    {
        if ($this->experience->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Documents[]
     */
    public function getDoc(): Collection
    {
        return $this->doc;
    }

    public function addDoc(Documents $doc): self
    {
        if (!$this->doc->contains($doc)) {
            $this->doc[] = $doc;
            $doc->setUser($this);
        }

        return $this;
    }

    public function removeDoc(Documents $doc): self
    {
        if ($this->doc->removeElement($doc)) {
            // set the owning side to null (unless already changed)
            if ($doc->getUser() === $this) {
                $doc->setUser(null);
            }
        }

        return $this;
    }

}
