<?php
if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}
?>
<div class="sidebar">
  <ul id="menu">
    <?php echo ($actual_page == 'home') ? '<li value="home" class="active"><div id="imghome" class="active">Home</div></li>' : '<li value="home"><div id="imghome">Home</div></li>'; ?>
    <?php echo ($actual_page == 'smtp' || $actual_page == 'general') ? '<li value="settings" class="active"><div id="imgsettings" class="active">Settings</div></li>' : '<li value="settings"><div id="imgsettings">Settings</div></li>'; ?>
    <ul class="submenu">
      <?php
      echo ($actual_page == 'general') ? '<li value="general" class="active">General</li>' : '<li value="general">General</li>';
      if ($config_smtp == 1) {
        echo ($actual_page == 'smtp') ? '<li value="smtp" class="active">SMTP</li>' : '<li value="smtp">SMTP</li>';
      }
      ?>
    </ul>

    <li value="logout"><div id="imglogout">Logout</div></li>
  </ul>
</div>
