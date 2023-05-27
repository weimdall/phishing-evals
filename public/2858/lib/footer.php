<footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="copyright pull-none">
                                &copy; 2020 <strong></strong> Made with <i class="fa fa-heart text-danger"></i> By <b><?php echo $cfg_author; ?></b><br />
<?php
//Mengatur page load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
$load = microtime();
$loadtime = explode(' ', microtime()); 
$loadtime = $loadtime[0]+$loadtime[1]-$starttime; 

echo "Load Page ".number_format($load,4)." seconds.";
?>                                                    

            </div>
            </footer> 
            
            <!-- Footer Ends -->
<script src="<?php echo $cfg_baseurl; ?>assets/js/vendor.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/app.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.js"></script> 
<script src="<?php echo $cfg_baseurl; ?>assets/js/footable.core.js"></script>

<script src="<?php echo $cfg_baseurl; ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/datatables.min.js"> </script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/buttons.html5.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/buttons.flash.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/buttons.print.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/dataTables.select.min.js"></script>
<script src="<?php echo $cfg_baseurl; ?>assets/js/pdfmake.min.js"></script>

<script src="<?php echo $cfg_baseurl; ?>assets/js/data-tables.js"> </script>


        <script type="text/javascript"> 
         var htmlobjek; 
    $(document).ready(function(){ 

        $("#level").change(function(){ 
            var level = $("#level").val(); 

        $.ajax({ 
            url    : '<?php echo $cfg_baseurl; ?>inc/note_adduser.php', 
            data    : 'level='+level, 
            type    : 'POST', 
            dataType: 'html', 
            success    : function(msg){ 
                 $("#note").html(msg); 
                } 
            }); 
        }); 
    }); 
    </script>
    <script type="text/javascript">
				    function get_detail(url) {
				        $.ajax({
				        	type: "GET",
				            url: url,
				            beforeSend: function() {
				                $('#modal-DetailBody').html('Sedang memuat...');
				            },
				            success: function(result) {
				                $('#modal-DetailBody').html(result);
				            },
				            error: function() {
				                $('#modal-DetailBody').html('Terjadi kesalahan.');
				            }
				        });
				        $('#myModal').modal();
				    }
				</script>
				<script type="text/javascript">
            function copy_to_clipboard(element) {
                var copyText = document.getElementById(element);
            copyText.select();
        document.execCommand("copy");
        }
            </script>
            
            <script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
    
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                ]

            });

        });

    </script>
    
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>

            <script type="text/javascript">
        function modal_open(type, url) {
			$('#modal').modal('show');
			if (type == 'add') {
				$('#modal-title').html('<i class="fa fa-plus-square"></i> Tambah Data');
			} else if (type == 'edit') {
				$('#modal-title').html('<i class="fa fa-edit"></i> Ubah Data');
			} else if (type == 'delete') {
				$('#modal-title').html('<i class="fa fa-trash"></i> Hapus Data');
			} else if (type == 'detail') {
				$('#modal-title').html('<i class="fa fa-search"></i> Detail Data');
			} else if (type == 'get') {
				$('#modal-title').html('<i class="fa fa-search"></i> Get Layanan');
			} else {
				$('#modal-title').html('Empty');
			}
        	$.ajax({
        		type: "GET",
        		url: url,
        		beforeSend: function() {
        			$('#modal-detail-body').html('Sedang memuat...');
        		},
        		success: function(result) {
        			$('#modal-detail-body').html(result);
        		},
        		error: function() {
        			$('#modal-detail-body').html('Terjadi kesalahan.');
        		}
        	});
        	$('#modal-detail').modal();
        }
    	</script>
    	
    	<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
</body>
</html>