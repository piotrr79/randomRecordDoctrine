<?php

namespace Websolutio\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Websolutio\DemoBundle\Entity\Banners;

/**
 * Banners controller.
 *
 */
class BannersController extends Controller
{

    /**
     * Select random banner. Doctrine does not support 'random' query. Solution is to set  floating offset with separate query
     *
     */
    public function banneroneAction(Request $request)
    {
		// get parameters from request		  		
		$request = $this->getRequest()->getLocale();
		
        $em = $this->getDoctrine()->getManager();
        $count = $em->createQuery("SELECT COUNT (b) FROM WebsolutioDemoBundle:Banners b WHERE b.language = ?1 AND b.type = 1 AND b.position = 1 AND b.expires_at > CURRENT_DATE() AND b.homepage = 1 AND b.publish = 1 "); 
        $count->setParameter(1, $request);
        $ofcount = $count->getSingleScalarResult();
        $offset = rand(0, $ofcount - 1);
        $query = $em->createQuery("SELECT b FROM WebsolutioDemoBundle:Banners b WHERE b.language = ?1 AND b.type = 1 AND b.position = 1 AND b.expires_at > CURRENT_DATE() AND b.homepage = 1 AND b.publish = 1 ");  
        $query->setParameter(1, $request);   
        $query->setFirstResult($offset);
        $query->setMaxResults(1);
        $entities = $query->getResult();

		if (!$entities) {
			return $this->render('WebsolutioDemoBundle:Banners:bannerdefault.html.twig');
		} else {
			return $this->render('WebsolutioDemoBundle:Banners:bannerone.html.twig', array(
				'entities' => $entities,
			));	
		}
    }

}
