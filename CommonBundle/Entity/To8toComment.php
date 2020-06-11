<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * To8toComment
 *
 * @ORM\Table(name="to8to_comment", indexes={@ORM\Index(name="oid", columns={"oid", "puttime", "comtype"}), @ORM\Index(name="hostid", columns={"hostid"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\To8toCommentRepository")
 */
class To8toComment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="comid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $comid;

    /**
     * @var integer
     *
     * @ORM\Column(name="oid", type="integer", nullable=false)
     */
    private $oid;

    /**
     * @var integer
     *
     * @ORM\Column(name="hostid", type="integer", nullable=false)
     */
    private $hostid;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=false)
     */
    private $ip = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="puttime", type="integer", nullable=false)
     */
    private $puttime = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="comtype", type="integer", nullable=false)
     */
    private $comtype;

    /**
     * @var integer
     *
     * @ORM\Column(name="smalltype", type="integer", nullable=false)
     */
    private $smalltype;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=200, nullable=false)
     */
    private $url = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=60, nullable=false)
     */
    private $title = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="ishidden", type="integer", nullable=false)
     */
    private $ishidden = '0';

    /**
     * @ORM\ManyToOne(targetEntity="To8toAnswer", inversedBy="to8toAnswer", cascade={"remove"})
     * @ORM\JoinColumn(name="oid", referencedColumnName="anid")
     */
    private $to8toAnswer;

    /**
     * Get comid
     *
     * @return integer
     */
    public function getComid()
    {
        return $this->comid;
    }

    /**
     * Set oid
     *
     * @param integer $oid
     *
     * @return To8toComment
     */
    public function setOid($oid)
    {
        $this->oid = $oid;

        return $this;
    }

    /**
     * Get oid
     *
     * @return integer
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set hostid
     *
     * @param integer $hostid
     *
     * @return To8toComment
     */
    public function setHostid($hostid)
    {
        $this->hostid = $hostid;

        return $this;
    }

    /**
     * Get hostid
     *
     * @return integer
     */
    public function getHostid()
    {
        return $this->hostid;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     *
     * @return To8toComment
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
     * Set ip
     *
     * @param string $ip
     *
     * @return To8toComment
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set puttime
     *
     * @param integer $puttime
     *
     * @return To8toComment
     */
    public function setPuttime($puttime)
    {
        $this->puttime = $puttime;

        return $this;
    }

    /**
     * Get puttime
     *
     * @return integer
     */
    public function getPuttime()
    {
        return $this->puttime;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return To8toComment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set comtype
     *
     * @param integer $comtype
     *
     * @return To8toComment
     */
    public function setComtype($comtype)
    {
        $this->comtype = $comtype;

        return $this;
    }

    /**
     * Get comtype
     *
     * @return integer
     */
    public function getComtype()
    {
        return $this->comtype;
    }

    /**
     * Set smalltype
     *
     * @param integer $smalltype
     *
     * @return To8toComment
     */
    public function setSmalltype($smalltype)
    {
        $this->smalltype = $smalltype;

        return $this;
    }

    /**
     * Get smalltype
     *
     * @return integer
     */
    public function getSmalltype()
    {
        return $this->smalltype;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return To8toComment
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return To8toComment
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
     * Set title
     *
     * @param string $title
     *
     * @return To8toComment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ishidden
     *
     * @param integer $ishidden
     *
     * @return To8toComment
     */
    public function setIshidden($ishidden)
    {
        $this->ishidden = $ishidden;

        return $this;
    }

    /**
     * Get ishidden
     *
     * @return integer
     */
    public function getIshidden()
    {
        return $this->ishidden;
    }

    /**
     * Set to8toAnswer
     *
     * @param \CommonBundle\Entity\To8toAnswer $to8toAnswer
     *
     * @return To8toComment
     */
    public function setTo8toAnswer(\CommonBundle\Entity\To8toAnswer $to8toAnswer = null)
    {
        $this->to8toAnswer = $to8toAnswer;

        return $this;
    }

    /**
     * Get to8toAnswer
     *
     * @return \CommonBundle\Entity\To8toAnswer
     */
    public function getTo8toAnswer()
    {
        return $this->to8toAnswer;
    }
}
