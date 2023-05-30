<?php
//Loading Assets
$asset_already = [];
foreach($forms as $form) {
$type = @$form['type'] ?: 'text';

if (in_array($type, $asset_already)) continue;

?>
<?php if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/asset.blade.php'))): ?>
    <?php echo $__env->make('crudbooster::default.type_components.'.$type.'.asset', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/asset.blade.php'))): ?>
    <?php echo $__env->make('vendor.crudbooster.type_components.'.$type.'.asset', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>
<?php
$asset_already[] = $type;
} //end forms
?>

<?php $__env->startPush('head'); ?>
    <style type="text/css">
        #table-detail tr td:first-child {
            font-weight: bold;
            width: 25%;
        }
    </style>
<?php $__env->stopPush(); ?>

<div class='table-responsive'>
    <table id='table-detail' class='table table-striped'>

        <?php
        foreach($forms as $index=>$form):

        $name = $form['name'];
        @$join = $form['join'];
        @$value = (isset($form['value'])) ? $form['value'] : '';
        @$value = (isset($row->{$name})) ? $row->{$name} : $value;
        @$showInDetail = (isset($form['showInDetail'])) ? $form['showInDetail'] : true;

        if ($showInDetail == FALSE) {
            continue;
        }

        if (isset($form['callback_php'])) {
            @eval("\$value = ".$form['callback_php'].";");
        }

        if (isset($form['callback'])) {
            $value = call_user_func($form['callback'], $row);
        }

        if (isset($form['default_value'])) {
            @$value = $form['default_value'];
        }

        if ($join && @$row) {
            $join_arr = explode(',', $join);
            array_walk($join_arr, 'trim');
            $join_table = $join_arr[0];
            $join_title = $join_arr[1];
            $join_table_pk = CB::pk($join_table);
            $join_fk = CB::getForeignKey($table, $join_table);
            $join_query_{$join_table} = DB::table($join_table)->select($join_title)->where($join_table_pk, $row->{$join_fk})->first();
            $value = @$join_query_{$join_table}->{$join_title};
        }

        $type = @$form['type'] ?: 'text';
        $required = (@$form['required']) ? "required" : "";
        $readonly = (@$form['readonly']) ? "readonly" : "";
        $disabled = (@$form['disabled']) ? "disabled" : "";
        $jquery = @$form['jquery'];
        $placeholder = (@$form['placeholder']) ? "placeholder='".$form['placeholder']."'" : "";
        $file_location = base_path('vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/component_detail.blade.php');
        $user_location = resource_path('views/vendor/crudbooster/type_components/'.$type.'/component_detail.blade.php');

        ?>

        <?php if(file_exists($file_location)): ?>
            <?php $containTR = (substr(trim(file_get_contents($file_location)), 0, 4) == '<tr>') ? TRUE : FALSE;?>
            <?php if($containTR): ?>
                <?php echo $__env->make('crudbooster::default.type_components.'.$type.'.component_detail', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php else: ?>
                <tr>
                    <td><?php echo e($form['label']); ?></td>
                    <td><?php echo $__env->make('crudbooster::default.type_components.'.$type.'.component_detail', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?></td>
                </tr>
            <?php endif; ?>
        <?php elseif(file_exists($user_location)): ?>
            <?php $containTR = (substr(trim(file_get_contents($user_location)), 0, 4) == '<tr>') ? TRUE : FALSE;?>
            <?php if($containTR): ?>
                <?php echo $__env->make('vendor.crudbooster.type_components.'.$type.'.component_detail', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php else: ?>
                <tr>
                    <td><?php echo e($form['label']); ?></td>
                    <td><?php echo $__env->make('vendor.crudbooster.type_components.'.$type.'.component_detail', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?></td>
                </tr>
            <?php endif; ?>
        <?php else: ?>
        <!-- <tr><td colspan='2'>NO COMPONENT <?php echo e($type); ?></td></tr> -->
        <?php endif; ?>


        <?php endforeach;?>

    </table>
</div>