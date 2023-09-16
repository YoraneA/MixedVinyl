<?php

namespace App\Controller;

use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MixController extends AbstractController
{
    #[Route('mix/new')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $genres = ['pop', 'rock'] ;

        $mix = (new VinylMix())
            ->setTitle('Do you Remember... Phil Collins?!')
            ->setDescription('A pure mix of drummers turned singers!')
            ->setGenre($genres[array_rand($genres)])
            ->setTrackCount(rand(5, 20))
            ->setVotes(rand(-50, 50));

        $entityManager->persist($mix);
        $entityManager->flush();

        return new Response(sprintf(
            "Mix %d is %d tracks of pure 80's heaven",
            $mix->getId(),
            $mix->getTrackCount()
        ));
    }

    #[Route('/mix/{slug}', name: 'app_mix_show')]
    public function show(VinylMix $mix = null)
    {
        if (!$mix) {
            throw $this->createNotFoundException('Mix not found');
        }

        return $this->render(
            'mix/show.html.twig',
            [
                'mix' => $mix
            ]
        );
    }

    #[Route('/mix/{id}/vote', name: 'app_mix_vote', methods: ['POST'])]
    public function vote(VinylMix $mix, EntityManagerInterface $entityManager, Request $request): Response
    {
        $direction = $request->request->get('direction', 'up');

        if ($direction === 'up') {
            $mix->upVote();
        }
        else {
            $mix->downVote();
        }

        $entityManager->flush();

        $this->addFlash('success', 'Vote counted !');

        return $this->redirectToRoute('app_mix_show', [
            'slug' => $mix->getSlug()
        ]);
    }
}