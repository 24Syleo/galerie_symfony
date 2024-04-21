<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Form\PictureType;
use App\Repository\PicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/galerie", name: "picture.")]
#[IsGranted("IS_AUTHENTICATED_FULLY")]
class PictureController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PicturesRepository $repo): Response
    {
        $pictures = $repo->findAll();

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $req, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $picture = new Pictures();

        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $picture->setUser($user);
            $em->persist($picture);
            $em->flush();
            $this->addFlash('success', 'Image ajouter à la galerie');
            return $this->redirectToRoute('picture.index');
        }

        return $this->render('picture/create.html.twig', [
            'form' => $form
        ]);
    }
}
