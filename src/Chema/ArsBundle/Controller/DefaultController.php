<?php

namespace Chema\ArsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chema\ArsBundle\Entity\VouchersAPIInterface;
use Chema\ArsBundle\Entity\Voucher;
use Symfony\Component\BrowserKit\Response;

class DefaultController extends Controller implements VouchersAPIInterface
{
	/**
	 * This method will return all vouchers from our partner in a JSON format.
	 *
	 * @return string
	 */
	public function getVouchers(){
		$pathJs = $this->get('kernel')->getRootDir() . '/../web/js';

		/*
		 * Query for to check if we have data. If we have take the 2st file.
		 * If we don't have data take the 1st file.
		 */
		$total = $this->getDoctrine()
	        ->getRepository('ChemaArsBundle:Voucher')
		    ->getTotal();

		$pathInput = $pathJs . (($total==0) ? '/input1.json' : '/input2.json');

		return file_get_contents($pathInput);
	}

    /**
     * Index route.
     *
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    	/*
    	 * Get all vouchers order by dateFound
    	 */
    	$vouchers = $this->getDoctrine()
	        ->getRepository('ChemaArsBundle:Voucher')
	        ->findAllOrderedDateFound();

    	return $this->render('ChemaArsBundle:Default:index.html.twig', [
    		'vouchers' => $vouchers
    	]);
    }

    /**
     * Update the DDBB with the datas from the JSON file.
     *
     * @Route("/update-json")
     */
    public function getJsonAction()
    {
    	$dm = $this->getDoctrine()->getManager();
    	$repo =	$this->getDoctrine()->getRepository('ChemaArsBundle:Voucher');

    	$jsondata = $this->getVouchers();
    	$json = json_decode($jsondata);
    	foreach ($json as $j){
    		/*
    		 * I took as a shop the domain from the destinationUrl
    		 */
    		$voucher = new Voucher();
    		$voucher->setShop(parse_url($j->destinationUrl, PHP_URL_HOST));
    		$voucher->setCode($j->code);
    		$voucher->setValue($j->discount);
    		$voucher->setUrl($j->destinationUrl);
    		$voucher->setStartDate(new \DateTime($j->startDate));
    		$voucher->setExpiryDate(new \DateTime($j->expiryDate));
    		/*
    		 * We need to see if the current voucher exists or it's a new one.
    		 * If exists just return an update his dateFound (is preUpdate func),
    		 * if not exists just return the same (new one).
    		 *
    		 * After that just call dm->persist(voucher) for update (if was exists before)
    		 * or create (if it's a new one).
    		 */
    		$voucher = $repo->getVoucherIfexists($voucher);
    		$dm->persist($voucher);
    	}
    	$dm->flush();
    	return $this->redirect('/');
    }

    /**
     * Remove a voucher.
     *
     * @Route("/remove-voucher")
     */
    public function removeVoucherAction(Request $request)
    {
    	$voucherId = $request->request->get('voucherId');
    	$dm = $this->getDoctrine()->getManager();
    	$repo =	$this->getDoctrine()->getRepository('ChemaArsBundle:Voucher');
    	$voucher = $repo->find($voucherId);
    	$dm->remove($voucher);
    	$dm->flush();
    	return new JsonResponse(['code' => 200]);
    }

}
