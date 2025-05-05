<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

	#[Route('/', name: "home")]
	public function displayHome() {
		return $this->render('home.html.twig');
	}


	
	#[Route('/404', name: "404")]
	public function display404() {

		// on ne peut pas utiliser la fonction render
		// car elle envoie un status 200 (on veut un 404)
		// donc je dois créer la réponse un peu plus "à la main"
		
		// je créé le HTML issu du twig
		$html = $this->renderView('404.html.twig');

		// je retourne une réponse 404, incluant le HTML
		return new Response($html, 404);
	}

}