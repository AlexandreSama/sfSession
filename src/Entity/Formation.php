<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomFormation = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Session::class)]
    private Collection $posseder;

    public function __construct()
    {
        $this->posseder = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    public function setNomFormation(string $nomFormation): static
    {
        $this->nomFormation = $nomFormation;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getPosseder(): Collection
    {
        return $this->posseder;
    }

    public function addPosseder(Session $posseder): static
    {
        if (!$this->posseder->contains($posseder)) {
            $this->posseder->add($posseder);
            $posseder->setFormation($this);
        }

        return $this;
    }

    public function removePosseder(Session $posseder): static
    {
        if ($this->posseder->removeElement($posseder)) {
            // set the owning side to null (unless already changed)
            if ($posseder->getFormation() === $this) {
                $posseder->setFormation(null);
            }
        }

        return $this;
    }
}
