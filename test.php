<?php
include 'loginCreator.php';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["med"]) && isset($_POST["username"]) && isset($_POST["pass"]) && isset($_POST["pharmaCode"]) && isset($_POST["action"]))
{
        $creator = new loginCreator($_POST["username"], $_POST["pass"], $_POST["pharmaCode"]);
        if($_POST["action"] == "getMedList")
        {        
            $result = $creator -> getMedListWithTips($_POST["med"]);
            preg_match('/\[.*\]/i', $result, $arr);
            echo ($arr[0]);
        }
        else if($_POST["action"] == "getMedDetails" && isset($_POST["medCode"]) && isset($_POST["medType"]))
        {
            //$creator -> getMedListWithTips($_POST["med"]);
            $result = $creator -> getMedDetails($_POST["med"], $_POST["medCode"], $_POST["medType"]);
            echo $result;
        }
        $creator -> destroySession();

}
?> 