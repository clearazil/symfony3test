<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
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

        $html = $this->render(
            'lucky/number.html.twig',
            ['luckyNumberList' => $numbersList]
        );

        return new Response($html);
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