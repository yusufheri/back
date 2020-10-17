<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ApiResource(
 *     itemOperations={ "get", "put"},
 *     collectionOperations={"get", "post"}
 * )
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Operation", inversedBy="images", cascade={"persist"})
     */
    private $operation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type ;

    /**
     * @ORM\Column(type="json")
     */
    private $localisation = [];




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getType()
    {
        return $this->type;
    }


    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getLocalisation(): array
    {
        return $this->localisation;
    }


    public function setLocalisation(array $localisation): void
    {
        $this->localisation = $localisation;
    }


}