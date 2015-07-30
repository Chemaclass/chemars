<?php

namespace Chema\ArsBundle\Entity;

interface VouchersAPIInterface
{
	/**
	 * This method will return all vouchers from our partner in a JSON format
	 *
	 * Our partner will deliver the result of
	 * input1.json on the first call and
	 * input2.json on all other clals
	 *
	 * @return string JSON formatted string
	 */
	public function getVouchers();
}
