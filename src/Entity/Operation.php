<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 * @ApiResource(
 *     itemOperations={ "get", "put","delete"},
 *     collectionOperations={"get", "post"},
 *      normalizationContext={
 *          "groups"={"operation"}
 *     }
 * )
 */
class Operation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"operation"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user","operation"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="operationUser")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Station", inversedBy="operationStation")
     * @Groups({"operation"})
     */
    private $station;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Panel", inversedBy="operationPanel")
     * @Groups({"operation"})
     */
    private $panel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="operation", cascade={"persist", "remove"})
     * @Groups({"post"})
     * @Groups({"operation"})
     */
    private $images;





    public function __construct()
    {
//        $this->panel = new ArrayCollection();
        $this->images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }


    public function getUser():?User
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }


    public function getStation() : ?Station
    {
        return $this->station;
    }

    /**
     * @param mixed $station
     */
    public function setStation($station): void
    {
        $this->station = $station;
    }



    public function getPanel(): ?Panel
    {
        return $this->panel;
    }

    public function setPanel(Panel $panel): void
    {
        $this->panel = $panel;
    }

    public function addPanel(Panel $panel): self
    {
        if (!$this->panel->contains($panel)) {
            $this->panel[] = $panel;
            $panel->setOperationPanel($this);
        }

        return $this;
    }

    public function removePanel(Panel $panel): self
    {
        if ($this->panel->contains($panel)) {
            $this->panel->removeElement($panel);
            // set the owning side to null (unless already changed)
            if ($panel->getOperationPanel() === $this) {
                $panel->setOperationPanel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setOperation($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getOperation() === $this) {
                $image->setOperation(null);
            }
        }

        return $this;
    }

//    /**
//     * @return Collection|Image[]
//     */
//    public function getImagesAfter(): Collection
//    {
//        return $this->imagesAfter;
//    }
//
//    public function addImageAfter(Image $imageAfter): self
//    {
//        if (!$this->images->contains($imageAfter)) {
//            $this->images[] = $imageAfter;
//            $imageAfter->setType("1");
//            $imageAfter->setOperation($this);
//        }
//
//        return $this;
//    }

//    public function removeImageAfter(Image $imageAfter): self
//    {
//        if ($this->imagesAfter->contains($imageAfter)) {
//            $this->imagesAfter->removeElement($imageAfter);
//            // set the owning side to null (unless already changed)
//            if ($imageAfter->getOperation() === $this) {
//                $imageAfter->setOperation(null);
//            }
//        }
//
//        return $this;
//    }


}
