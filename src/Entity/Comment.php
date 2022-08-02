<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private ?float $rank = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $downVotes = null;

    #[ORM\Column(nullable: true)]
    private ?int $upVotes = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRank(): ?float
    {
        return $this->rank;
    }

    public function setRank(float $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDownVotes(): ?int
    {
        return $this->downVotes;
    }

    public function setDownVotes(?int $downVotes): self
    {
        $this->downVotes = $downVotes;

        return $this;
    }

    public function getUpVotes(): ?int
    {
        return $this->upVotes;
    }

    public function setUpVotes(?int $upVotes): self
    {
        $this->upVotes = $upVotes;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public static function getStars(string $raiting): array
    {
        $stars = [];

        if ($raiting === 0) {
            for ($i = 0; $i < 5; $i++) {
                $stars[] = '<i class="fa-regular fa-star"></i>';
            }
        } elseif ($raiting > 0 && $raiting <= 0.5){
            $stars = [
                '<i class="fas fa-star-half-stroke"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 0.5 && $raiting <= 1) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 1 && $raiting <= 1.5) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star-half-stroke"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 1.5 && $raiting <= 2) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 2 && $raiting <= 2.5) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star-half-stroke"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 2.5 && $raiting <= 3) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 3 && $raiting <= 3.5) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star-half-stroke"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 3.5 && $raiting <= 4) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fa-regular fa-star"></i>',
            ];
        } elseif ($raiting > 4 && $raiting <= 4.5) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star-half-stroke"></i>',
            ];
        } elseif ($raiting > 4.5 && $raiting <= 5) {
            $stars = [
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
                '<i class="fas fa-star"></i>',
            ];
        }

        return $stars;
    }
}
