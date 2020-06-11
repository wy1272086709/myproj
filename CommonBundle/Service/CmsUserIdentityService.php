<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace CommonBundle\Service;
use CommonBundle\Entity\CmsUserIdentity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CmsUserIdentityService extends BaseService
{
    protected $userService;
    protected static $repositoryInstance = null;
    const OFFICIAL_CERTIFICATION      = 1; //机构认证
    const PERSONAL_AUTHENTICATION     = 2; //个人认证
    const INSTITUTIONAL_ACCREDITAIION = 3; //机构认证
    const BRAND_AUTHENTICATION = 4;// 品牌认证
    const HOUSING_OFFICER      = 5;// 房捡官认证
    const IMG_PREFIX             = 'https://img.to8to.com/identitytype/';
    protected static $identityImgArr = [
        self::OFFICIAL_CERTIFICATION  => 'official_certification.png',
        self::PERSONAL_AUTHENTICATION => 'personal_authentication.png',
        self::INSTITUTIONAL_ACCREDITAIION => 'institutional_accreditation.png',
        self::BRAND_AUTHENTICATION => 'brand_authentication.png',
        self::HOUSING_OFFICER      => 'housing_officer.png'
    ];
    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager,
        UserService $userService)
    {
        parent::__construct($container);
        $this->container     = $container;
        $this->userService   = $userService;
        $this->entityManager = $entityManager;
    }

    /**
     * Get the corresponding repository class
     * @return \CommonBundle\Repository\CmsUserIdentityRepository|null|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        if (self::$repositoryInstance == null) {
            self::$repositoryInstance = $this->getEntityManager()->getRepository(CmsUserIdentity::class);
        }

        return self::$repositoryInstance;
    }

    /**
     * pass uids, batch get user info
     * @param $uids
     * @return array
     */
    public function getUserInfo($uids)
    {
        $res = $this->userService->batchGetUserInfo($uids);
        return $res;
    }

    /**
     * add user identity info method
     * @param $uid
     * @param $identityType
     * @param $identificationDesc
     */
    public function addUserIdentity($uid, $identityType, $identificationDesc)
    {
        $res = $this->getUserInfo( [ $uid ]);
        if ($res)
        {
            $userInfo = current($res);
            // if user is not valid or user is not owner
            //输入的用户ID无效
            if (isset($userInfo['authorIdentity']) && $userInfo['authorIdentity'] != 0)
            {
                return false;
            }
            $isExists  = $this->getRepository()->isUserInRepository($uid);
            //当前认证信息已经存在
            // if current identity info is exists
            if ($isExists)
            {
                return 0;
            }
            $userName  = $userInfo['username'];
            return $this->getRepository()->addUserIdentity($uid, $userName, $identityType, $identificationDesc);
        }
        return false;
    }

    /**
     * get user identity detail method
     * @param $id
     * @return array
     */
    public function getUserIdentityDetail($id)
    {
        return $this->getRepository()->getOneUserIdentity($id);
    }

    /**
     * save user indentity info 
     * @param $id
     * @param $identityType
     * @param $identificationDesc
     * @return array
     */
    public function saveUserIdentity($id, $identityType, $identificationDesc)
    {
        return $this->getRepository()->saveUserIdentity($id, $identityType, $identificationDesc);
    }

    /**
     * del user identity by id
     * @param $id
     * @return bool
     */
    public function delUserIdentity($id)
    {
        return $this->getRepository()->delUserIdentity($id);
    }

    /**
     * show or hide user identity info
     * @param $id
     * @param int $status
     * @return mixed
     * @throws \Exception
     */
    public function showUserIdentity($id, $status = 1)
    {
        return $this->getRepository()->showUserIdentity($id, $status);
    }

    /**
     * get user identity list method
     * @param $status
     * @param $uid
     * @param $userName
     * @return array
     */
    public function getUserIdentityList($status, $uid, $userName, $page = 1, $pageSize = 10)
    {
        return $this->getRepository()->getUserIdentityList($status, $uid, $userName, $page, $pageSize);
    }

    /**
     * get user identity type list method
     */
    public function getUserIdentityTypeList()
    {
        return $this->getRepository()->getUserIdentityTypeList();
    }

    /**
     * Pass the parameter ID to determine whether the user is authenticated
     * @param $id
     * @return mixed
     */
    public function getIsIdentity($id)
    {
        $isIdentity = $this->getRepository()->hasUserIdentity($id);
        return $isIdentity;
    }

    /**
     * get identity type option 
     * @param $result array 获取到的分页数据，包含list,totalRows
     * @return array
     */
    public function getListAndOptions($result)
    {
        $options = [
            'identityType'   => [
                [
                    'label' => '官方认证',
                    'value' => 1
                ],
                [
                    'label' => '个人认证',
                    'value' => 2
                ],
                [
                    'label' => '机构认证',
                    'value' => 3
                ],
                [
                    'label' => '品牌认证',
                    'value' => 4
                ],
                [
                    'label' => '房检官认证',
                    'value' => 5
                ],
            ],
            'identityStatus' => [
                [
                    'label' => '显示',
                    'value' => 1
                ],
                [
                    'label' => '隐藏',
                    'value' => 0
                ]
            ]
        ];
        if (!$result['data'])
        {
            return [
                'data'    => [
                    'list'    => [],
                    'options' => $options
                ],
                'allRows' => 0
            ];
        }
        $list = $this->getUserIdentityTypeList();
        $userIdentityTypeMap = [];
        array_map(function ($row) use (&$userIdentityTypeMap) {
            $userIdentityTypeMap[$row['identity_type']] = $row['identity_name'];
        }, $list);
        $statusNameMap = [
            1 => '显示',
            0 => '隐藏'
        ];
        $result['data'] = array_map(function ($row) use ($userIdentityTypeMap, $statusNameMap) {
            $row['createTime'] = date('Y-m-d H:i:s', $row['createTime']);
            $row['identityType'] = isset($userIdentityTypeMap[$row['identityType']]) ? $userIdentityTypeMap[$row['identityType']] : '';
            $row['identityStatusName']   = isset($statusNameMap[$row['identityStatus']]) ? $statusNameMap[$row['identityStatus']]: '';
            return $row;
        }, $result['data']);
        return [
            'data' => [
                'list'   => $result['data'],
                'options'=> $options
            ],
            'allRows' => $result['allRows']
        ];
    }

    /**
     * get current user identity type desc ,use for app
     */
    public function getIdentityTypeDesc()
    {
        $offcialTxt  = '<p style="font-size:15px;color:#666666">土巴兔官方账号。</p>';
        $personalTxt = '<p style="font-size:15px;color:#666666">
Q：哪些人可以申请个人认证？<br/>A：普通个人、个体工作室等用户可以申请进行个人认证。<br/>
</p>
<p style="font-size:15px;color:#666666">
Q：个人认证的作用是什么？<br/>A：认证通过的个人创作者将拥有以下特权：<br/>
特权一：专属身份标识。<br/>
特权二：官方推荐，更多曝光。<br/>
</p>
<p style="font-size:15px;color:#666666">
申请认证请发送邮件至 influencer@corp.to8to.com，我们将通过邮箱联系您。
</p>';
        $institutionalTxt = '<p style="font-size:15px;color:#666666">
Q：哪些机构可以申请机构认证？<br/>
A：杂志，机构自媒体、政府机构、非盈利组织等可申请机构认证，如：腾讯家居、网易家居、知电等。<br/>
</p>
<p style="font-size:15px;color:#666666">
Q：机构认证的作用是什么？<br/>
A：认证通过的机构创作者将拥有以下特权：<br/>
特权一：专属身份标识。<br/>
特权二：文章作者版权认证。<br/>
特权三：官方推荐，更多曝光。<br/>
</p><p style="font-size:15px;color:#666666">
申请认证请发送邮件至 influencer@corp.to8to.com，我们将通过邮箱联系您。
</p>';
        $brandTxt   = '<p style="font-size:15px;color:#666666">Q：什么是品牌认证？<br/>
品牌认证能帮助您的企业提升信誉，使用户轻松辨识，认证后我们还将提供官方营销工具助您精准触达人群。适合企业、公司及其分支机构、旗下品牌等认证，如：宜家、格力、公牛等。
</p>
<p style="font-size:15px;color:#666666">
        Q：品牌认证的作用是什么？<br/>
A：认证通过的品牌创作者将拥有以下特权：<br/>
特权一：专属标识，权威官方背书。<br/>
特权二：多种运营工具，玩转粉丝经济。<br/>
特权三：广告精准高效，助力企业营销。<br/>
</p>
<p style="font-size:15px;color:#666666">
        申请认证请发送邮件至 influencer@corp.to8to.com，我们将通过邮箱联系您。
    </p>';
        $housingTxt = '<p style="font-size:15px;color:#666666">土巴兔质检，专注家装质检服务，同时为您解忧答疑。</p>';
        return [
            self::OFFICIAL_CERTIFICATION  => $offcialTxt,
            self::PERSONAL_AUTHENTICATION => $personalTxt,
            self::INSTITUTIONAL_ACCREDITAIION => $institutionalTxt,
            self::BRAND_AUTHENTICATION        => $brandTxt,
            self::HOUSING_OFFICER             => $housingTxt
        ];
    }

    /**
     * pass uids, get user identity list method
     * 获取用户认证信息列表
     * @param $uids
     * @return array
     */
    public function getUserIdentityListByUids(array $uids)
    {
        $userIdentityList = $this->getRepository()->getUserIdentityListByUids($uids);
        $re = [];
        foreach ($uids as $uid)
        {
            $re[$uid] = [ ];
        }
        foreach ($userIdentityList as $userIdentity)
        {
            if ($userIdentity instanceof CmsUserIdentity)
            {
                $id  = $userIdentity->getId();
                $uid = $userIdentity->getUid();
                $username = $userIdentity->getUsername();
                $desc     = $userIdentity->getIdentificationDesc();
                $type     = $userIdentity->getIdentityType();
                $status     = $userIdentity->getIdentityStatus();
                $createTime = $userIdentity->getCreateTime();
                $re[$uid] = [
                    'id'  => $id,
                    'uid' => $uid,
                    'username'            => $username,
                    'identification_desc' => $desc,
                    'identity_status'     => $status,
                    'identity_type'       => $type,
                    'create_time'         => $createTime,
                    'identity_pic'        => self::IMG_PREFIX. self::$identityImgArr[$type]
                ];
            }
        }
        return $re;
    }

}