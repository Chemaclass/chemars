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


        $already_found_vouchers = array();
    	foreach ($json as $j){
            $original_voucher_id = $j->id;

            // File is sorted by newest vouchers first. And it also has the old ones in it.
            // So in order it doesn't get overwritten we have to just persist the first voucher found in the file.
            if (in_array($original_voucher_id, $already_found_vouchers, true)) {
                continue;
            } else {
                $already_found_vouchers[] = $original_voucher_id;
            }

            $voucher = $repo->findOneBy(array('originalVoucherId' => $original_voucher_id));

            if (!$voucher) {
                $voucher = new Voucher();
                $voucher->setOriginalVoucherId($original_voucher_id);
            }
            // TODO: Could also think of making a setter for dateFound and update if it really was updated

            // Override all the values and save again
            $voucher->setShop(parse_url($j->destinationUrl, PHP_URL_HOST));
            $voucher->setCode($j->code);
            $voucher->setValue($j->discount);
            $voucher->setUrl($j->destinationUrl);
            $voucher->setStartDate(new \DateTime($j->startDate));
            $voucher->setExpiryDate(new \DateTime($j->expiryDate));

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
