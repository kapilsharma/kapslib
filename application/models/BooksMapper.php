<?php

class Application_Model_BooksMapper {

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
            $this->setDbTable('Application_Model_DbTable_Books');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Book $book) {
        $data = array(
            'name'   => $book->getName(),
            'isbn' => $book->getIsbn(),
            'price' => $book->getPrice(),
            'cdcount' => $book->getCdcount(),
        );
 
        if (null === ($id = $book->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Book $book) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $book->setId($row->id)
              ->setName($row->name)
              ->setIsbn($row->isbn)
              ->setPrice($row->price)
              ->setCdcount($row->cdcount);
    }
 
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Guestbook();
            $entry->setId($row->id)
                    ->setName($row->name)
                    ->setIsbn($row->isbn)
                    ->setPrice($row->price)
                    ->setCdcount($row->cdcount);
            $entries[] = $entry;
        }
        return $entries;
    }
}