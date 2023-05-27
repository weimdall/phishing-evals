<?php
require_once __DIR__ . '/../function.php';

$xconfig = false;
$actual_page = 'smtp';

if (file_exists($api->dir_config . '/' . $api->smtp_config))
{
  $xconfig = true;
  @eval(file_get_contents($api->dir_config . '/' . $api->smtp_config));
}

if(isset($_POST['save']))
{
	$a = $_POST['smtphost'];
	$b = $_POST['smtpport'];
	$c = $_POST['smtpsecure'];
	$d = $_POST['smtpuser'];
	$e = $_POST['smtppass'];
  $f = $_POST['smtpfrom'];
  $g = $_POST['smtpname'];
	$api->setSMTP(array($a, $b, $c, $d, $e, $f, $g));
  $api->redirect("smtp?success=true");
}

if(isset($_POST['connect']))
{
  if ($api->checkSmtp() == true)
  {
    $api->redirect("smtp?connect=success");
  }
  else if ($api->checkSmtp() == false)
  {
    $api->redirect("smtp?failed=true");
  }
}

?>
<?php require 'page/header.php'; ?>

<body>

	<div id="main">

		<?php require 'page/sidebar.php'; ?>

		<div class="content">
			<div class="top-subhead">
				<h2>SMTP Settings</h2>
				<div class="clear"></div>
			</div>
			<div class="full-container no-border">
				<?php
					if (isset($_GET['success']) == true)
					{
						echo '<div class="success">Changes have been saved!</div>';
					}
          else if (isset($_GET['connect']))
          {
            echo '<div class="success">SMTP Connected!</div>';
          }
          else if (isset($_GET['failed']))
          {
            echo '<div class="failed">Connection Failed!</div>';
          }
				?>
					<form method="post" action="" autocomplete="off">
						<ul id="settings">
							<li>
								<div class="left">SMTP Host</div>
								<div class="right">
									<input type="text" name="smtphost" <?php if($xconfig == true){ echo "value=\"$config_smtphost\""; } ?> required>
								</div>
							</li>
							<li>
								<div class="left">SMTP Port</div>
								<div class="right">
									<input type="text" name="smtpport" <?php if($xconfig == true){ echo "value=\"$config_smtpport\""; } ?> required>
								</div>
							</li>
							<li>
								<div class="left">SMTP Secure</div>
								<div class="right">
									<select name="smtpsecure">
										<?php if($xconfig == true && $config_smtpsecure == 1){
											echo '<option value="1" selected>Enabled</option>
                      <option value="0">Disabled</option>';
										}
										else
										{
											echo '<option value="1">Enabled</option>
                      <option value="0" selected>Disabled</option>';
										}?>
									</select>
								</div>
							</li>
							<li>
								<div class="left">SMTP User</div>
								<div class="right">
									<input type="text" name="smtpuser" <?php if($xconfig == true){ echo "value=\"$config_smtpuser\""; } ?> required>
								</div>
							</li>
							<li>
								<div class="left">SMTP Pass</div>
								<div class="right">
									<input type="text" name="smtppass" <?php if($xconfig == true){ echo "value=\"$config_smtppass\""; } ?> required>
								</div>
							</li>
              <li>
								<div class="left">SMTP From</div>
								<div class="right">
									<input type="text" name="smtpfrom" <?php if($xconfig == true){ echo "value=\"$config_smtpfrom\""; } ?> required>
								</div>
							</li>
              <li>
								<div class="left">SMTP Name</div>
								<div class="right">
									<input type="text" name="smtpname" <?php if($xconfig == true){ echo "value=\"$config_smtpname\""; } ?> required>
								</div>
							</li>
						</ul>
						<br>
						<input type="submit" name="save" value="Save changes">
            &nbsp;&nbsp;
            <input type="submit" name="connect" value="Test Connect">
					</form>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<?php require 'page/footer.php'; ?>
</body>

</html>
