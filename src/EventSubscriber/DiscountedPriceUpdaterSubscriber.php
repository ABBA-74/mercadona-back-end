<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Product;
use App\Service\PromotionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DiscountedPriceUpdaterSubscriber implements EventSubscriberInterface
{
    public function __construct(private PromotionManager $promotionManager)
    { }

    public function updatePrice(ViewEvent $event): void
    {
        // Get resource class ex:'App\Entity\Product' for Product / Get Collection
        $entityType = $event->getRequest()->attributes->get('_route_params')['_api_resource_class'];

        // Get the HTTP method being used
        $method = $event->getRequest()->getMethod();

        // dd(!$entityType === 'App\Entity\Product', $method !== Request::METHOD_GET);
        // Check if it's not api resource Product or if the HTTP method is not GET
        if (
            $entityType !== 'App\Entity\Product' || $method !== Request::METHOD_GET
        ) {
            return;
        }
        $this->promotionManager->updateDiscountedPricesForAllProducts();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['updatePrice', EventPriorities::PRE_WRITE],
        ];
    }
}
