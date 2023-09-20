<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;

class PromotionManager
{
    public function __construct(
        private ProductRepository $productRepository,
        private PromotionRepository $promotionRepository
    ) {
    }

    public function updateDiscountedPricesForAllProducts(): void
    {
        $products = $this->productRepository->findAll();

        foreach ($products as $product) {
            $promotions = $product->getPromotions();
            $bestPromotion = $this->promotionRepository->findBestPromotion($promotions);
            
            $originalPrice = $product->getOriginalPrice();
            $discountPercentage = $bestPromotion?->getDiscountPercentage();
            $discountedPrice = $this->calculateDiscountPrice($discountPercentage, $originalPrice);

            $this->productRepository->updateDiscountPrice($product, $discountPercentage, $discountedPrice);
        }
    }

    private function calculateDiscountPrice(?int $discountAmount, ?float $originalPrice): ?float
    {
        if ($originalPrice === null || $discountAmount === null) {
            return null;
        }

        return round($originalPrice - ($originalPrice * ($discountAmount / 100)), 2);
    }
}