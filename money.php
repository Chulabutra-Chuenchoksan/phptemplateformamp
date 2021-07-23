<?php
    include_once "classes/DBManager.php";
    include_once "models/IncomeOutcomeModel.php";
    $result = IncomeOutcomeModel::getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
        crossorigin="anonymous"></script>
    <style>
        label {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row my-3">
            <div class="col-12">
                <h2 class="text-center">Income & Outcome</h2>
            </div>
        </div>
        <div class="row mb-3 text-right align-items-center">
            <label class="col-2" for="">Income: </label>
            <input class="col-3 form-control" type="number" id="Income">
            <label class="col-2" for="">Description: </label>
            <input class="col-3 form-control" type="text" id="Description">
            <button class="offset-1 col-1 btn btn-primary" id="SaveId" onclick="saving('income')">Save</button>
        </div>
        <div class="row mb-5 text-right align-items-center">
            <label class="col-2" for="">Outcome: </label>
            <input class="col-3 form-control" type="number" id="Outcome">
            <label class="col-2" for="">Description: </label>
            <input class="col-3 form-control" type="text" id="OutcomeDesc">
            <button class="offset-1 col-1 btn btn-danger" onclick="saving('outcome')">Save</button>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="table-primary" scope="col">Date</th>
                            <th class="table-secondary" scope="col">Description</th>
                            <th class="table-info" scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="tableId">
                        <?php 
                            $total = 0;
                            foreach($result as $row) { 
                        ?>
                            <?php
                                $total += $row["amount"];
                                $color = "success";
                                if($row["amount"] < 0) {
                                    $color = "danger";
                                }
                            ?>
                            <tr class="table-<?=$color?>">
                                <td><b><?=$row["money_date"]?></b></td>
                                <td><?=$row["money_desc"]?></td>
                                <td><?=number_format($row["amount"], 2)?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-warning">
                            <td colspan="2"><b>Total</b></td>
                            <td id="total"><?=number_format($total, 2)?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

<script>
    var total = <?=$total?>;

    function saving(saveType) {

        var Table = document.getElementById("tableId");
        var totalElem = document.getElementById("total");
        var now = new Date();
        var DateValue = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, "0")}-${now.getDate()}`;
        // var DateValue = `${now.getDate()}/${(now.getMonth() + 1)}/${now.getFullYear()}`;

        if (saveType == "income") {
            var AmountElem = document.getElementById("Income");
            var DescriptionElem = document.getElementById("Description");
            var AmountValue = parseFloat(AmountElem.value);
            var DescriptionValue = DescriptionElem.value;
            var Colour = "success";
        }

        else {
            var AmountElem = document.getElementById("Outcome");
            var DescriptionElem = document.getElementById("OutcomeDesc");
            var AmountValue = parseFloat(AmountElem.value) * -1;
            var DescriptionValue = DescriptionElem.value;
            var Colour = "danger";
        }

        if (AmountElem.value != '' && DescriptionElem.value != '') {
            AmountElem.value = '';
            DescriptionElem.value = '';
            total = total + AmountValue;
            totalElem.innerText = total.toLocaleString(undefined, {minimumFractionDigits: 2});
            var htmlRow = `
                <tr class="table-${Colour}">
                    <th scope="row">${DateValue}</th>
                    <td>${DescriptionValue}</td>
                    <td>${AmountValue.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                </tr>
            `;
            $(Table).append(htmlRow);
            insertIncomeOutCome(DescriptionValue, AmountValue);
        }

        else {
            alert("You need to fill in all the boxes!")
        }
    }

    function insertIncomeOutCome(DescriptionValue, AmountValue) {
        console.log("getting data");
        $.ajax({
            url: `controllers/MoneyController.php?DescriptionValue=${DescriptionValue}&AmountValue=${AmountValue}`,
            method: 'GET',
            success: function(data) {
                console.log(data);
            }
        })
    }
</script>

</html>