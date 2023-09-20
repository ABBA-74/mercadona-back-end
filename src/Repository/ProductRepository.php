<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->em =  $this->getEntityManager();
    }

    /**
    * Saves a product by persisting it to the database.
    *
    * @param Product $product The product to save.
    */
    public function save(Product $product): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    /**
    * Updates the discount prices of a product.
    *
    * @param Product $product The product to update.
    * @param int|null $discountPercentage The discount percentage to apply (can be null).
    * @param int|null $discountedPrice The reduced price (can be null).
    */
    public function updateDiscountPrice(
        Product $product,
        ?float $discountPercentage,
        ?float $discountedPrice
    ): void
    {
        // Update the product's discount properties
        $product->setDiscountedPrice($discountedPrice);
        $product->setCurrentPromotionPercentage($discountPercentage);

        // Flush to persist the changes to the database
        $this->em->flush();
    }
}
