<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

	#[Route('/', name: "home")]
	public function displayHome() {
		return $this->render('home.html.twig');
	}

}