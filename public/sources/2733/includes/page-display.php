<?php
        require ("../cms_pilgrims_house/functions.php");
        // # Dynamic Page Display
        
        if(isset($_GET['title'])) {
            
            $page_title = $_GET['title'];
            
            
            $process_pages = mysqli_query($connection,"SELECT * FROM public_pages WHERE page_url='$page_title'");
            
            
            if(mysqli_num_rows($process_pages) > 0){
            
                
                
                // ACCOMODATION
                if(isset($_GET['room'])) {
                    
                $room_display = null;
                
                $page_room = $_GET['room'];
                    
                $process_rooms = mysqli_query($connection,"SELECT * FROM accomodation_pages WHERE page_url='$page_room'");
                $row_room = mysqli_fetch_assoc($process_rooms);
                
                if(mysqli_num_rows($process_rooms) > 0){
                
                $page_title_room = $row_room['page_title'];
                $room_description = $row_room['page_description'];
                $room_cancellation_description = $row_room['cancellation_description'];
                $room_slider_small_description = $row_room['slider_small_description'];
                $room_map = $row_room['map']; 
                $room_price = $row_room['price']; 
                $room_slider_image = $row_room['room_slider_image'];
                $service_rate = $row_room['service_rate'];
                $furniture_rental = $row_room['furniture_rental'];
                $amount_rooms = $row_room['amount_rooms'];
                $amount_of_beds_available = $row_room['amount_of_beds_available'];
                $ensuite_bathroom = $row_room['ensuite_bathroom'];
                $private_kitchenette = $row_room['private_kitchenette'];
                $kitchenette_shared = $row_room['kitchenette_shared'];
                $unit_size = $row_room['unit_size'];
                $room_type= $row_room['room_type'];
                $room_rate = $row_room['room_rate'];
                    
                } else {
                    
                    $room_display = "display:none;";
                    
                    echo '<style>.flat-row {padding: 50px 0;}.flat-row.flat-our {padding-bottom: 30px;}</style>
                        <section class="flat-row flat-our">
                        <div class="container">
                        <div class="row"> 
                        <div class="col-md-12">
                        <h1 style="text-align: center;font-size: 80px;">404</h1>
                        <h2 style="text-align: center;padding-top: 30px;">PAGE NOT FOUND!</h2>
                        <p style="text-align: center;">Oops! Looks like the page you are looking for does not exist.</p>
                        <div class="col-md-12" style="padding-top: 50px; ">
                        <div class="read-more view-all">
                        <a href="../home">Back Home</a>
                        </div>
                        </div> 
                        </div>
                        </div>
                        </div>
                        </section>';
                    
                    
                }
                
                }
                
                //PAGES
                $row = mysqli_fetch_assoc($process_pages);
                
                $page_title = $row['page_title'];
                $page_content = $row['page_content'];
                
                echo eval("?>".$page_content."<?");
               
            } else { 
                    echo '<style>.flat-row {padding: 50px 0;}.flat-row.flat-our {padding-bottom: 30px;}</style>
                        <section class="flat-row flat-our">
                        <div class="container">
                        <div class="row"> 
                        <div class="col-md-12">
                        <h1 style="text-align: center;font-size: 80px;">404</h1>
                        <h2 style="text-align: center;padding-top: 30px;">PAGE NOT FOUND!</h2>
                        <p style="text-align: center;">Oops! Looks like the page you are looking for does not exist.</p>
                        <div class="col-md-12" style="padding-top: 50px; ">
                        <div class="read-more view-all">
                        <a href="home">Back Home</a>
                        </div>
                        </div> 
                        </div>
                        </div>
                        </div>
                        </section>';
            }
            
        } 
        
?>

