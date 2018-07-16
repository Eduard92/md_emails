<section ng-controller="InputConfigCtrl">
    <div class="lead text-success"><?=lang('asignacion:'.$this->method)?></div>
    <?php echo form_open($this->uri->uri_string()); ?>
    <div class="form-group">
        <label>Titulo</label>
        <?=form_input('titulo',$org->name,'class="form-control" ng-model="titulo" readonly ng-init="titulo=\''.$org->name.'\'"')?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group" ng-init="table.slug = '<?=$org->table?>'">
                <label>Módulo</label>
                <?=form_dropdown('table',array(''=>' [ Elegir ] '),null,'class="form-control" ng-options="module.name for module in modules track by module.slug" ng-model="table"  ng-change="select_index(table)" ')?>
                <input type="hidden" name="index_table" value="{{index_table}}" />
            </div>
            <div class="form-group" ng-init="auth_by='<?=$org->auth_by?>'" >
                <label>Autenticado por</label>
                <select name="auth_by" class="form-control" ng-model="auth_by">
                    <option value="codigo">Código (Recomendado)</option>
                    <option ng-repeat="row in rows_left" value="{{row}}">{{row}}</option>
                </select>
            </div>

            <div class="form-group" ng-init="table_id='<?=$org->table_id?>'">
                <label>Columna primaria</label>
                <?=form_dropdown('table_id',array(''=>' [ Elegir ] '),null,'class="form-control" ng-options="row for row in rows_left track by row" ng-model="table_id" ')?>
            </div>
        </div>
    </div>
    <div class="buttons divider clearfix" >
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
    </div>
    <?php echo form_close();?>
</section>

