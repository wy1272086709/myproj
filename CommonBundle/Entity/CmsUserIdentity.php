<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUserIdentity
 *
 * @ORM\Table(name="cms_user_identity", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_cms_user_identity_uid", columns={"uid"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CmsUserIdentityRepository")
 */
class CmsUserIdentity
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
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=32, nullable=false)
     */
    private $username = '';

    /**
     * @var string
     *
     * @ORM\Column(name="identification_desc", type="string", length=255, nullable=false)
     */
    private $identificationDesc = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="identity_type", type="integer", nullable=false)
     */
    private $identityType = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="identity_status", type="integer", nullable=false)
     */
    private $identityStatus = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime = '0';



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
     * Set uid
     *
     * @param integer $uid
     *
     * @return CmsUserIdentity
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    
        return $this;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return CmsUserIdentity
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set identificationDesc
     *
     * @param string $identificationDesc
     *
     * @return CmsUserIdentity
     */
    public function setIdentificationDesc($identificationDesc)
    {
        $this->identificationDesc = $identificationDesc;
    
        return $this;
    }

    /**
     * Get identificationDesc
     *
     * @return string
     */
    public function getIdentificationDesc()
    {
        return $this->identificationDesc;
    }

    /**
     * Set identityType
     *
     * @param integer $identityType
     *
     * @return CmsUserIdentity
     */
    public function setIdentityType($identityType)
    {
        $this->identityType = $identityType;
    
        return $this;
    }

    /**
     * Get identityType
     *
     * @return integer
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }

    /**
     * Set identityStatus
     *
     * @param boolean $identityStatus
     *
     * @return CmsUserIdentity
     */
    public function setIdentityStatus($identityStatus)
    {
        $this->identityStatus = $identityStatus;
    
        return $this;
    }

    /**
     * Get identityStatus
     *
     * @return boolean
     */
    public function getIdentityStatus()
    {
        return $this->identityStatus;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsUserIdentity
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
}
