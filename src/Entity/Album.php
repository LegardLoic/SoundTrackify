<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name", message="Donnée déjà existante dans la base de données.")
 * @UniqueEntity("mainThemeUrl", message="Donnée déjà existante dans la base de données.")
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $poster;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Videogame::class, inversedBy="albums")
     */
    private $videogame;

    /**
     * @ORM\OneToMany(targetEntity=Music::class, mappedBy="album", cascade={"remove"})
     */
    private $musics;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     * @Assert\Url
     * @Assert\NotBlank
     */
    private $mainThemeUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->musics = new ArrayCollection();
    }

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

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Fonction for set updatedAt automatically
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getVideogame(): ?Videogame
    {
        return $this->videogame;
    }

    public function setVideogame(?Videogame $videogame): self
    {
        $this->videogame = $videogame;

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusics(): Collection
    {
        return $this->musics;
    }

    public function addMusic(Music $music): self
    {
        if (!$this->musics->contains($music)) {
            $this->musics[] = $music;
            $music->setAlbum($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->musics->removeElement($music)) {
            // set the owning side to null (unless already changed)
            if ($music->getAlbum() === $this) {
                $music->setAlbum(null);
            }
        }

        return $this;
    }

    public function getMainThemeUrl(): ?string
    {
        return $this->mainThemeUrl;
    }

    public function setMainThemeUrl(?string $mainThemeUrl): self
    {
        $this->mainThemeUrl = $mainThemeUrl;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }


}
