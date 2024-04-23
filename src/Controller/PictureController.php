<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Form\SearchType;
use App\Form\PictureType;
use App\Repository\PicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/galerie", name: "picture.")]
#[IsGranted("IS_AUTHENTICATED_FULLY")]
class PictureController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PicturesRepository $repo, Request $req): Response
    {
        $user = $this->getUser();
        $pictures = $repo->getPicByUser($user);

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($req);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $id = $data['title']->getId();
            return $this->redirectToRoute('picture.edit', [
                'id' => $id
            ]);
        }

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
            'searchForm' => $searchForm
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(PicturesRepository $repo, Request $req): Response
    {
        $user = $this->getUser();
        $pictures = $repo->getPicByUser($user);

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($req);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $id = $data['title']->getId();
            return $this->redirectToRoute('picture.edit', [
                'id' => $id
            ]);
        }

        return $this->render('picture/liste.html.twig', [
            'pictures' => $pictures,
            'searchForm' => $searchForm
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

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS])]
    public function edit(Pictures $picture, Request $req, EntityManagerInterface $em)
    {

        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Image modifier');
            return $this->redirectToRoute('picture.list');
        }

        return $this->render('picture/edit.html.twig', [
            'form' => $form,
            'pic' => $picture
        ]);
    }
}
