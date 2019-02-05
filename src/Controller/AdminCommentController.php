<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(PaginationService $pagination, $page)
    {
        $pagination->setEntityClass(Comment::class)
                    //->setRoute('admin_comments_index')
                    ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdminCommentType::class, $comment);
        // ne pas oublier de lui passer le $comment pour qu'il récupère les infos de ce comment

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire n°{$comment->getId()} a bien été modifié."
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Comment $comment
     * @param ObjectManager $manager
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     *
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager) {

        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimé."
        );

        return $this->redirectToRoute('admin_comments_index');

    }
}
