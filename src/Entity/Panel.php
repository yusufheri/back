<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PanelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PanelRepository::class)
 * @ApiResource(
 *     itemOperations={ "get", "put", "delete"},
 *     collectionOperations={"get", "post"},
 * )
 */ 
class Panel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"operation","station"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"operation","station"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"operation","station"})
     */
    private $longueur;

    /**
     * @ORM\Column(type="float")
     * @Groups({"operation","station"})
     */
    private $largeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="panels")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Station", inversedBy="panels")
     */
    private $station;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Operation", mappedBy="panel", cascade={"remove"})
     */
    private $operationPanel;

    public function __construct()
    {
        $this->operationPanel = new ArrayCollection();
//        $this->station=new ArrayCollection();
//        $this->user=new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): self
    {
        $this->largeur = $largeur;

        return $this;
    }




    public function getUser():?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getStation():?Station
    {
        return $this->station;
    }

    public function setStation(Station $station): void
    {
        $this->station = $station;
    }

    public function getOperationPanel(): Collection
    {
        return $this->operationPanel;
    }

    public function setOperationPanel(Collection $operationPanel): self
    {
        $this->operationPanel = $operationPanel;

        return $this;
    }

    public function __toString()
    {
        return $this->getDescription();
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operationPanel->contains($operation)) {
            $this->operationPanel[] = $operation;
            $operation->setPanels($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operationPanel->contains($operation)) {
            $this->operationPanel->removeElement($operation);
            // set the owning side to null (unless already changed)
            if ($operation->getPanels() === $this) {
                $operation->setPanels(null);
            }
        }

        return $this;
    }




}
