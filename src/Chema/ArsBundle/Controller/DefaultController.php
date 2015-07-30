<?php

namespace Chema\ArsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chema\ArsBundle\Entity\VouchersAPIInterface;
use Chema\ArsBundle\Entity\Voucher;

class DefaultController extends Controller implements VouchersAPIInterface
{
	/**
	 *
	 * @return string
	 */
	public function getVouchers(){
		$pathJs = $this->get('kernel')->getRootDir() . '/../web/js';

		// query checking if they're datas.
		$total = $this->getDoctrine()
	        ->getRepository('ChemaArsBundle:Voucher')
		    ->getTotal();

		error_log("total: ".$total);

		$pathInput = $pathJs . (($total==0) ? '/input1.json' : '/input2.json');

		return file_get_contents($pathInput);
	}

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    	$vouchers = $this->getDoctrine()
	        ->getRepository('ChemaArsBundle:Voucher')
	        ->findAllOrderedDateFound();

    	return $this->render('ChemaArsBundle:Default:index.html.twig', [
    		'vouchers' => $vouchers
    	]);
    }

    /**
     * @Route("/update-json")
     */
    public function getJsonAction()
    {
    	$dm = $this->getDoctrine()->getManager();
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
    		$dm->persist($voucher);
    	}
    	$dm->flush();
    	return $this->redirect('/app_dev.php');
    }




}
