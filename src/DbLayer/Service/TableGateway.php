<?php
/**
 * @Author: Dan Marinescu
 * @Email: dan.m@my1hr.com
 * @Date:   2015-07-11 04:20:10
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-07-11 21:54:54
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
    public $query;


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
        return $this->getSql()->getSqlStringForSqlObject($this->query);
    }

    public function select($where = null)
    {
        $this->query = $this->getSql()->select();

        if (!empty($where)) {
            $this->query->where($where);
        }

        return $this;
    }

    public function insert($set)
    {
        $this->query = $this->getSql()->insert();
        $this->query->values($set);

        $this->beginTransaction();
        try {
            parent::insertWith($this->query);
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
        $this->query = $this->getSql()->update();

        if (!empty($where)) {
            $this->query->where($where);
        } else {
            throw new Exception("Error Processing Request, where condition cannot be empty", 1);
        }
        $this->query->set($set);

        $this->beginTransaction();
        try {
            parent::updateWith($this->query);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }

        return true;
    }

    public function delete($where)
    {
        $this->query = $this->getSql()->delete();

        if (!empty($where)) {
            $this->query->where($where);
        } else {
            throw new Exception("Error Processing Request, where condition cannot be empty", 1);
        }

        $this->beginTransaction();
        try {
            parent::deleteWith($this->query);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }

        return true;
    }

    public function join($join = null)
    {
        if (!empty($join)) {
            foreach ($join as $table) {
                $this->query->join(
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
        $this->query->group($group);
        return $this;
    }

    public function having($having)
    {
        $this->query->having($having);
        return $this;
    }

    public function order($order)
    {
        $this->query->order($order);
        return $this;
    }

    public function limit($limit)
    {
        $this->query->limit($limit);
        return $this;
    }

    public function offset($offset)
    {
        $this->query->offset($offset);
        return $this;
    }

    public function findAll()
    {
        return parent::selectWith($this->query);
    }

    public function findOne()
    {
        $this->query->limit(1);

        return parent::selectWith($this->query);
    }

    public function findPaginated($paginator = null)
    {
        $result = new Paginator(new PaginatorIterator($this->query, $this->adapter));
        $result->setCurrentPageNumber(isset($paginator['page']) ? $paginator['page'] : 0)
            ->setItemCountPerPage((isset($paginator['countPerPage']) ? $paginator['countPerPage'] : 20))
            ->setPageRange((isset($paginator['pageRange']) ? $paginator['pageRange'] : 10));
        return $result;
    }

    public function columns($columns = null)
    {
        $this->query->columns($columns);
        return $this;
    }

    public function distinct()
    {
        $this->query->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
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
