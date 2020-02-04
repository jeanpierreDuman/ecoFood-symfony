<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Template()
 * Class SubscriptionController
 * @package App\Controller
 */
class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription/{id}", name="subscription_to")
     */
    public function subscriptionTo(User $user)
    {
        $currentUser = $this->getUser();
        $currentUser->getSubscriptions()->add($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentUser);
        $em->flush();

        return $this->redirectToRoute('subscriptions');
    }

    /**
     * @Route("/unsubscription/{id}", name="unsubscription")
     */
    public function unsubscription(User $user)
    {
        $currentUser = $this->getUser();
        $currentUser->removeSubscription($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentUser);
        $em->flush();

        return $this->redirectToRoute('subscriptions');
    }

    /**
     * @Route("/subscriptions", name="subscriptions")
     */
    public function index()
    {
        $subscriptions = $this->getUser()->getSubscriptions();

        return [
            'subscriptions' => $subscriptions
        ];
    }
}
