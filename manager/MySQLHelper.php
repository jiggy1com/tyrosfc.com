<?php

class MySQLHelper  {

    public $mysql;

    function __construct()
    {
        $this->mysql = new MySQL();
    }

    public function getError(){
        return $this->mysql->getError();
    }

    // ROSTER

    public function getRoster(){
        $query = "select * from roster order by lastname";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getActiveRoster(){
        $query = "select * from roster where isActive = 1 order by lastname";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getInActiveRoster(){
        $query = "select * from roster where isActive = 0 order by lastname";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getRosterById($id){
        $query = "select * from roster where id = '$id'";
        return $this->mysql->setQuery($query)->runRead();
    }

    // ATTENDANCE

    public function getAttendance(){
        $query = "select * from attendance order by id";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getAttendanceByGameUID($uid){
        $query = "select * from attendance where uid = '$uid' order by id";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function deleteFromAttendance($rosterId, $uid){
        $query = "delete from attendance where uid = '$uid' and rosterId = '$rosterId'";
        return $this->mysql->setQuery($query)->runDelete();
    }

    public function insertIntoAttendance($rosterId, $uid, $isGoing){
        $query = "insert into attendance(rosterId, uid, isGoing) values('$rosterId', '$uid', '$isGoing')";
        return $this->mysql->setQuery($query)->runCreate();
    }

    // ROSTER AND ATTENDANCE
    public function getRosterAttendanceByGameUid($uid){

        $subQuery = " select isgoing from attendance where uid = '$uid' and rosterid = roster.id ";

        $query = "SELECT id, firstname, lastname, gender, ($subQuery) as isgoing, '$uid' as uid ";
        $query .= "FROM roster ";
        $query .= "WHERE isactive = 1  ";
        $query .= "ORDER BY isgoing desc, firstname";

//        $query = "SELECT r.id, r.firstname, r.lastname, r.gender, ( ";
//        $query .= "SELECT isgoing ";
//        $query .= "FROM attendance ";
//        $query .= "WHERE uid =  '$uid' ";
//        $query .= "AND rosterid = r.id ";
//        $query .= ") AS isgoing ";
//        $query .= "FROM roster r ";
//        $query .= "WHERE r.isactive = 1 ";
//        $query .= "ORDER BY r.lastname";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getRosterAttendanceThatIsGoingByGameUid($uid){
        $subQuery = " select isgoing from attendance where uid = '$uid' and rosterid = roster.id ";

        $query = "SELECT id, firstname, lastname, gender, ($subQuery) as isgoing ";
        $query .= "FROM roster ";
        $query .= "WHERE isactive = 1 and ($subQuery) ";
        $query .= "ORDER BY firstname";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getRosterAttendanceThatIsNotGoingByGameUid($uid){
        $query = "SELECT r.id, r.firstname, r.lastname, ( ";
        $query .= "SELECT isgoing ";
        $query .= "FROM attendance ";
        $query .= "WHERE uid =  '$uid' ";
        $query .= "AND rosterid = r.id ";
        $query .= ") AS isgoing ";
        $query .= "FROM roster r ";
        $query .= "WHERE r.isactive = 1 and r.isgoing = 0 ";
        $query .= "ORDER BY r.firstname";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function getRosterAttendanceThatIsUnknownByGameUid($uid){
        $query = "SELECT r.id, r.firstname, r.lastname, ( ";
        $query .= "SELECT isgoing ";
        $query .= "FROM attendance ";
        $query .= "WHERE uid =  '$uid' ";
        $query .= "AND rosterid = r.id ";
        $query .= ") AS isgoing ";
        $query .= "FROM roster r ";
        $query .= "WHERE r.isactive = 1 and r.isgoing is null";
        $query .= "ORDER BY r.firstname";
        return $this->mysql->setQuery($query)->runRead();
    }

    // lineup
    public function getLineupByUid($__uid){
        $query = "select * from lineup where uid = '$__uid'";
        return $this->mysql->setQuery($query)->runRead();
    }

    public function createLineup($uid){
        $query = "insert into lineup(uid, starters, game) values('$uid', '{\"q1\":[],\"q2\":[],\"q3\":[],\"q4\":[]}', '{\"attendance\":[],\"lineups\":[],\"totalLineups\":0}')";
        return $this->mysql->setQuery($query)->runCreate();
    }

    public function saveLineup($uid, $game){
        $query = "update lineup set game = '$game' where uid = '$uid'";
        return $this->mysql->setQuery($query)->runUpdate();
    }


//select r.id, r.firstname, r.lastname,
//(select isgoing from attendance where uid = '449.teamsideline.com' and rosterid = r.id) as ig
//from roster r
//where r.isactive = 1
//order by r.lastname

}