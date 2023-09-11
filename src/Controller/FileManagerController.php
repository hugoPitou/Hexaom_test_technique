<?php

namespace App\Controller;

use App\Entity\FileManager;
use App\Form\FileManagerType;
use App\Repository\FileManagerRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/app/file_manager')]
class FileManagerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_file_manager')]
    public function index(FileManagerRepository $fileManagerRepository,Request $request, SluggerInterface $slugger): Response
    {

        $fileManager = new FileManager();
        $form = $this->createForm(FileManagerType::class, $fileManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where file are stored
                try {
                    $file->move(
                        $this->getParameter('upload_file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

           
                $fileManager->setFillname($newFilename);
                $this->entityManager->persist($fileManager);
                $this->entityManager->flush();
            }

            return $this->redirectToRoute('app_file_manager', [], Response::HTTP_SEE_OTHER);
        }

        $files = $fileManagerRepository->findAll();

        return $this->render('file_manager/index.html.twig', [
            'form' => $form,
            'files' => $files,
            'nbFiles' => count($files),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_file_delete')]
    public function deleteFile(string $id)
    {
        $fileManagerRepository = $this->entityManager->getRepository(FileManager::class);
    
        // Récupérez l'entité FileManager correspondant à l'ID
        $fileManager = $fileManagerRepository->find($id);
    
        if (!$fileManager) {
            // L'entité FileManager avec cet ID n'existe pas
            return new Response("Le fichier n'existe pas.", 404);
        }
    
        // Récupérez le nom du fichier à partir de l'entité FileManager
        $filename = $fileManager->getFillname();
    
        // Spécifiez le répertoire où se trouvent les fichiers à supprimer 
        $uploadDirectory = $this->getParameter('upload_file_directory');
    
        // Vérifiez si le fichier existe avant de le supprimer
        $filePath = $uploadDirectory . '/' . $filename;
        if (file_exists($filePath)) {
            try {
                // Supprimez le fichier
                unlink($filePath);
    
                // Supprimez également l'entité FileManager de la base de données
                $this->entityManager->remove($fileManager);
                $this->entityManager->flush();
    
                // Redirigez ou renvoyez une réponse appropriée après la suppression
                return $this->redirectToRoute('app_file_manager');
            } catch (\Exception $e) {
                // Gérez les erreurs en cas de problème lors de la suppression du fichier
                return new Response("Une erreur s'est produite lors de la suppression du fichier : " . $e->getMessage(), 500);
            }
        } else {
            // Le fichier n'existe pas, vous pouvez rediriger ou renvoyer une réponse appropriée
            return new Response("Le fichier n'existe pas.", 404);
        }
    }

}
