<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class LuckyController
{
    /**
     * @Route("/lucky/number")
     * @return [type] [description]
     */
    public function numberAction()
    {
        $number = rand(0, 100);

        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }

    /**
     * @Route("/api/lucky/number")
     * @return [type] [description]
     */
    public function apiNumberAction()
    {
        $data = [
            'lucky_number' => rand(0, 100),
        ];

        return new JsonResponse($data);
    }
}