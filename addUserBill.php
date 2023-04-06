<html>

<head>
    <?php

include "dbconfigure.php" ;
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css1/bootstrap.css" media="all" />
    <script src=js1/bootstrap.min.js></script>
    <script src=js1/jquery-3.2.1.min.js></script>

    <style>
    label {
        font-weight: bold
    }
    </style>
    <script>
    function calc() {

        var currentreading1 = document.getElementById("currentdayreading").value;
        var previousreading1 = document.getElementById("previousdayreading").value;
        var totaldayunits = parseInt(currentreading1) - parseInt(previousreading1);

        var currentnightreading1 = document.getElementById("currentnightreading").value;
        var previousnightreading1 = document.getElementById("previousnightreading").value;
        var totalnightunits = parseInt(currentnightreading1) - parseInt(previousnightreading1);

        var gasReaging1 = document.getElementById("currentgasreading").value;
        var previousgasreading1 = document.getElementById("previousgasreading").value;
        var totalgastunits = parseInt(gasReaging1) - parseInt(previousgasreading1);

        var date1 = document.getElementById("newbilldate").valueAsDate;
        var date2 = document.getElementById("lastmonthdate").valueAsDate;

        var Difference_In_Time = date1.getTime() - date2.getTime();

        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

        console.log(Difference_In_Days);

        var chargeperdayunit1 = document.getElementById("chargeperunitforday").value;
        var chargeperNightunit1 = document.getElementById("chargeperunitfornight").value;
        var chargeperGasunit1 = document.getElementById("chargeperunitforgas").value;
        var standingChargeunit = document.getElementById("stadingchargeper").value;

        var totaldayamount = parseInt(totaldayunits) * parseFloat(chargeperdayunit1);
        var totalnightamount = parseInt(totalnightunits) * parseFloat(chargeperNightunit1);
        var totalgastamount = parseInt(totalgastunits) * parseFloat(chargeperGasunit1);
        var standingamount = parseInt(Difference_In_Days) * parseFloat(standingChargeunit);

        var totalamount = totaldayamount + totalnightamount + totalgastamount + standingamount;
        document.getElementById("finalamount").value = totalamount;

    }

    </script>
</head>

<body>

    <?php include "navigationbar1.php"; ?>


    <div class="container" style="margin-top : 30px">

        <h2 class=text-center>Add New Bill</h2>

        <form method="post">

            <label>Consumer ID</label>
            <select class="form-control" name=connectionid>
                <?php
                  $sql = "SELECT connectionid from connection";
                  $rs = my_select($sql);
                  //$row = mysqli_num_rows($rs);
                  while ($row = mysqli_fetch_array($rs)){
                  $customername = $row['customername'];
                  $customerid = $row['customerid'];
                  echo "<option value='". $row['connectionid'] ."'>" .$row['connectionid'] ."</option>" ;
                  }
                  ?>
            </select>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>New Bill Date</label>
                    <input type="date" name="newbilldate" id="newbilldate" class="form-control" required/>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Last Month Bill Date</label>
                    <input type="date" name="lastmonthdate" id="lastmonthdate" class="form-control" required/>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Day Reading</label>
                    <input type="text" name="currentdayreading" id="currentdayreading" class="form-control" 
                        placeholder="Enter Day Current Reading" required/>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Previous Day Reading</label>
                    <input type="text" name="previousdayreading" id="previousdayreading" class="form-control" required
                         />
                </div>
            </div>


            <div class="col-sm-12">
                <div class="form-group">
                    <label>Charge Per Unit</label>
                    <input type="text" name="chargeperunitforday" id="chargeperunitforday" class="form-control" value="0.34"  readonly/>
                </div>
            </div>

            <!-- {% comment %} Night Current Details {% endcomment %} -->

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Night Reading</label>
                    <input type="text" name="currentnightreading" id="currentnightreading" class="form-control"
                        placeholder="Enter Night Current Reading" required />
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Previous Night Reading</label>
                    <input type="text" name="previousnightreading" id="previousnightreading" class="form-control" required
                        />
                </div>
            </div>


            <div class="col-sm-12">
                <div class="form-group">
                    <label>Charge Per Unit For Night</label>
                    <input type="text" name="chargeperunitfornight" id="chargeperunitfornight" class="form-control" value="0.2" readonly
                         />
                </div>
            </div>

            <!-- {% comment %} Gas Details {% endcomment %} -->

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Gas Reading</label>
                    <input type="text" name="currentgasreading" id="currentgasreading" class="form-control"
                        placeholder="Enter Gas Current Reading" required />
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Previous Gas Reading</label>
                    <input type="text" name="previousgasreading" id="previousgasreading" class="form-control" onkeyup="calc()" required
                         />
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Charge Per Unit For Gas</label>
                    <input type="text" name="chargeperunitforgas" id="chargeperunitforgas" class="form-control" value="0.1" readonly
                    />
                </div>
            </div>

            <!-- {% comment %} Standing charge per day {% endcomment %} -->

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Standing charge per day</label>
                    <input type="text" name="stadingchargeper" id="stadingchargeper" class="form-control" value="0.74" readonly
                         />
                </div>
            </div>

            <label>Final Amount</label>
            <input type="text" name="finalamount" id="finalamount" class="form-control">

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="duedate" class="form-control" />
                </div>
            </div>
    </div>
    <hr />
    <input type="submit" class="btn btn-primary" name="save" value="Submit" />


    </form>
    </div>

    <?php


if(isset($_POST["save"]))
{
$connectionid=$_POST['connectionid'];
$billformonth=$_POST['billformonth'];
$dayelectricitycurrentreading=$_POST['currentdayreading'];
$dayelectricitypreviousreading=$_POST['previousdayreading'];
$dayelectricitychargeperunit=$_POST['chargeperunitforday'];
$nightelectricitycurrentreading=$_POST['currentnightreading'];
$nightelectricitypreviousreading=$_POST['previousnightreading'];
$nightelectricitychargeperunit=$_POST['chargeperunitfornight'];
$gascurrentreading=$_POST['currentgasreading'];
$gaspreviousreading=$_POST['previousgasreading'];
$gaschargeperunit=$_POST['chargeperunitforgas'];
$standingcharge=$_POST['stadingchargeper'];
$finalamount=$_POST['finalamount'];
$duedate=$_POST['duedate'];
$status = "not paid";



$query="insert into bill(connectionid,billformonth,dayelectricitycurrentreading,dayelectricitypreviousreading, dayelectricitychargeperunit,nightelectricitycurrentreading,nightelectricitypreviousreading,nightelectricitychargeperunit,gascurrentreading,gaspreviousreading,gaschargeperunit,standingcharge,finalamount,duedate,status) values('$connectionid','$billformonth','$dayelectricitycurrentreading','$dayelectricitypreviousreading','$dayelectricitychargeperunit','$nightelectricitycurrentreading','$nightelectricitypreviousreading','$nightelectricitychargeperunit','$gascurrentreading','$gaspreviousreading','$gaschargeperunit','$standingcharge','$finalamount','$duedate','$status')";
$n = my_iud($query);
if($n==1)
{
echo "<script>alert('Record Saved Successfully');
window.location = 'viewmybill.php';
</script>";
}
else
{
echo "<script>alert('Something Went Wrong , Try Again');</script>";
}
}



?>


</body>

</html>


</div>

</body>

</html>