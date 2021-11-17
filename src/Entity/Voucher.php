<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Immutable;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\String\ByteString;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 */
class Voucher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private string $code;

    /**
     * @ORM\Column(type="integer")
     */
    private int $discount;

    public function __construct(int $discount)
    {
        $this->code = ByteString::fromRandom(8)->toUnicodeString()->toString();
        $this->discount = $discount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }
}
