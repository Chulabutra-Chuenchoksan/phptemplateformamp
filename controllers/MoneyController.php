<?php
    include_once "../classes/DBManager.php";
    include_once "../models/IncomeOutcomeModel.php";

    $params = $_GET;
    echo $params;
    IncomeOutcomeModel::insertData($params);

?>