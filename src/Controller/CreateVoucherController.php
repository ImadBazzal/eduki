<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Voucher;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateVoucherController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }


    #[Route('/generate', name: 'create_voucher', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $json = $request->getContent();

        try {
            $content = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

            if (empty($content['discount']) || !is_int($content['discount'])) {
                return $this->json([
                    'message' => "Discount must be provided"
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $voucher = new Voucher($content['discount']);

            $this->entityManager->persist($voucher);
            $this->entityManager->flush();

            return $this->json([
                'code' => $voucher->getCode()
            ], Response::HTTP_CREATED);

        } catch (JsonException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
