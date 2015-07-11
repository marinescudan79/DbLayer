DbLayer - README
============================================================


Name: DbLayer
Version: 1.0
Release date: 2015-07-11
Author: Dan Marinescu

Copyright (c) 2015:
    Dan Marinescu

Description:
    DbLayer is a PHP collection of classes for quering databases in ZendFramework2 on-the-fly without requiring extensions such as models or entities.

Layer Use:

    Get intsnace of a DB Table :

        $tableInstance = $this->getServiceLocator()->get('DbLayerService')->get($tableName);

    Method's provided by the $tableInstance :
        Select :
        $tableInstance->select()->fetchAll();
            Returning all records from a DB Table.

        $tableInstance->select()->fetchOne();
            Returning a single record from a DB Table.

        $tableInstance->select()->fetchPaginated($paginator);
            Returning paginated records from a DB Table ready to use with ZF2 pagination view helper.


        $tableInstance->insert($data);

        $tableInstance->update($data, $where);

        $data = array(
            'TableColumn' => 'TableValue',
            'TableColumn2' => 'TableValue2',
        );

        $tableInstance->delete($where);

        Layer instance methods :

            select($where) accepts an array with WHERE predicate conditions
                $where = array(
                    'id = ?' => $id
                );

            join($join) accepts an array for joining other tables
                $join = array(
                    array(
                        'table_name' => 'JoinedTable',
                        'join_condition' => 'Table.Id = JoinedTable.TableId',
                        'columns' => array('JoinedTableColumn', 'AliasedTableName' => 'JoinedTableColumn2'),
                        'join' => 'left',
                    ),
                    array(
                        'table_name' => 'JoinedTable',
                        'join_condition' => 'Table.Id = TableAliasName.TableId',
                        'columns' => array('JoinedTableAliasNameColumn', 'AliasedTableName' => 'JoinedTableAliasNameColumn2'),
                        'join' => 'left',
                        'alias' => 'TableAliasName'
                    ),
                );

            limit(2) limiting results returned to 2

            order($order) ordering results
                $order = array(
                    'Table.Id' => 'ASC',
                    'JoinedTable.TableId' => 'DESC',
                );

            having($having)

            group($group)


Main Features:

Installation :
    composer require danmarinescu/db-layer

License:
    Copyright (C) 2015  Dan Marinescu

    DbLayer is free software: you can redistribute it and/or modify it
    under the terms of the GNU Lesser General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    DbLayer is distributed in the hope that it will be useful, but
    WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU Lesser General Public License for more details.

============================================================
