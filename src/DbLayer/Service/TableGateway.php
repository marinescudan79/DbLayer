<?php
/**
 * @Author: Dan Marinescu
 * @Email: dan.m@my1hr.com
 * @Date:   2015-07-11 04:20:10
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-07-12 03:11:26
 */
namespace DbLayer\Service;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql;
use Zend\Db\ResultSet;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect as PaginatorIterator;

class TableGateway extends AbstractTableGateway
{
    public $lastInsertValue;
    public $table;
    public $adapter;
    public $select;
    public $insert;
    public $update;
    public $delete;


    public function __construct($table, Adapter $adapter)
    {
        $this->table      = $table;
        $this->adapter    = $adapter;
        $this->featureSet = new Feature\FeatureSet();
        $this->sql        = new Sql\Sql($this->adapter, $this->table);
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    }

    public function __toString()
    {
        try {
            return $this->getSql()->getSqlStringForSqlObject($this->select);
        } catch (\Exception $e) {
            return $this->getSql()->buildSqlString($this->select);
        }
    }

    public function select($where = null)
    {
        $this->select = $this->getSql()->select();

        if (!empty($where)) {
            $this->select->where($where);
        }

        return $this;
    }

    public function insert($set)
    {
        $this->insert = $this->getSql()->insert();
        $this->insert->values($set);

        $this->beginTransaction();
        try {
            $this->insertWith($this->insert);
            $insertId = $this->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }

        return $insertId;
    }

    public function update($set, $where = null)
    {
        $this->update = $this->getSql()->update();

        if (!empty($where)) {
            $this->update->where($where);
        } else {
            throw new Exception("Error Processing Request, where condition cannot be empty", 1);
        }
        $this->update->set($set);

        $this->beginTransaction();
        try {
            $this->updateWith($this->update);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }

        return 1;
    }

    public function delete($where)
    {
        $this->delete = $this->getSql()->delete();

        if (!empty($where)) {
            $this->delete->where($where);
        } else {
            throw new Exception("Error Processing Request, where condition cannot be empty", 1);
        }

        $this->beginTransaction();
        try {
            $this->deleteWith($this->delete);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }

        return 1;
    }

    public function join($join = null)
    {
        if (!empty($join)) {
            foreach ($join as $table) {
                $this->select->join(
                    array(
                    (isset($table['alias']) ? $table['alias'] : $table['table_name']) => $table['table_name']),
                    $table['join_condition'],
                    isset($table['columns']) ? $table['columns'] : array(),
                    (isset($table['join']) ? $table['join'] : null
                    )
                );
            }
        }
        return $this;
    }

    public function group($group)
    {
        $this->select->group($group);
        return $this;
    }

    public function having($having)
    {
        $this->select->having($having);
        return $this;
    }

    public function order($order)
    {
        $this->select->order($order);
        return $this;
    }

    public function limit($limit)
    {
        $this->select->limit($limit);
        return $this;
    }

    public function offset($offset)
    {
        $this->select->offset($offset);
        return $this;
    }

    public function findAll()
    {
        return $this->selectWith($this->select);
    }

    public function findOne()
    {
        $this->select->limit(1);

        return $this->selectWith($this->select);
    }

    public function findPaginated($paginator = null)
    {
        $result = new Paginator(new PaginatorIterator($this->select, $this->adapter));
        $result->setCurrentPageNumber(isset($paginator['page']) ? $paginator['page'] : 0)
            ->setItemCountPerPage((isset($paginator['countPerPage']) ? $paginator['countPerPage'] : 20))
            ->setPageRange((isset($paginator['pageRange']) ? $paginator['pageRange'] : 10));
        return $result;
    }

    public function columns($columns = null)
    {
        $this->select->columns($columns);
        return $this;
    }

    public function distinct()
    {
        $this->select->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
        return $this;
    }

    // Begin Transaction
    public function beginTransaction()
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }

    // Start Transaction
    public function commit()
    {
        $this->getAdapter()->getDriver()->getConnection()->commit();
    }

    // Rollback Transaction
    public function rollback()
    {
        $this->getAdapter()->getDriver()->getConnection()->rollback();
    }
}
