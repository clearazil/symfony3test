<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class LuckyController
{
    /**
     * @Route("/lucky/number/{count}")
     * @return [type] [description]
     */
    public function numberAction($count)
    {
        $numbers = [];

        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }
        
        $numbersList = implode(', ', $numbers);

        return new Response(
            '<html><body>Lucky numbers: ' . $numbersList . '</body></html>'
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