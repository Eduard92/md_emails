<style type="text/css">
md-dialog .md-actions ,md-dialog-actions{
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-order: 2;
    -ms-flex-order: 2;
    order: 2;
    box-sizing: border-box;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-justify-content: flex-end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    margin-bottom: 0;
    padding-right: 8px;
    padding-left: 16px;
    min-height: 52px;
    overflow: hidden;
}
</style>
<section ng-controller="IndexCtrl">
    <?php echo form_open('admin/emails', 'class="form-inline" method="get" ') ?>
    	<div class="form-group col-md-5">
    				
    				<?php echo form_input('f_keywords', '', 'style="width: 100%;" class="form-control" placeholder="Buscar por correo"') ?>
    			</div>
    
    			<button class="md-raised btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                <div class="clearfix"></div>
    <?php echo form_close() ?>
        <hr />
        <p class="text-right text-muted">Total registros: <?=count($emails)?> </p>
    <table class="table">
        <thead>
            <tr>
                <th width="2%"><input type="checkbox" ng-model="select_all()" /></th>
                <th>Email</th>
                <th>Org</th>
                <th width="22%">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($emails as $email){?>
            <tr>
                <td ><input type="checkbox" name="action_to[]" /></td>
                <td>
                <?=$email->email?>
                </td>
                <td>
                <?=$email->org?>
                </td>
                <td>
                      <a href="<?=base_url('admin/emails/edit/'.$email->id)?>">Modificar</a>  |
                    <a href="#">Resetear</a> 
                    |
                    <a href="javascript:;" ng-click="showConfirm($event,'<?=$email->email?>')">Eliminar</a>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</section>
