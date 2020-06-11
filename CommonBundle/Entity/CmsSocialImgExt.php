<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialImgExt
 *
 * @ORM\Table(name="cms_social_img_ext", indexes={@ORM\Index(name="base_id", columns={"base_id"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CmsSocialImgExtRepository")
 */
class CmsSocialImgExt
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
     * @var integer
     *
     * @ORM\Column(name="img_num", type="integer", nullable=false)
     */
    private $imgNum = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255, nullable=false)
     */
    private $cover = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="cover_width", type="smallint", nullable=false)
     */
    private $coverWidth = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="cover_height", type="smallint", nullable=false)
     */
    private $coverHeight = '0';

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
     * @ORM\OneToOne(targetEntity="CmsSocialBase", inversedBy="cmsSocialImgExt")
     * @ORM\JoinColumn(name="base_id", referencedColumnName="id")
     */

    private $cmsSocialBase;



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
     * @return CmsSocialImgExt
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
     * Set imgNum
     *
     * @param integer $imgNum
     *
     * @return CmsSocialImgExt
     */
    public function setImgNum($imgNum)
    {
        $this->imgNum = $imgNum;

        return $this;
    }

    /**
     * Get imgNum
     *
     * @return integer
     */
    public function getImgNum()
    {
        return $this->imgNum;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return CmsSocialImgExt
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set coverWidth
     *
     * @param integer $coverWidth
     *
     * @return CmsSocialImgExt
     */
    public function setCoverWidth($coverWidth)
    {
        $this->coverWidth = $coverWidth;

        return $this;
    }

    /**
     * Get coverWidth
     *
     * @return integer
     */
    public function getCoverWidth()
    {
        return $this->coverWidth;
    }

    /**
     * Set coverHeight
     *
     * @param integer $coverHeight
     *
     * @return CmsSocialImgExt
     */
    public function setCoverHeight($coverHeight)
    {
        $this->coverHeight = $coverHeight;

        return $this;
    }

    /**
     * Get coverHeight
     *
     * @return integer
     */
    public function getCoverHeight()
    {
        return $this->coverHeight;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsSocialImgExt
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
     * @return CmsSocialImgExt
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
     * @return CmsSocialImgExt
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
}
