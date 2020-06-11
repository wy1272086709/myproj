<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUserIdentityType
 *
 * @ORM\Table(name="cms_user_identity_type")
 * @ORM\Entity
 */
class CmsUserIdentityType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identity_show_name", type="string", length=20, nullable=false)
     */
    private $identityShowName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="identity_type", type="integer", nullable=false)
     */
    private $identityType = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="identity_pic_url", type="string", length=255, nullable=false)
     */
    private $identityPicUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="identity_desc", type="string", length=255, nullable=false)
     */
    private $identityDesc = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="identity_name", type="string", length=20, nullable=false)
     */
    private $identityName = '';



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
     * Set identityShowName
     *
     * @param string $identityShowName
     *
     * @return CmsUserIdentityType
     */
    public function setIdentityShowName($identityShowName)
    {
        $this->identityShowName = $identityShowName;
    
        return $this;
    }

    /**
     * Get identityShowName
     *
     * @return string
     */
    public function getIdentityShowName()
    {
        return $this->identityShowName;
    }

    /**
     * Set identityType
     *
     * @param boolean $identityType
     *
     * @return CmsUserIdentityType
     */
    public function setIdentityType($identityType)
    {
        $this->identityType = $identityType;
    
        return $this;
    }

    /**
     * Get identityType
     *
     * @return boolean
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }

    /**
     * Set identityPicUrl
     *
     * @param string $identityPicUrl
     *
     * @return CmsUserIdentityType
     */
    public function setIdentityPicUrl($identityPicUrl)
    {
        $this->identityPicUrl = $identityPicUrl;
    
        return $this;
    }

    /**
     * Get identityPicUrl
     *
     * @return string
     */
    public function getIdentityPicUrl()
    {
        return $this->identityPicUrl;
    }

    /**
     * Set identityDesc
     *
     * @param string $identityDesc
     *
     * @return CmsUserIdentityType
     */
    public function setIdentityDesc($identityDesc)
    {
        $this->identityDesc = $identityDesc;
    
        return $this;
    }

    /**
     * Get identityDesc
     *
     * @return string
     */
    public function getIdentityDesc()
    {
        return $this->identityDesc;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsUserIdentityType
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    
        return $this;
    }

    /**
     * Get createTime
     *
     * @return integer
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set identityName
     *
     * @param string $identityName
     *
     * @return CmsUserIdentityType
     */
    public function setIdentityName($identityName)
    {
        $this->identityName = $identityName;
    
        return $this;
    }

    /**
     * Get identityName
     *
     * @return string
     */
    public function getIdentityName()
    {
        return $this->identityName;
    }
}
