<?php $__currentLoopData = $addaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    foreach ($row as $key => $val) {
        $a['url'] = str_replace("[".$key."]", $val, $a['url']);
    }

    $confirm_box = '';
    if (isset($a['confirmation']) && ! empty($a['confirmation']) && $a['confirmation']) {

        $a['confirmation_title'] = ! empty($a['confirmation_title']) ? $a['confirmation_title'] : trans('crudbooster.confirmation_title');
        $a['confirmation_text'] = ! empty($a['confirmation_text']) ? $a['confirmation_text'] : trans('crudbooster.confirmation_text');
        $a['confirmation_type'] = ! empty($a['confirmation_type']) ? $a['confirmation_type'] : 'warning';
        $a['confirmation_showCancelButton'] = empty($a['confirmation_showCancelButton']) ? 'true' : 'false';
        $a['confirmation_confirmButtonColor'] = ! empty($a['confirmation_confirmButtonColor']) ? $a['confirmation_confirmButtonColor'] : '#DD6B55';
        $a['confirmation_confirmButtonText'] = ! empty($a['confirmation_confirmButtonText']) ? $a['confirmation_confirmButtonText'] : trans('crudbooster.confirmation_yes');;
        $a['confirmation_cancelButtonText'] = ! empty($a['confirmation_cancelButtonText']) ? $a['confirmation_cancelButtonText'] : trans('crudbooster.confirmation_no');;
        $a['confirmation_closeOnConfirm'] = empty($a['confirmation_closeOnConfirm']) ? 'true' : 'false';

        $confirm_box = '
        swal({   
            title: "'.$a['confirmation_title'].'",
            text: "'.$a['confirmation_text'].'",
            type: "'.$a['confirmation_type'].'",
            showCancelButton: '.$a['confirmation_showCancelButton'].',
            confirmButtonColor: "'.$a['confirmation_confirmButtonColor'].'",
            confirmButtonText: "'.$a['confirmation_confirmButtonText'].'",
            cancelButtonText: "'.$a['confirmation_cancelButtonText'].'",
            closeOnConfirm: '.$a['confirmation_closeOnConfirm'].', }, 
            function(){  location.href="'.$a['url'].'"});        

        ';
    }

    $label = $a['label'];
    $title = ($a['title']) ?: $a['label'];
    $icon = $a['icon'];
    $color = $a['color'] ?: 'primary';
    $confirmation = $a['confirmation'];
    $target = $a['target'] ?: '_self';

    $url = $a['url'];
    if (isset($confirmation) && ! empty($confirmation)) {
        $url = "javascript:;";
    }

    if (isset($a['showIf'])) {

        $query = $a['showIf'];

        foreach ($row as $key => $val) {
            $query = str_replace("[".$key."]", '"'.$val.'"', $query);
        }

        @eval("if($query) {
          echo \"<a class='btn btn-xs btn-\$color' title='\$title' onclick='\$confirm_box' href='\$url' target='\$target'><i class='\$icon'></i> $label</a>&nbsp;\";
      }");
    } else {
        echo "<a class='btn btn-xs btn-$color' title='$title' onclick='$confirm_box' href='$url' target='$target'><i class='$icon'></i> $label</a>&nbsp;";
    }
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($button_action_style == 'button_text'): ?>

    <?php if(CRUDBooster::isRead() && $button_detail): ?>
        <a class='btn btn-xs btn-primary btn-detail' title='<?php echo e(trans("crudbooster.action_detail_data")); ?>'
           href='<?php echo e(CRUDBooster::mainpath("detail/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())); ?>'><?php echo e(trans("crudbooster.action_detail_data")); ?></a>
    <?php endif; ?>

    <?php if(CRUDBooster::isUpdate() && $button_edit): ?>
        <a class='btn btn-xs btn-success btn-edit' title='<?php echo e(trans("crudbooster.action_edit_data")); ?>'
           href='<?php echo e(CRUDBooster::mainpath("edit/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field); ?>'><?php echo e(trans("crudbooster.action_edit_data")); ?></a>
    <?php endif; ?>

    <?php if(CRUDBooster::isDelete() && $button_delete): ?>
        <?php $url = CRUDBooster::mainpath("delete/".$row->$pk);?>
        <a class='btn btn-xs btn-warning btn-delete' title='<?php echo e(trans("crudbooster.action_delete_data")); ?>' href='javascript:;'
           onclick='<?php echo e(CRUDBooster::deleteConfirm($url)); ?>'><?php echo e(trans("crudbooster.action_delete_data")); ?></a>
    <?php endif; ?>
