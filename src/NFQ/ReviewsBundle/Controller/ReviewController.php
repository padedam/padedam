<?php

namespace NFQ\ReviewsBundle\Controller;

use NFQ\ReviewsBundle\Entity\Review;
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

            if($form->get('thumbUp')->getData()){
                $currentUser->incrementThumbUps();
            }
            else{
                $currentUser->incrementThumbDowns();
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('nfq_assistance_request_submitted');
        }

        return $this->render('NFQReviewsBundle:Review:createReview.html.twig', array('form' => $form->createView()));
    }
}
