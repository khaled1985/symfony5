<?php

namespace App\Entity;

use App\Repository\EtudiantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EtudiantsRepository::class)
 */
class Etudiants
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="integer")

     */
    private $cin;

    /**
     * @ORM\OneToMany(targetEntity=EtudiantMatieres::class, mappedBy="etudiant")
     */
    private $CinEtudiants;

    public function __construct()
    {
        $this->CinEtudiants = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * @return Collection|EtudiantMatieres[]
     */
    public function getCinEtudiants(): Collection
    {
        return $this->CinEtudiants;
    }

    public function addCinEtudiant(EtudiantMatieres $cinEtudiant): self
    {
        if (!$this->CinEtudiants->contains($cinEtudiant)) {
            $this->CinEtudiants[] = $cinEtudiant;
            $cinEtudiant->setEtudiant($this);
        }

        return $this;
    }

    public function removeCinEtudiant(EtudiantMatieres $cinEtudiant): self
    {
        if ($this->CinEtudiants->removeElement($cinEtudiant)) {
            // set the owning side to null (unless already changed)
            if ($cinEtudiant->getEtudiant() === $this) {
                $cinEtudiant->setEtudiant(null);
            }
        }

        return $this;
    }
}
