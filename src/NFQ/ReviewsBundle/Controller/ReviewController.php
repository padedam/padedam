<?php

namespace NFQ\ReviewsBundle\Controller;

use NFQ\ReviewsBundle\Entity\Review;
use NFQ\ReviewsBundle\Entity\Thanks;
use NFQ\ReviewsBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ReviewController extends Controller
{
    public function createReviewAction(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(new ReviewType(), $review, array('method'=>'POST'));

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $currentUser = $this->getUser();
            $review->setHelper($currentUser);
            $review->setHelpGetter($currentUser);

            $em = $this->getDoctrine()->getManager();

            if($form->get('thank')->getData()){
                $thank = $em->getRepository('NFQReviewsBundle:Thanks')->findOneByHelper($currentUser);

                if(!$thank){
                    $thank = new Thanks();
                    $thank->setHelper($currentUser);
                }

                if($form->get('reviewMessage')->getData()){
                    $thank->addReview($review->getId());
                }
                $thank->incrementNumber();
                $em->persist($thank);
            }

            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('nfq_assistance_request_submitted');
        }

        return $this->render('NFQReviewsBundle:Review:createReview.html.twig', array('form' => $form->createView()));
    }
}
