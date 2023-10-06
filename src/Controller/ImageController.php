<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/images/{filename}', name: 'app_get_image')]
    public function getImage(string $filename): Response
    {
        $imagePath = $this->getParameter('kernel.project_dir').'/public/build/images/'.$filename;
        
        if (!file_exists($imagePath)) {
            return new Response('Image not found.', 404);
        }

        return new BinaryFileResponse($imagePath);
    }
}
