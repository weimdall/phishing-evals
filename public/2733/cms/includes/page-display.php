<?php
        require ("../../cms_pilgrims_house/functions.php");
        // # Dynamic Page Display
        
        if(isset($_GET['title'])) {
            
            $page_title = $_GET['title'];
            
            
            $process_pages = mysqli_query($connection,"SELECT * FROM cms_pages WHERE page_url='$page_title'");
            
            
            if(mysqli_num_rows($process_pages) > 0){
            
                $row = mysqli_fetch_assoc($process_pages);
                
                $page_title = $row['page_title'];
                $page_content = $row['page_content'];
                
                echo eval("?>".$page_content."<?");
               
            } else {echo "<h1 style='text-align:center;padding-top:50px;'>404<h1>
                        <h2 style='text-align:center;'>PAGE NOT FOUND!</h2>
                        <p style='text-align:center;'>Oops! Looks like the page you are looking for does not exist.</p>
                        <a href='dashboard' style='justify-content: center;display: flex;'><button type='button' class='btn btn-primary'>Back To Dashboard</button></a>";
                
            }
            
        } 
        
?>

