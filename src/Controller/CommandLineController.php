<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandLineController extends AbstractController
{
    #[Route('/command/line', name: 'app_command_line')]
    public function index(): Response
    {
        return $this->render('command_line/index.html.twig', [
            'controller_name' => 'CommandLineController',
        ]);
    }
}
