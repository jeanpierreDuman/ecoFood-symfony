<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\User;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use App\Services\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Template()
 * Class FoodController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class FoodController extends AbstractController
{
    /**
     * @param FoodRepository $foodRepository
     * @Route("/food", name="food")
     * @return array
     */
    public function index(FoodRepository $foodRepository)
    {
        return [
            'foods' => $foodRepository->findAll()
        ];
    }

    /**
     * @Route("/food/add", name="food_add")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return array
     */
    public function add(Request $request, FileUploader $fileUploader)
    {
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $picture = $request->files->get('food')['picture'];
            $food = $fileUploader->upload($food, $picture);
            $food->setUser($this->getUser());
            $food->setStatus(Food::STATUS_BASKET);
            $em = $this->getDoctrine()->getManager();
            $em->persist($food);
            $em->flush();

            return $this->redirectToRoute('food');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/food/{id}", name="food_show")
     * @param Food $food
     * @return array
     */
    public function show(Food $food)
    {
        $user = $this->getUser();

        if(!$food->getViews()->contains($user)) {
            $food->addView($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($food);
            $em->flush();
        }

        return [
            'food' => $food,
            'products' => $food->getFoodProducts()
        ];
    }

    /**
     * @Route("/food/user/{id}", name="food_user_list")
     */
    public function showUserFood(User $user, FoodRepository $foodRepository)
    {
        $foods = $foodRepository->findByUser($user);

        return [
            'foods' => $foods,
            'user' => $user
        ];
    }

    /**
     * @param Food $food
     * @param FileUploader $fileUploader
     * @Route("/food/{id}/delete", name="food_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Food $food, FileUploader $fileUploader)
    {
        if($fileUploader->pathExist($food->getPicture())) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($fileUploader->getFoodDirectory($food->getPicture()));
        }

        if(count($food->getViews()) > 0) {
            $food->getViews()->clear();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($food);
        $em->flush();

        return $this->redirectToRoute('food');
    }
}
