<?php

//Loading Assets
$asset_already = [];
foreach($forms as $form) {
$type = @$form['type'] ?: 'text';
$name = $form['name'];

if (in_array($type, $asset_already)) continue;
?>
<?php if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/asset.blade.php'))): ?>
    <?php echo $__env->make('crudbooster::default.type_components.'.$type.'.asset', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/asset.blade.php'))): ?>
    <?php echo $__env->make('vendor.crudbooster.type_components.'.$type.'.asset', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>
<?php
$asset_already[] = $type;
}


//Loading input components
$header_group_class = "";
foreach($forms as $index=>$form) {

$name = $form['name'];
@$join = $form['join'];
@$value = (isset($form['value'])) ? $form['value'] : '';
@$value = (isset($row->{$name})) ? $row->{$name} : $value;

$old = old($name);
$value = (! empty($old)) ? $old : $value;

$validation = array();
$validation_raw = isset($form['validation']) ? explode('|', $form['validation']) : array();
if ($validation_raw) {
    foreach ($validation_raw as $vr) {
        $vr_a = explode(':', $vr);
        if ($vr_a[1]) {
            $key = $vr_a[0];
            $validation[$key] = $vr_a[1];
        } else {
            $validation[$vr] = TRUE;
        }
    }
}

if (isset($form['callback_php'])) {
    @eval("\$value = ".$form['callback_php'].";");
}


if (isset($form['callback'])) {
    $value = call_user_func($form['callback'], $row);
}

if ($join && @$row) {
    $join_arr = explode(',', $join);
    array_walk($join_arr, 'trim');
    $join_table = $join_arr[0];
    $join_title = $join_arr[1];
    $join_query_{$join_table} = DB::table($join_table)->select($join_title)->where("id", $row->{'id_'.$join_table})->first();
    $value = @$join_query_{$join_table}->{$join_title};
}
$form['type'] = ($form['type']) ?: 'text';
$type = @$form['type'];
$required = (@$form['required']) ? "required" : "";
$required = (@strpos($form['validation'], 'required') !== FALSE) ? "required" : $required;
$readonly = (@$form['readonly']) ? "readonly" : "";
$disabled = (@$form['disabled']) ? "disabled" : "";
$placeholder = (@$form['placeholder']) ? "placeholder='".$form['placeholder']."'" : "";
$col_width = @$form['width'] ?: "col-sm-9";

if ($parent_field == $name) {
    $type = 'hidden';
    $value = $parent_id;
}

if ($type == 'header') {
    $header_group_class = "header-group-$index";
} else {
    $header_group_class = ($header_group_class) ?: "header-group-$index";
}

?>
<?php if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/component.blade.php'))): ?>
    <?php echo $__env->make('crudbooster::default.type_components.'.$type.'.component', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/component.blade.php'))): ?>
    <?php echo $__env->make('vendor.crudbooster.type_components.'.$type.'.component', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php else: ?>
    <p class='text-danger'><?php echo e($type); ?> is not found in type component system</p><br/>
<?php endif; ?>
<?php
}