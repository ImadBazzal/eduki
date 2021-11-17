<?php

namespace App\Controller;

use App\DiscountService;
use App\Repository\VoucherRepository;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplyVoucherController extends AbstractController
{
    public function __construct(
        private VoucherRepository $voucherRepository,
        private DiscountService $discountService
    )
    {
    }

    #[Route('/apply', name: 'apply_voucher', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $json = $request->getContent();

        try {
            $content = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

            if (empty($content['items']) || !is_iterable($content['items'])) {
                return $this->json([
                    'message' => "Array of items must be provided"
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (empty($content['code']) || !is_string($content['code'])) {
                return $this->json([
                    'message' => "Voucher code must be provided"
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $voucher = $this->voucherRepository->findOneByCode($content['code']);

            if ($voucher === null) {
                return $this->json([
                    'message' => "Voucher with code {$content['code']} not found"
                ], Response::HTTP_NOT_FOUND);
            }

            return $this->json([
                'items' => $this->discountService->applyDiscount($content['items'], $voucher->getDiscount()),
                'code'  => $voucher->getCode()
            ]);

        } catch (JsonException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