<?php elseif($button_action_style == 'button_icon_text'): ?>


    <?php if(CRUDBooster::isRead() && $button_detail): ?>
        <a class='btn btn-xs btn-primary btn-detail' title='<?php echo e(trans("crudbooster.action_detail_data")); ?>'
           href='<?php echo e(CRUDBooster::mainpath("detail/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())); ?>'><i
                    class='fa fa-eye'></i> <?php echo e(trans("crudbooster.action_detail_data")); ?></a>
    <?php endif; ?>

    <?php if(CRUDBooster::isUpdate() && $button_edit): ?>
        <a class='btn btn-xs btn-success btn-edit' title='<?php echo e(trans("crudbooster.action_edit_data")); ?>'
           href='<?php echo e(CRUDBooster::mainpath("edit/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field); ?>'><i
                    class='fa fa-pencil'></i> <?php echo e(trans("crudbooster.action_edit_data")); ?></a>
    <?php endif; ?>

    <?php if(CRUDBooster::isDelete() && $button_delete): ?>
        <?php $url = CRUDBooster::mainpath("delete/".$row->$pk);?>
        <a class='btn btn-xs btn-warning btn-delete' title='<?php echo e(trans("crudbooster.action_delete_data")); ?>' href='javascript:;'
           onclick='<?php echo e(CRUDBooster::deleteConfirm($url)); ?>'><i class='fa fa-trash'></i> <?php echo e(trans("crudbooster.action_delete_data")); ?></a>
    <?php endif; ?>

<?php elseif($button_action_style == 'dropdown'): ?>

    <div class='btn-group btn-group-action'>
        <button type='button' class='btn btn-xs btn-primary btn-action'><?php echo e(trans("crudbooster.action_label")); ?></button>
        <button type='button' class='btn btn-xs btn-primary dropdown-toggle' data-toggle='dropdown'>
            <span class='caret'></span>
            <span class='sr-only'>Toggle Dropdown</span>
        </button>
        <ul class='dropdown-menu dropdown-menu-action' role='menu'>
            <?php $__currentLoopData = $addaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                foreach ($row as $key => $val) {
                    $a['url'] = str_replace("[".$key."]", $val, $a['url']);
                }

                $label = $a['label'];
                $url = $a['url']."?return_url=".urlencode(Request::fullUrl());
                $icon = $a['icon'];
                $color = $a['color'] ?: 'primary';

                if (isset($a['showIf'])) {

                    $query = $a['showIf'];

                    foreach ($row as $key => $val) {
                        $query = str_replace("[".$key."]", '"'.$val.'"', $query);
                    }

                    @eval("if($query) {
                        echo \"<li><a title='\$label' href='\$url'><i class='\$icon'></i> \$label</a></li>\";
                    }");
                } else {
                    echo "<li><a title='$label' href='$url'><i class='$icon'></i> $label</a></li>";
                }
                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(CRUDBooster::isRead() && $button_detail): ?>
                <li><a class='btn-detail' title='<?php echo e(trans("crudbooster.action_detail_data")); ?>'
                       href='<?php echo e(CRUDBooster::mainpath("detail/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())); ?>'><i
                                class='fa fa-eye'></i> <?php echo e(trans("crudbooster.action_detail_data")); ?></a></li>
            <?php endif; ?>

            <?php if(CRUDBooster::isUpdate() && $button_edit): ?>
                <li><a class='btn-edit' title='<?php echo e(trans("crudbooster.action_edit_data")); ?>'
                       href='<?php echo e(CRUDBooster::mainpath("edit/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field); ?>'><i
                                class='fa fa-pencil'></i> <?php echo e(trans("crudbooster.action_edit_data")); ?></a></li>
            <?php endif; ?>

            <?php if(CRUDBooster::isDelete() && $button_delete): ?>
                <?php $url = CRUDBooster::mainpath("delete/".$row->$pk);?>
                <li><a class='btn-delete' title='<?php echo e(trans("crudbooster.action_delete_data")); ?>' href='javascript:;'
                       onclick='<?php echo e(CRUDBooster::deleteConfirm($url)); ?>'><i class='fa fa-trash'></i> <?php echo e(trans("crudbooster.action_delete_data")); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

<?php else: ?>

    <?php if(CRUDBooster::isRead() && $button_detail): ?>
        <a class='btn btn-xs btn-primary btn-detail' title='<?php echo e(trans("crudbooster.action_detail_data")); ?>'
           href='<?php echo e(CRUDBooster::mainpath("detail/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())); ?>'><i class='fa fa-eye'></i></a>
    <?php endif; ?>

    <?php if(CRUDBooster::isUpdate() && $button_edit): ?>
        <a class='btn btn-xs btn-success btn-edit' title='<?php echo e(trans("crudbooster.action_edit_data")); ?>'
           href='<?php echo e(CRUDBooster::mainpath("edit/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field); ?>'><i
                    class='fa fa-pencil'></i></a>
    <?php endif; ?>

    <?php if(CRUDBooster::isDelete() && $button_delete): ?>
        <?php $url = CRUDBooster::mainpath("delete/".$row->$pk);?>
        <a class='btn btn-xs btn-warning btn-delete' title='<?php echo e(trans("crudbooster.action_delete_data")); ?>' href='javascript:;'
           onclick='<?php echo e(CRUDBooster::deleteConfirm($url)); ?>'><i class='fa fa-trash'></i></a>
    <?php endif; ?>

<?php endif; ?>
