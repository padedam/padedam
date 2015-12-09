<?php

namespace NFQ\ReviewsBundle\Controller;

use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use NFQ\ReviewsBundle\Entity\Review;
use NFQ\ReviewsBundle\Entity\Thanks;
use NFQ\ReviewsBundle\Form\ReviewType;
use NFQ\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        $thank = $em->getRepository('NFQReviewsBundle:Thanks')->findOneByUser($currentUser);

        if (!$thank) {
            return $this->render('NFQReviewsBundle:Review:reviewsProfile.html.twig', ['number' => 0, 'reviews' => null]);
        } else {
            $list = $currentUser->getGReviews()->toArray();
        }

        return $this->render('NFQReviewsBundle:Review:reviewsProfile.html.twig', ['number' => $thank->getNumber(), 'reviews' => $list]);
    }

    /**
     * @param Request $request
     * @param $arid
     * @return RedirectResponse|Response
     */
    public function createReviewAction(Request $request, $arid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();

        $assistanceRequest = $em->getRepository('NFQAssistanceBundle:AssistanceRequest')->find($arid);

        if ($assistanceRequest->getOwner() != $currentUser ||
            $assistanceRequest->getHelper() == $currentUser ||
            $assistanceRequest->getStatus() != AssistanceRequest::STATUS_TAKEN
        ) {
            throw new Exception('problems');
        }

        $review = new Review();
        $review->setAssistanceRequest($assistanceRequest);

        $form = $this->createForm(new ReviewType(), $review, array('method' => 'POST'));

        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isValid()) {

            $review->setHelper($assistanceRequest->getHelper());
            $review->setHelpGetter($currentUser);
            $review->setAssistanceRequest($assistanceRequest);

            $review->setDate(new \DateTime('now'));

            $assistanceRequest->setStatus(AssistanceRequest::STATUS_DONE);
            $em->persist($assistanceRequest);

            if ($form->get('thank')->getData()) {
                $thank = $em->getRepository('NFQReviewsBundle:Thanks')->findOneByUser($assistanceRequest->getHelper());

                if ($thank == null) {
                    $thank = new Thanks();
                    $thank->setHelper($assistanceRequest->getHelper());
                }

                $thank->incrementNumber();
                $em->persist($thank);
            }

            if (!$form->get('reviewMessage')->getData()) {
                $this->get('session')->getFlashBag()->add('success', 'assistance_done');
            } else {
                $em->persist($review);

                $this->get('session')->getFlashBag()->add('success', 'review_added');
            }

            $em->flush();

            return $this->redirectToRoute('nfq_assistance_request_list');
        }

        return $this->render('NFQReviewsBundle:Review:createReview.html.twig', array('form' => $form->createView()));
    }
}
