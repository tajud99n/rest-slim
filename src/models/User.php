<?php

    class User 
    {
        private $_db,
                $_table = 'users';

        public function __construct($db)
        {
            $this->_db = $db;
        }

        public function query($sql)
        {
            $this->_db->query($sql);
            return $this->_db->results();
        }

        public function insert($fields)
        {
            return (!$this->_db->insert($this->_table, $fields)) ? false : true;

        }

        public function update($id, $fields)
        {
            return (!$this->_db->update($this->_table, $id, $fields)) ? false : true;            
        }

        public function delete($id)
        {
            return (!$this->_db->delete($this->_table, $id)) ? false : true;            
        }
    }