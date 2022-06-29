<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lucky")
 */
class LuckyController
{
    /**
    * @Route("/number")
    */
    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        return new JsonResponse(['lucky_number' => $number]);
    }

    /**
     * @Route("/day")
     * @return JsonResponse
     */
    public function day(): JsonResponse
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat', 'Sun'];
        $day = random_int(0, 6);
        return new JsonResponse(['lucky_day' => $days[$day]]);
    }
}
