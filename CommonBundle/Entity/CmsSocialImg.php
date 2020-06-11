<?php

namespace CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialImg
 *
 * @ORM\Table(name="cms_social_img", indexes={@ORM\Index(name="base_id", columns={"base_id"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CmsSocialImgRepository")
 */
class CmsSocialImg
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
     * @ORM\Column(name="base_id", type="integer", nullable=false)
     */
    private $baseId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(name="img_path", type="string", length=255, nullable=false)
     */
    private $imgPath = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="smallint", nullable=false)
     */
    private $width = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="smallint", nullable=false)
     */
    private $height = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="img_order", type="integer", nullable=false)
     */
    private $imgOrder = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="img_type", type="integer", nullable=false)
     */
    private $imgType = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="update_time", type="integer", nullable=false)
     */
    private $updateTime = '0';

    /**
     * @ORM\ManyToOne(targetEntity="CmsSocialBase", inversedBy="cmsSocialImgs")
     * @ORM\JoinColumn(name="base_id", referencedColumnName="id")
     */

    private $cmsSocialBase;

    /**
     * @ORM\OneToMany(targetEntity="CmsSocialImgTagMap", mappedBy="cmsSocialImg")
     */
    private $cmsSocialImgTagMaps;

    public function __construct()
    {
        $this->cmsSocialImgTagMaps = new ArrayCollection();
    }


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
     * Set baseId
     *
     * @param integer $baseId
     *
     * @return CmsSocialImg
     */
    public function setBaseId($baseId)
    {
        $this->baseId = $baseId;

        return $this;
    }

    /**
     * Get baseId
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->baseId;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmsSocialImg
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgPath
     *
     * @param string $imgPath
     *
     * @return CmsSocialImg
     */
    public function setImgPath($imgPath)
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    /**
     * Get imgPath
     *
     * @return string
     */
    public function getImgPath()
    {
        return $this->imgPath;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return CmsSocialImg
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return CmsSocialImg
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set imgOrder
     *
     * @param integer $imgOrder
     *
     * @return CmsSocialImg
     */
    public function setImgOrder($imgOrder)
    {
        $this->imgOrder = $imgOrder;

        return $this;
    }

    /**
     * Get imgOrder
     *
     * @return integer
     */
    public function getImgOrder()
    {
        return $this->imgOrder;
    }

    /**
     * Set imgType
     *
     * @param integer $imgType
     *
     * @return CmsSocialImg
     */
    public function setImgType($imgType)
    {
        $this->imgType = $imgType;

        return $this;
    }

    /**
     * Get imgType
     *
     * @return integer
     */
    public function getImgType()
    {
        return $this->imgType;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsSocialImg
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
     * Set updateTime
     *
     * @param integer $updateTime
     *
     * @return CmsSocialImg
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return integer
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }


    /**
     * Set cmsSocialBase
     *
     * @param \CommonBundle\Entity\CmsSocialBase $cmsSocialBase
     *
     * @return CmsSocialImg
     */
    public function setCmsSocialBase(\CommonBundle\Entity\CmsSocialBase $cmsSocialBase = null)
    {
        $this->cmsSocialBase = $cmsSocialBase;

        return $this;
    }

    /**
     * Get cmsSocialBase
     *
     * @return \CommonBundle\Entity\CmsSocialBase
     */
    public function getCmsSocialBase()
    {
        return $this->cmsSocialBase;
    }

    /**
     * Add cmsSocialImgTagMap
     *
     * @param \CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap
     *
     * @return CmsSocialImg
     */
    public function addCmsSocialImgTagMap(\CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap)
    {
        $this->cmsSocialImgTagMaps[] = $cmsSocialImgTagMap;

        return $this;
    }

    /**
     * Remove cmsSocialImgTagMap
     *
     * @param \CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap
     */
    public function removeCmsSocialImgTagMap(\CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap)
    {
        $this->cmsSocialImgTagMaps->removeElement($cmsSocialImgTagMap);
    }

    /**
     * Get cmsSocialImgTagMaps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCmsSocialImgTagMaps()
    {
        return $this->cmsSocialImgTagMaps;
    }
}
