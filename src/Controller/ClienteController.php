<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController
{
    #[Route('/clientes', name: 'cliente_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $clientes = $em->getRepository(Cliente::class)->findAll();

        return $this->render('cliente/index.html.twig', [
            'clientes' => $clientes,
        ]);
    }

    #[Route('/cliente/novo', name: 'cliente_novo')]
    public function novo(Request $request, EntityManagerInterface $em): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cliente);
            $em->flush();

            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/novo.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
