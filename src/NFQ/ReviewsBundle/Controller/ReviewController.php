<?php

namespace NFQ\ReviewsBundle\Controller;

use NFQ\ReviewsBundle\Entity\Review;
use NFQ\ReviewsBundle\Entity\Thanks;
use NFQ\ReviewsBundle\Form\ReviewType;
use NFQ\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReviewController extends Controller
{
    /**
     * @return Response
     */
    public function reviewListAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $thank = $em->getRepository('NFQReviewsBundle:Thanks')->findOneByHelper($currentUser);

        if (!$thank) {
            return $this->render('NFQReviewsBundle:Review:reviewsProfile.html.twig', ['number' => 0, 'reviews' => null]);
        } else {
            $list = $currentUser->getGReviews()->toArray();
        }

        return $this->render('NFQReviewsBundle:Review:reviewsProfile.html.twig', ['number' => $thank->getNumber(), 'reviews' => $list]);
    }

    public function createReviewAction(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(new ReviewType(), $review, array('method' => 'POST'));

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $currentUser = $this->getUser();
            $review->setHelper($currentUser);
            $review->setHelpGetter($currentUser);

            $em = $this->getDoctrine()->getManager();

            if ($form->get('thank')->getData()) {
                $thank = $em->getRepository('NFQReviewsBundle:Thanks')->findOneByHelper($currentUser);

                if (!$thank) {
                    $thank = new Thanks();
                    $thank->setHelper($currentUser);
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
