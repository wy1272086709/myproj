<?php
namespace CommonBundle\Repository;

/**
 * author sunrise.wang
 * date: 2019-02-30
 */
use CommonBundle\Entity\CmsUserIdentity;

class CmsUserIdentityRepository extends BaseCmsRepository
{
    protected $tableAlias = "cmsUserIdentity";
    protected $entityClassName = CmsUserIdentity::class;

    /**
     * get user identity list method
     * 获取用户认证信息列表
     * @param int $status
     * @param int $uid
     * @param string $userName
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getUserIdentityList($status = 1, $uid = 0, $userName = '', $page = 1, $pageSize = 10): array
    {
        $map    = [];
        $offset = ((int)$page -1 ) *10;
        $status!=-1 && $map['identityStatus'] = $status;
        $uid && $map['uid'] = $uid;
        $userName && $map['username'] = $userName;
        //var_export($map);
        $res = $this->page(
            $this->entityClassName,
            $map,
            [],
            ['createTime' => 'desc'],
            $offset,
            $pageSize
        );
        return $res;
    }

    /**
     * get user identity list by uids
     * 获取用户认证列表
     * @return array
     * @param $uids array 用户ID集合
     * @return array CmsUserIdentity
     */
    public function getUserIdentityListByUids($uids)
    {
        return $this->select($this->entityClassName, [
            'uid'            => $uids,
            'identityStatus' => 1
        ]);
    }

    /**
     * get user identity info
     * 获取单条用户认证信息
     * @param $id
     */
    public function getOneUserIdentity($id)
    {
        return $this->getOne([ 'id'=> $id ]);
    }

    /**
     * determining whether a user is an authenticated user
     * 判断用户是否是认证用户
     * @param $id
     * @return int
     */
    public function hasUserIdentity($id)
    {
        $em   = $this->getEntityManager();
        $conn = $em->getConnection();
        $sql  = "SELECT 1 FROM  `cms_user_identity` where `uid` = :uid AND `identity_status` = 1";
        $re   = $conn->fetchAll($sql, [ 'uid' => $id ]);
        return $re?1:0;
    }

    /**
     * judge current user is in identity table.
     * 判断用户是否存在认证用户数据表中
     * @param $id
     * @return int
     */
    public function isUserInRepository($id)
    {
        $em   = $this->getEntityManager();
        $conn = $em->getConnection();
        $sql  = "SELECT 1 FROM  `cms_user_identity` where `uid` = :uid ";
        $re   = $conn->fetchAll($sql, [ 'uid' => $id ]);
        return $re?1:0;
    }

    /**
     * add user identity info
     * 添加用户认证信息
     * @param $uid integer 用户ID
     * @param $userName string 用户名
     * @param $identityType integer 用户认证类型
     * @param $identificationDesc string 用户认证信息
     */
    public function addUserIdentity($uid, $userName, $identityType, $identificationDesc)
    {
        $params = [
            'uid'                => $uid,
            'identityType'       => $identityType,
            'username'           => $userName,
            'identificationDesc' => $identificationDesc,
            'createTime'         => time()
        ];
        return $this->add($this->entityClassName, $params);
    }

    /**
     * save user identity info.
     * 修改用户认证信息
     * @param $id
     * @param $identityType
     * @param $identificationDesc
     */
    public function saveUserIdentity($id, $identityType, $identificationDesc)
    {
        return $this->save($this->entityClassName, [ 'id' => $id ], [
            'identityType'       => $identityType,
            'identificationDesc' => $identificationDesc,
        ], 1);
    }

    /**
     * show or hide user identity info.
     * @param $id
     * @param int $identityStatus
     */
    public function showUserIdentity($id, $identityStatus = 1)
    {
        return $this->save($this->entityClassName, [ 'id' => $id ], [
            'identityStatus' => $identityStatus
        ], 1);
    }

    /**
     * del user identity by id
     * @param $id
     * @return bool
     */
    public function delUserIdentity($id)
    {
        return $this->delete($this->entityClassName, [
            'id' => $id
        ]);
    }

    /**
     * get user identity type list method
     * @return mixed
     */
    public function getUserIdentityTypeList()
    {
        $em   = $this->getEntityManager();
        $conn = $em->getConnection();
        $sql  = "SELECT * FROM  `cms_user_identity_type`";
        $re   = $conn->fetchAll($sql);
        foreach ($re as $k => $row)
        {
            $re[$k]['create_time'] = date('Y-m-d H:i:s');
        }
        return $re;
    }

}