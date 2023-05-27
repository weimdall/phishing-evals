<?php if(Request::input('fileformat') == 'pdf'): ?>
    <h3><?php echo e(Request::input('filename')); ?></h3>
<?php endif; ?>
<?php //echo "<pre>"; print_r($client_data);exit; ?>

<table border='1' cellpadding='3' cellspacing="0" style='border-collapse: collapse;font-size:12px;margin-top: 0px;margin-top: 50px;'>
    <?php //echo count($columns)+1;exit;?>
    <thead>
        <tr>
            <td colspan='4' align="center">
                <div style="text-align: center;">
                    <div style="font-size: 14px;font-weight: bold;"> <?php echo e($client_data[0]->name); ?>&nbsp;<?php if(!empty($client_data[0]->city)): ?>
                        (<?php echo e($client_data[0]->city); ?>)
                    <?php endif; ?></div>
                
                </div>
            </td>
            <td colspan='3' align="center">
                <div style="text-align: center;">
                    <div style="font-size: 14px;font-weight: bold;">
                    <?php echo e($client_data[0]->cust_name); ?>&nbsp; <?php if(!empty($client_data[0]->cust_city)): ?>
                        (<?php echo e($client_data[0]->cust_city); ?>)
                    <?php endif; ?></div>
                
                </div>
            </td>
        </tr>
        <tr>
            <td colspan='4' align="center">
                <div style="text-align: center;">
                    <div>
                        <?php echo e($client_data[0]->address_1); ?></div>
                </div>
            </td>
             <td colspan='3' align="center">
                <div style="text-align: center;">
                    <div><?php echo e($client_data[0]->cust_address_1); ?></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan='4' align="center">
                <div style="text-align: center;">
                    <?php if(!empty($client_data[0]->address_2)): ?>
                        <?php echo e($client_data[0]->address_2); ?>,<?php echo e($client_data[0]->zipcode); ?>

                    <?php endif; ?>
                </div>
            </td>
            <td colspan='3' align="center">
                <div style="text-align: center;">
                     <?php if(!empty($client_data[0]->cust_address_2)): ?>
                        <?php echo e($client_data[0]->cust_address_2); ?>,<?php echo e($client_data[0]->cust_zipcode); ?>

                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan='4' align="center">
                <div style="text-align: center;">
                   <?php if(!empty($client_data[0]->gst_no)): ?>
                            GSTIN :-<?php echo e($client_data[0]->gst_no); ?>

                    <?php endif; ?>
                </div>
            </td>
            <td colspan='3' align="center">
                <div style="text-align: center;">
                    <?php if(!empty($client_data[0]->cust_gst_no)): ?>
                            GSTIN :-<?php echo e($client_data[0]->cust_gst_no); ?>

                        <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr class='warning'>
            <td colspan='<?php echo e(count($columns)); ?>' align="center" style="font-size: 12px;">Party Ledger Account</td>
        </tr>

        <?php if(isset($fromDate) && !empty($fromDate)): ?>
        <tr class='warning' >
            <td colspan='<?php echo e(count($columns)); ?>' align="center" style="font-size: 12px;font-weight: bold;"><?php echo e(date('d-M-Y', strtotime($fromDate))); ?> to <?php echo e(date('d-M-Y', strtotime($toDate))); ?> </td>
        </tr>
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
    <?php   $debit_total = 0;
            $credit_total= 0;
    ?>
     <?php if(isset($open_stock->balance) && count($result)!=0): ?>
            <tr>
                <?php $credit_total += $open_stock->balance;?>
                <td align="center">
                    <?php echo date("d-M-Y",strtotime($open_stock->date)); ?>

                </td>
                <td align="left" style="font-weight: bold;">
                    Opening Balance
                </td>
                <td></td>
                <td></td>
                <td></td>
                <?php if($open_stock->id_cms_privileges == 4): ?>
                    <td align="right">
                        <?php echo e(number_format($open_stock->balance,2)); ?>

                    </td>
                    <td></td>
                <?php else: ?>
                    <td></td>
                    <td align="right">
                        <?php echo e(number_format($open_stock->balance,2)); ?>

                    </td>
                <?php endif; ?>
            </tr>
        <?php endif; ?>
    
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
                               
                                //echo "<pre>";print_r($value);exit;
                                
                            }
                            if( strpos($value, ',')) {
                                $value = str_replace(',','',$value);
                                    }
                            if ($col['label'] == "Debit") {
                                    $debit_total += $value;

                                }
                                if ($col['label'] == "Credit") {
                                    $credit_total += $value;
                                }
                        //}
                                if ($col['label'] == "Debit" || $col['label'] == "Credit") {
                                    $align = 'right';
                                }elseif ($col['label'] == "Particulars" || $col['label'] == "Ref No.") {
                                    $align = 'left';
                                }else{
                                    $align = 'center';
                                }

                        echo "<td style='text-align:".$align."'>".$value."</td>"; 
                       // print_r("<td style='text-align:'".$align.">");exit;
                    }
                }
                ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td colspan="5" align="center" style="font-weight: bold;">
               
            </td>
            <td style="text-align: right;">
               <?php echo e(number_format($debit_total,2)); ?> 
            </td>
            <td style="text-align: right;">
               <?php echo e(number_format($credit_total,2)); ?> 
            </td>
        </tr>
        <tr>
            <td colspan="5" align="center" style="font-weight: bold;">
              Closing Balance
            </td>
            <td style="text-align: right;">
                <?php //$debit_total += $credit_total-$debit_total; ?>
               <?php echo e(number_format($credit_total-$debit_total,2)); ?> 
            </td>
            <td style="text-align: right;">
            </td>
        </tr>
         <tr>
            <td colspan="5" align="center" style="font-weight: bold;">
               Total Amount  
            </td>
            <td style="text-align: right;">
               <?php echo e(number_format($debit_total+($credit_total-$debit_total),2)); ?> 
            </td>
            <td style="text-align: right;">
               <?php echo e(number_format($credit_total,2)); ?> 
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

