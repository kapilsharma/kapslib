<?php

class Application_Model_AuthorMapper {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Author');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Book $author) {
        $data = array(
            'first_name'   => $author->getFirstName(),
            'middle_name'   => $author->getMiddleName(),
            'last_name'   => $author->getLastName(),
            'popular_name'   => $author->getPopularName(),
            'email' => $author->getEmail(),
            'address' => $author->getAddress(),
        );
 
        if (null === ($id = $author->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Book $author) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $author->setId($row->id)
                ->setFirstName($row->first_name)
                ->setMiddleName($row->middle_name)
                ->setLastName($row->last_name)
                ->setPopularName($row->popular_name)
                ->setEmail($row->email)
                ->setAddress($row->address);
    }
 
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Guestbook();
            $entry->setId($row->id)
                    ->setFirstName($row->first_name)
                    ->setMiddleName($row->middle_name)
                    ->setLastName($row->last_name)
                    ->setPopularName($row->popular_name)
                    ->setEmail($row->email)
                    ->setAddress($row->address);
            $entries[] = $entry;
        }
        return $entries;
    }

}