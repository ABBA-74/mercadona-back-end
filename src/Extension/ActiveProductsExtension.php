<?php

namespace App\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;

class ActiveProductsExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator, 
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void
    {
        if(Product::class !== $resourceClass || $operation->getName() !== '_api_/products/active_get_collection') {
            return;
        }
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->where(sprintf('%s.isActive = :isActive', $rootAlias))
            ->setParameter('isActive', 1);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []): void
    {
        if(Product::class !== $resourceClass || $operation->getName() !== '_api_/products/active/{id}_get') {
            return;
        }
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andwhere(sprintf('%s.id = :id', $rootAlias))
            ->setParameter('id', $identifiers['id'])
            ->andWhere(sprintf('%s.isActive = :isActive', $rootAlias))
            ->setParameter('isActive', 1);
    }
}
