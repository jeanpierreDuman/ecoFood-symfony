<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\FoodProduct;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Template()
 * Class BasketController
 * @package App\Controller
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index()
    {
        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }

    /**
     * @Route("/food/{id}/basket/choose", name="basket_choose")
     */
    public function choose(Food $food, ProductRepository $productRepository)
    {
        return [
            'food' => $food,
            'products' => $productRepository->findAll()
        ];
    }

    /**
     * @Route("/food/{id}/basket/product/{product}", name="basket_choose_add")
     * @param Food $food
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addInBasket(Food $food, Product $product)
    {
        $foodProduct = new FoodProduct();
        $foodProduct->setFood($food);
        $foodProduct->setProduct($product);
        $em = $this->getDoctrine()->getManager();
        $em->persist($foodProduct);

        $food->addFoodProduct($foodProduct);

        $em->persist($food);
        $em->flush();

        return $this->redirectToRoute('food_show', [
            'id' => $food->getId()
        ]);
    }

    /**
     * @Route("/food/{id}/basket/valid", name="basket_valid")
     * @param Food $food
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function valid(Food $food)
    {
        $food->setStatus(Food::STATUS_PUBLISH);
        $em = $this->getDoctrine()->getManager();
        $em->persist($food);
        $em->flush();

        return $this->redirectToRoute('food');
    }
}
