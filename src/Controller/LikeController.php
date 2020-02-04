<?php

namespace App\Controller;

use App\Entity\Food;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Template()
 * Class LikeController
 * @package App\Controller
 */
class LikeController extends AbstractController
{
    /**
     * @Route("/like_to/{id}", name="like_to")
     */
    public function likeTo(Food $food)
    {
        $user = $this->getUser();

        $this->getUser()->getLikes()->add($food);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('likes');
    }

    /**
     * @Route("/dislike/{id}", name="dislike")
     */
    public function dislike(Food $food)
    {
        $user = $this->getUser();

        $this->getUser()->removeLike($food);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('likes');
    }

    /**
     * @Route("/likes", name="likes")
     */
    public function index()
    {
        $foods = $this->getUser()->getLikes();

        return [
            'foods' => $foods
        ];
    }
}
