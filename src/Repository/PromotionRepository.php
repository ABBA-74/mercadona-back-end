<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

/**
 * @extends ServiceEntityRepository<Promotion>
 *
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    public function findBestPromotion(?Collection $promotions): ?Promotion
    {
        $maxDiscountPercentage = 0;
        $bestPromotion = null;
        $now = new \DateTime();

        foreach ($promotions as $promotion) {
            if ($promotion->getStartDate() <= $now && $promotion->getEndDate() >= $now) {
                $discountPercentage = $promotion->getDiscountPercentage();

                if ($discountPercentage > $maxDiscountPercentage) {
                    $maxDiscountPercentage = $discountPercentage;
                    $bestPromotion = $promotion;
                }
            }
        }
        return $bestPromotion;
    }
}
