<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Wish;
use App\Form\WishType;
use Knp\Component\Pager\PaginatorInterface;

/**
     * @Route("/wish")
     */

class WishController extends AbstractController
{
    /**
     * @Route("/", name="wish-liste")
     */
    public function liste(WishRepository $wishRepo, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $wishRepo->listeWishCategories();

        $listWish = $paginator->paginate(
            $data,						
            $request->query->getInt('page',1),		
            5						
        );

        return $this->render('wish/wishliste.html.twig', [
            'controller_name' => 'WishController',
            'listWish' => $listWish,
        ]);
    }

    /**
     * @Route("/{id}", name="wish-details", requirements={"id"="\d+"})
     */
    public function details(int $id, WishRepository $wishRepo): Response
    {
        $wish = $wishRepo->wishDetailsCategory($id);

        return $this->render('wish/wishdetails.html.twig', [
            'controller_name' => 'WishController',
            'wish' => $wish,
        ]);
    }

        /**
     * @Route("/create", name="wish-creation")
     */
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $wish = new Wish();

        $wishForm=$this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){

            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime());
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success','Nouveau wish créé.');
	
            return $this->redirectToRoute('wish-details',['id'=> $wish->getId()]);
            }

        return $this->render('wish/wishcrea.html.twig', [
            'controller_name' => 'WishController',
            'wishForm'=>$wishForm-> createView(),
        ]);
    }

     /**
     * @Route("/delete/{id}", name="wish-delete", 
     * requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete(int $id, EntityManagerInterface $em, Request $request, WishRepository $wishRepo): Response
    {
        $wish = $wishRepo->find($id);

        if(!$wish){
            throw $this->createNotFoundException("Wish inconnu ou déjà supprimer.");
        }

        if($this->isCsrfTokenValid("supprimerWish".$wish->getId(), $request->get('_token'))){
            $em->remove($wish);
            $em->flush();
            $this->addFlash('success','Le wish a bien été supprimé.');

            return $this->redirectToRoute('home');
        }

        $this->addFlash('danger','Le wish n\'a pas pu être supprimé.');

        return $this->redirectToRoute('wish-details', ['id'=> $wish->getId()]);
        
    }

    /**
     * @Route("/modifier/{id}", name="wish-modif", requirements={"id"="\d+"})
     */
    public function update(int $id, WishRepository $wishRepo, Request $request, EntityManagerInterface $em): Response
    {
        $wish = $wishRepo->find($id);
        $wishForm=$this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){

            $em->persist($wish);          
            $em->flush();

            $this->addFlash('success','Wish modifié.');
	
            return $this->redirectToRoute('home');
            }

        return $this->render('wish/wishmodifier.html.twig', [
            'controller_name' => 'WishController',
            'wish' => $wish,
            'wishForm'=>$wishForm-> createView(),
        ]);
    }
}
