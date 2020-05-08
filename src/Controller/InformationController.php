<?php

namespace App\Controller;

use App\Entity\Tool;
use App\Service\FileUploader;
use Gedmo\Sluggable\Util\Urlizer;
use App\Form\FinalInformationType;
use App\Repository\ToolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class InformationController extends AbstractController
{
    /**
     * @Route("back-office/{themeSlug}/{categorySlug}/{slug}/information/edit", name="information_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tool $tool): Response
    {
        $form = $this->createForm(FinalInformationType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('information')->get('imageFile')->getData();

            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/upload/informations';
                $fileUploader = new FileUploader($destination);
                $newFileName = $fileUploader->upload($uploadedFile);
                $fileUploader->deleteFile($tool->getInformation()->getPictureName());
                $tool->getInformation()->setPictureName($newFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tool);
            $entityManager->flush();

            return $this->redirectToRoute('tool_index', [
                    'slug' => $tool->getCategory()->getSlug(),
                    'themeSlug' => $tool->getCategory()->getTheme()->getSlug()
                ]);
        }

        return $this->render('information/edit.html.twig', [
            'tool' => $tool,
            'form' => $form->createView(),
        ]);
    }
}
