<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    // on cree un attribut raisonSociale qui est un string null, il peut faire maximum 100 de longueur
    #[ORM\Column(length: 100)]
    private ?string $raisonSociale = null;
    // on cree l'attibut d'une date qui est d'abord null
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 100)]
    private ?string $adresse = null;

    #[ORM\Column(length: 20)]
    private ?string $cp = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    /**
     * @var Collection<int, Employe>
     */
    // on cree la clé étrangère Entreprise 1-n Employé == Employé 1-1 Entreprise, oprhanRemoval
    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'entreprise', orphanRemoval: true)]
    #[ORM\OrderBy(["nom" => "ASC"])] // on odronne dans l'ordre croissant le nom de l'employé
    private Collection $employes;

    //permet de déclarer le constructeur pour la classe
    public function __construct()
    {
        $this->employes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }
    public function setRaisonSociale(string $raisonSociale): static
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }
    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }
    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }
    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
            $employe->setEntreprise($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getEntreprise() === $this) {
                $employe->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getAdresseComplete():string { 
        return $this->adresse .', '. $this->cp .'-'. $this->ville;
    }
    public function getDateCreationFr ():string { 
        return $this->dateCreation->format("D d M Y à H : i");
    }

    public function __toString(){
        return $this->raisonSociale;
    }
}
