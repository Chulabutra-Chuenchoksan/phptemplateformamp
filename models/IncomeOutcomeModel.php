<?php
    class IncomeOutcomeModel {
        function getAll() {
            $conn = DBManager::getConnection();
            $sql = "select * from income_outcome";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //echo "<pre>".print_r($result[0]["amount"],true)."</pre>";
            return $result;
        }

        function insertData($params) {
            $conn = DBManager::getConnection();
            $sql = "insert into income_outcome (money_date, money_desc, amount) values (current_date, '$params[DescriptionValue]', $params[AmountValue])";
            echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
    }
?>