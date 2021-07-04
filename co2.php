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
            <li class="nav-item active">
              <a class="nav-link" href="./co2.php">CO2</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./co2transfer.php">CO2 Transfer & Emission</a>
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
        $sql_us_co2 = "SELECT co2 from co2 where land='United States' and jahr>1989 order by jahr";
        $sql_us_jahr = "SELECT jahr from co2 where land='United States' and jahr>1989 order by jahr";
        $res_us_co2 = $conn->query($sql_us_co2);
        $res_us_jahr = $conn->query($sql_us_jahr);
        $sql_ger_co2 = "SELECT co2 from co2 where land='Germany' and jahr>1989 order by jahr";
        $res_ger_co2 = $conn->query($sql_ger_co2);
        $sql_chi_co2 = "SELECT co2 from co2 where land='China' and jahr>1989 order by jahr";
        $res_chi_co2 = $conn->query($sql_chi_co2);

        //define pass functions for javascript
        function sqlecho($res){
          echo "[";   
          if ($res->num_rows > 0) {
            // output data of each row
            while($row = $res->fetch_assoc()) {
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
            var usCo2Data=<?php sqlecho($res_us_co2)?>;
            var gerCo2Data=<?php sqlecho($res_ger_co2)?>;
            var chiCo2Data=<?php sqlecho($res_chi_co2)?>;
            var usJahrLabel=<?php sqlecho($res_us_jahr)?>;
                //console.log(valueData);
            var chart = new Chart('chart', {
            type: 'line',
            data: {
            labels: usJahrLabel,
            datasets: [
                {
                label: "USA",
                data: usCo2Data,
                fill: false,
                borderColor: 'red'
                },
                {
                label: "Germany",
                data: gerCo2Data,
                fill: false,
                borderColor: 'blue'
                },
                {
                label: "China",
                data: chiCo2Data,
                fill: false,
                borderColor: 'black'
                }
            ]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: 'CO2 Emisssions per Year'
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