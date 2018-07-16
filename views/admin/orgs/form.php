<section>
    <div class="lead text-success"><?=lang('email:orgs_'.$this->method)?></div>
    <?php echo form_open();?>
    <div class="form-group">
        <label>Org path</label>
        <?=form_input('org_path',$org->org_path,'class="form-control" disabled')?>
    </div>
    <div class="form-group">
        <label>Usuarios</label>
        <div>
        <?php foreach($users AS $user): ?>
            <label class="checkbox-inline col-md-3">
                <input type="checkbox" name="users[]" <?=in_array($user->user_id,$users_active)?'checked':''?> value="<?=$user->user_id?>" /><?=$user->email?>
            <br />
            <span class="text-muted"><?=$user->group_name?></span>
            </label>
        <?php endforeach;?>
        </div>
    </div>
    <div class="divider clearfix"></div>
    <hr />
    <div class="form-actions">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
    </div>
    <?php echo form_close();?>
</section>