<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\PostRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/service', name: 'service.')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        $postsCount = [];

        foreach ($services as $service) {
            $postsCount[$service->getId()] = $service->getPosts()->count();
        }

        return $this->render('service/index.html.twig', [
            'services' => $services,
            'postsCount' => $postsCount
        ]);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Service $service, Request $request, EntityManagerInterface $em) : Response{
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            return $this->redirectToRoute('service.index');
        }

        return $this->render('service/edit.html.twig', [
            'form' => $form
        ]);
    }
}
