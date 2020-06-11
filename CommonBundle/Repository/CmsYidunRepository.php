<?php

namespace CommonBundle\Repository;

use CommonBundle\Entity\CmsYidun;

class CmsYidunRepository extends BaseCmsRepository
{
    protected $tableAlias = 'cmsYidun';
    protected $entityClassName = CmsYidun::class;

    public function getSingleYidunInfo($moduleCode, $objectId) {
    	if(empty($moduleCode) || empty($objectId))
    	{
    		return false;
    	}
    	return $this->selectOne($this->entityClassName, compact('moduleCode', 'objectId'));
    }

    /**
     * batch get yidun list
     * @param $moduleCode
     * @param array $objectIds
     * @return array
     */
    public function getBatchYidunList($moduleCode, array $objectIds) {
    	if(empty($objectIds) || !is_array($objectIds)) return [];
        $queryBuilder = $this->createQueryBuilder($this->tableAlias)
            ->select($this->fields('*'))
            ->where($this->tableAlias . ".moduleCode = '" . $moduleCode . "'")
            ->andWhere($this->tableAlias . '.objectId in (:objectIds)')
            ->setParameter('objectIds', $objectIds)
            ->orderBy($this->tableAlias . '.id', 'desc');
        $result = $queryBuilder->getQuery()->getArrayResult();
        return $result && is_array($result) ? $result : [];
    }

    /**
     * add yidun record by params
     * @param array $params
     * @return mixed
     */
    public function addYidunRecord(array $params) {
    	return $this->add($this->entityClassName, $params);
    }

    /**
     * edit yidun record info
     * @param $id
     * @param array $params
     * @return bool
     */
    public function editYidunRecord($id, array $params) {
    	if(empty($id))
    	{
    		return false;
    	}
		return $this->save($this->entityClassName, ['id'=>$id], $params);
    }

    /**
     * get batch lastest yidun list info
     * @param $moduleCode
     * @param array $objectIds
     * @param string $fields
     * @return array
     */
    public function getLatestBatchYidunList($moduleCode, array $objectIds, $fields = '*') {
        if(empty($objectIds) || !is_array($objectIds)) return [];
        $queryBuilder = $this->createQueryBuilder($this->tableAlias)
            ->select($this->fields($fields))
            ->where($this->tableAlias . ".moduleCode = '" . $moduleCode . "'")
            ->andWhere($this->tableAlias . '.objectId in (:objectIds)')
            ->setParameter('objectIds', $objectIds)
            ->orderBy($this->tableAlias . '.checkTime', 'desc');
        $result = $queryBuilder->getQuery()->getArrayResult();
        $result = $result && is_array($result) ? $result : [];
        $res    = $latestYidunList = [];
        foreach ($objectIds as $objectId)
        {
            $latestYidunList[$objectId] = [];
        }
        foreach ($result as $row)
        {
            $res[$moduleCode][$row['objectId']][] = $row;
        }
        foreach ($res as $moduleCode => $row)
        {
            foreach ($row as $objectId => $item)
            {
                $latestYidunList[$objectId] = current($item);
            }
        }
        return $latestYidunList;
    }

}