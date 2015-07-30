<?php

namespace Chema\ArsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voucher
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Chema\ArsBundle\Entity\VoucherRepository")
 */
class Voucher
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="shop", type="string", length=255)
     */
    private $shop;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiryDate", type="datetime")
     */
    private $expiryDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="foundDate", type="datetime")
     */
    private $foundDate;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set shop
     *
     * @param string $shop
     *
     * @return Voucher
     */
    public function setShop($shop)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return string
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Voucher
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Voucher
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Voucher
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Voucher
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return Voucher
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set foundDate
     *
     * @param \DateTime $foundDate
     *
     * @return Voucher
     */
    public function setFoundDate($foundDate)
    {
        $this->foundDate = $foundDate;

        return $this;
    }

    /**
     * Get foundDate
     *
     * @return \DateTime
     */
    public function getFoundDate()
    {
        return $this->foundDate;
    }
}
