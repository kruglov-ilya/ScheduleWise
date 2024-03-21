<?php

namespace App\Controller\API;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ServiceByCategoryController extends AbstractController
{
    public function __invoke(Request $request, ServiceRepository $repository, $id): JsonResponse
    {
        $services = $repository->findBy(['category' => $id]);

        return $this->json($services);
    }
}