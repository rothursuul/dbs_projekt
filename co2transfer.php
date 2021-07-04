<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AAPL Apple Inc. </title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://d3js.org/d3.v7.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
        #wrapper{
            width: 100%;
            height: 100%;
        }
        #chart{
            width: 100%;
            height: 100%;
        }
        </style>
    </head>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="./">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./co2.php">CO2</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="./co2transfervsco2.php">CO2 Transfer & Emission</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./co2vsgdp.php">CO2 vs GDP</a>
            </li>
          </ul>
        </div>
      </nav>
      <?php
        $servername = "XXX";
        $username = "XXX";
        $password = "XXX";
        $db = "XXX";

        // Create connection
        $conn = new mysqli($servername, $username, $password,$db);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
        if($conn){
          //echo "connn success";
        }
        //import data
        $sql_us_jahr = "SELECT jahr from co2 where land='United States' and jahr>1989 order by jahr";
        $res_us_jahr = $conn->query($sql_us_jahr);

        $sql_us_co2 = "SELECT co2 from co2 where land='United States' and jahr>1989 order by jahr";
        $res_us_co2 = $conn->query($sql_us_co2);
        $sql_ger_co2 = "SELECT co2 from co2 where land='Germany' and jahr>1989 order by jahr";
        $res_ger_co2 = $conn->query($sql_ger_co2);
        $sql_chi_co2 = "SELECT co2 from co2 where land='China' and jahr>1989 order by jahr";
        $res_chi_co2 = $conn->query($sql_chi_co2);

        $sql_us_co2transfer = "SELECT co2 from co2_transfer where land='United States' and jahr>1989 order by jahr";
        $res_us_co2transfer = $conn->query($sql_us_co2transfer);
        $sql_ger_co2transfer = "SELECT co2 from co2_transfer where land='Germany' and jahr>1989 order by jahr";
        $res_ger_co2transfer = $conn->query($sql_ger_co2transfer);
        $sql_chi_co2transfer = "SELECT co2 from co2_transfer where land='China' and jahr>1989 order by jahr";
        $res_chi_co2transfer = $conn->query($sql_chi_co2transfer);

        //define pass functions for javascript
        function sqlecho($res){
          echo "[";   
          if ($res->num_rows > 0) {
            // output data of each row
            while($row = $res->fetch_assoc()) {
              //if ($row["gdp"]){echo $row["gdp"].",";}
              if ($row["co2"]){echo $row["co2"].",";}
              if ($row["jahr"]){echo $row["jahr"].",";}
            }
          } 
          else {
            echo "None";
          }
          echo "]";
        }
      ?>
      <body>
        <div id="wrapper">
            <canvas id="chart"></canvas>
        <script>
            // get co2
            var usCo2Data=<?php sqlecho($res_us_co2)?>;
            var gerCo2Data=<?php sqlecho($res_ger_co2)?>;
            var chiCo2Data=<?php sqlecho($res_chi_co2)?>;

            // get co2 transfer
            var usCo2transferData=<?php sqlecho($res_us_co2transfer)?>;
            usCo2transferData = usCo2transferData.map(x => x * 3664000);
            var gerCo2transferData=<?php sqlecho($res_ger_co2transfer)?>;
            gerCo2transferData = gerCo2transferData.map(x => x * 3664000);
            var chiCo2transferData=<?php sqlecho($res_chi_co2transfer)?>;
            chiCo2transferData = chiCo2transferData.map(x => x * 3664000);

            var usJahrLabel=<?php sqlecho($res_us_jahr)?>;
                //console.log(valueData);
            var chart = new Chart('chart', {
            type: 'line',
            data: {
            labels: usJahrLabel,
            datasets: [
                {
                label: "USA CO2 Emission",
                data: usCo2Data,
                fill: false,
                borderColor: 'red'
                },
                {
                label: "USA CO2 Transfer",
                data: usCo2transferData,
                fill: false,
                borderColor: 'pink'
                },
                {
                label: "Germany CO2 Emission",
                data: gerCo2Data,
                fill: false,
                borderColor: 'blue'
                },
                {
                label: "Germany CO2 Transfer",
                data: gerCo2transferData,
                fill: false,
                borderColor: 'CornflowerBlue'
                },
                {
                label: "China CO2 Emission",
                data: chiCo2Data,
                fill: false,
                borderColor: 'black'
                },
                {
                label: "China CO2 Transfer",
                data: chiCo2transferData,
                fill: false,
                borderColor: 'gray'
                }
            ]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: 'CO2 transfer per year'
                }
              }
            }
            });
            //console.log(germany);
        </script>
    </body>
   <?php
    //close the connection
    $conn->close();?>

</html>