<?php if(Request::input('fileformat') == 'pdf'): ?>
    <h3><?php echo e(Request::input('filename')); ?></h3>
<?php endif; ?>
<?php //echo "<pre>"; print_r($result[0]);exit; ?>

<table border='1' cellpadding='3' cellspacing="0" style='border-collapse: collapse;font-size:12px;margin-top: 0px;margin-top: 50px;'>
    <?php //echo count($columns)+1;exit;?>
    <thead>
        <tr>
            <td colspan='<?php echo e(count($columns)); ?>'>
                <div >
                    <div style="font-size: 14px;font-weight: bold;"> <?php echo e($client_data[0]->name); ?>&nbsp; <?php if(!empty($client_data[0]->city)): ?>
                        (<?php echo e($client_data[0]->city); ?>)
                        <?php endif; ?></div>
                
                </div>
            </td>
        </tr>
        <tr>
            <td colspan='<?php echo e(count($columns)); ?>'>
                <div><?php echo e($client_data[0]->address_1); ?></div>
            </td>
        </tr>
        <tr>
            <td colspan='<?php echo e(count($columns)); ?>'>
                <?php if(!empty($client_data[0]->address_2)): ?>
                    <?php echo e($client_data[0]->address_2); ?>,<?php echo e($client_data[0]->zipcode); ?>

                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td colspan='<?php echo e(count($columns)); ?>'>
                    <?php if(!empty($client_data[0]->gst_no)): ?>
                            GSTIN :-<?php echo e($client_data[0]->gst_no); ?>

                    <?php endif; ?>
            </td>
        </tr>
        <?php if($invoice_type == 'Sales Register'): ?>
        <tr class='warning'>

            <td colspan='<?php echo e(count($columns)); ?>' align="center" style="font-size: 14px;font-weight: bold;">Sales Invoice Report</td>
        </tr>

        <?php if(isset($fromDate) && !empty($fromDate)): ?>
       
        <tr class='warning'>
            <td colspan='<?php echo e(count($columns)); ?>' align="center"><?php echo e(date('d-M-Y', strtotime($fromDate))); ?> to <?php echo e(date('d-M-Y', strtotime($toDate))); ?> </td>
        </tr>
        <?php endif; ?>
        <?php endif; ?>
        <tr>
            <?php
            foreach ($columns as $col) {

                if (Request::get('columns')) {
                    if (! in_array($col['name'], Request::get('columns'))) {
                        continue;
                    }
                }
                $colname = $col['label'];
                echo "<th align='center'>$colname</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
    <?php if(count($result)==0): ?>
        <tr class='warning'>
            <td colspan='<?php echo e(count($columns)); ?>' align="center">No Data Available</td>
        </tr>
    <?php else: ?>
    <?php   $cgst_total = 0;
            $sgst_total= 0;
            $igst_total = 0;
            $taxable_total = 0;
            $grand_total = 0;
            $round_total = 0;
            $freight_total = 0;

    ?>
        <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <?php
                foreach ($columns as $col) {

                    if (Request::get('columns')) {
                        if (! in_array($col['name'], Request::get('columns'))) {
                            continue;
                        }
                    }

                    $value = @$row->{$col['field']};
                    $title = @$row->{$title_field};

                    if (@$col['image']) {
                        if ($value == '') {
                            $value = "http://placehold.it/50x50&text=NO+IMAGE";
                        }
                        $pic = (strpos($value, 'http://') !== FALSE) ? $value : asset($value);
                        $pic_small = $pic;
                        if (Request::input('fileformat') == 'pdf') {
                            echo "<td><a data-lightbox='roadtrip' rel='group_{{$table}}' title='$col[label]: $title' href='".$pic."'><img class='img-circle' width='40px' height='40px' src='".$pic_small."'/></a></td>";
                        } else {
                            echo "<td>$pic</td>";
                        }
                    } elseif (@$col['download']) {
                        $url = (strpos($value, 'http://') !== FALSE) ? $value : asset($value);
                        echo "<td><a class='btn btn-sm btn-primary' href='$url' target='_blank' title='Download File'>Download</a></td>";
                    } else {

                        //limit character
                        if ($col['str_limit']) {
                            $value = trim(strip_tags($value));
                            $value = str_limit($value, $col['str_limit']);
                        }

                        if ($col['nl2br']) {
                            $value = nl2br($value);
                        }

                        //if (Request::input('fileformat') == 'pdf') {
                            if (! empty($col['callback_php'])) {

                                foreach ($row as $k => $v) {
                                    $col['callback_php'] = str_replace("[".$k."]", $v, $col['callback_php']);
                                }
                                @eval("\$value = ".$col['callback_php'].";");
                            }

                            //New method for callback
                            if (isset($col['callback'])) {
                                $value = call_user_func($col['callback'], $row);
                                if( strpos($value, ',')) {
                                        $value = str_replace(',','',$value);
                                    }

                                //echo "<pre>";print_r($value);exit;
                                if ($col['label'] == "CGST") {
                                    $cgst_total += $value;

                                }
                                if ($col['label'] == "SGST") {

                                    $sgst_total += $value;
                                }
                                if ($col['label'] == "IGST") {
                                    $igst_total += $value;
                                }
                                if ($col['label'] == "Taxable Value") {
                                    $taxable_total += $value;
                                    //print_r($col);exit;
                                }
                                if ($col['label'] == "Total Invoice Value") {
                                    $grand_total +=$value;
                                }
                                if ($col['label'] == "Round Off(-/+)") {
                                    $round_total += $value;
                                }
                                if ($col['label'] == "Freight") {
                                    $freight_total += $value;
                                }
                            
                            }
                        //}

                            if ($col['label'] == "GSTIN/UIN" || $col['label'] == "Invoice No" || $col['label'] == "Date" || $col['label'] == "Customer Name") {
                                    $align = 'center';
                                }elseif ($col['label'] == "Particulars") {
                                    $align = 'left';
                                }else{
                                    $align = 'right';
                                }

                        echo "<td style='text-align:".$align."'>".$value."</td>";

                    }
                }
                ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       
        <tr>
            <td colspan="6" align="center">
               Total Amount  
            </td>
            <td>
               <?php echo e(number_format($grand_total,4)); ?> 
            </td>
            <td >
               <?php echo e(number_format($taxable_total,4)); ?> 
            </td>
             <td>
               <?php echo e(number_format($cgst_total,4)); ?> 
            </td>
            <td>
               <?php echo e(number_format($sgst_total,4)); ?> 
            </td>
             <td>
               <?php echo e(number_format($igst_total,4)); ?> 
            </td>
            <td>
               <?php echo e(number_format($freight_total,4)); ?> 
            </td>
            <td>
               <?php echo e(number_format($round_total,4)); ?> 
            </td>
            
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<script type="text/php">
    if ( isset($pdf) ) {
        $font = Font_Metrics::get_font("helvetica", "bold");
        $pdf->page_text(36, 18, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
    }

</script>

