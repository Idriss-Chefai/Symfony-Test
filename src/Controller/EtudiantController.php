<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    #[Route('/etudiants', name: 'liste_etudiant')]
    public function list(EntityManagerInterface $em): Response
    {
        $etudiants = $em->getRepository(Etudiant::class)->findAll();
        return $this->render('etudiant/list.html.twig', ['etudiants' => $etudiants]);
    }

    #[Route('/etudiants/new', name: 'new_etudiant',methods:"GET|POST")]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etudiant);
            $em->flush();

            return $this->redirectToRoute('liste_etudiant');
        }

        return $this->render('etudiant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/etudiant/{id}/edit', name:'edit_etudiant',methods:"GET|POST")]
    public function edit(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('liste_etudiant');
        }

        return $this->render('etudiant/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/etudiant/{id}/delete", name:"etudiant_delete", methods:"POST")]
    public function delete(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiant->getId(), $request->request->get('_token'))) {
            $em->remove($etudiant);
            $em->flush();
        }

        return $this->redirectToRoute('etudiant_list');
    }

}


