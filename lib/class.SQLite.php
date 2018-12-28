<?php
class SQLiteDB extends SQLite3
{
    protected $dbFile = '../data/mpdata.sqlite';

    public function __construct() {
        // $this->open($this->dbFile);
    }
}