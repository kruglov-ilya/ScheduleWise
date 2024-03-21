<?php

namespace App\Controller\API;

use App\Repository\TimeslotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TimeslotByServiceController extends AbstractController
{
    public function __invoke(Request $request, TimeslotRepository $repository, int $id): JsonResponse
    {
        $timeslots = $repository->findTimesForService($id);

        return $this->json($timeslots);
    }
}