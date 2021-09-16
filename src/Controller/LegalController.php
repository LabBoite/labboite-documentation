<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Repository\ThemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalController extends AbstractController
{
    /**
     * Legal page - front office
     * 
     * @Route("/legal", name="legal")
     */
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('legal/public/legal.html.twig', [
            'themes' => $themeRepository->findAll()
        ]);
    }

    /**
     * Legal page - front office
     * 
     * @Route("/legal", name="admin_legal")
     */
    public function adminIndex(ThemeRepository $themeRepository, Theme $theme): Response
    {
        return $this->render('legal/admin/legal.html.twig', [
            'themes' => $themeRepository->findAll()
        ]);
    }
}