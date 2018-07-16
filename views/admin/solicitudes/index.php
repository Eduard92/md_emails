<div class="content" ng-controller="SolicitudIndexCtrl">
        <?php echo form_open('admin/emails/solicitudes/', 'class="form-inline" method="get" ') ?>
            
                
                <div class="form-group col-md-5">
                    <label for="f_concepto">Centro: </label>
                    
                    <?=form_dropdown('f_centro',array(''=>' [ Todos ] ')+$centros,false,'class="form-control"')?>
                </div>
            
    
                <button class="md-raised btn btn-default"><i class="fa fa-search"></i> Buscar</button>
                <?php if($_GET):?>
                <a href="<?=base_url('admin/emails/solicitudes')?>" class="md-raised btn btn-success"><i class="fa fa-refresh"></i> Mostrar todos</a>
                <?php endif;?>
                
                
                
                
                
            
        <?php echo form_close() ?>
        <hr />

       <div class="alert" ng-bind-html="message" ng-if="message" ng-class="{'alert-danger':!status,'alert-success':status}"></div>

    <div class="ui-tab-container ui-tab-horizontal" >
        <uib-tabset justified="false" class="ui-tab">
            
            <uib-tab heading="Recibidos" >
                <?php if(!empty($data['Recibidos'])):?>                    
                        <table class="table">
                            <thead>
                                 <tr>
                                    <th>Nombre</th>
                                    <th>Plantel</th>
                                    <!--th>Apellidos</th-->
                                    <!--th>Grupo</th-->
                                    <th>Matricula</th>
                                    <th>Motivo de Solicitud</th> 
                                    <th width="15%"></th>                         
                                </tr>
                            </thead>
                            <tbody id="bind-enviados">
                                    <tr ng-repeat = "recibido in recibidos ">
                                        <td>{{ recibido.full_name }}</td>
                                        <td>{{ recibido.extra.plantel }}</td>
                                        <td>{{ recibido.extra.matricula }}</td>
                                        <td>{{ recibido.extra.motivo }}</td>
                                        <td>
                                            <a href="#" ng-click="changeStatus(recibido)" ui-wave class="btn-icon  btn-icon-sm btn-tumblr" title="Cambiar Estatus"><i class="fa fa-cogs" ></i></a>
                                            <a href="#" ng-click="details(recibido)" ui-wave class="btn-icon  btn-icon-sm btn-info" title="Detalles"><i class="fa fa-search" ></i></a>
                                            <a href="<?=base_url('admin/emails/solicitudes/delete/{{recibido.id}}')?>" ui-wave class="btn-icon  btn-icon-sm btn-danger" title="Eliminar"><i class="fa fa-close" confirm-action ></i></a>
                                        </td>
                                    </tr> 
                            </tbody>
                        </table>            
                <?php else: ?>
                     <div class="alert alert-info">
                      <?=lang('email:not_found_solicitudes')?>
                    </div>
                <?php endif;?>

            </uib-tab>
        <?php if(!empty($data['Validados'])):?>
            <uib-tab heading="Validados">
                
                        <table class="table">
                            <thead>
                                 <tr>
                                    <th>Nombre</th>
                                    <th>Plantel</th>
                                    <!--th>Apellidos</th-->
                                    <!--th>Grupo</th-->
                                    <th>Matricula</th>
                                    <th>Motivo de Solicitud</th> 
                                    <th width="15%"></th>                         
                                </tr>
                            </thead>
                            <tbody id="bind-validados">
                                    <tr ng-repeat = "validado in validados ">
                                        <td>{{ validado.full_name }}</td>
                                        <td>{{ validado.extra.plantel }}</td>
                                        <td>{{ validado.extra.matricula }}</td>
                                        <td>{{ validado.extra.motivo }}</td>
                                        <td>
                                            <a href="#" ng-click="details(validado)" ui-wave class="btn-icon  btn-icon-sm btn-info" title="Detalles"><i class="fa fa-search" ></i></a>
                                            <a href="<?=base_url('admin/emails/solicitudes/delete/{{validado.id}}')?>" ui-wave class="btn-icon  btn-icon-sm btn-danger" title="Eliminar"><i class="fa fa-close" confirm-action ></i></a>
                                        </td>
                                    </tr> 
                            </tbody>
                        </table>
                </uib-tab>
        <?php endif;?>
        <?php if(!empty($data['Rechazados'])):?>
            <uib-tab heading="Rechazados">                                    
                        <table class="table">
                            <thead>
                                 <tr>
                                    <th>Nombre</th>
                                    <th>Plantel</th>
                                    <!--th>Apellidos</th-->
                                    <!--th>Grupo</th-->
                                    <th>Matricula</th>
                                    <th>Motivo de Solicitud</th> 
                                    <th width="15%"></th>                         
                                </tr>
                            </thead>
                            <tbody id="bind-validados">
                                    <tr ng-repeat = "rechazado in rechazados ">
                                        <td>{{ rechazado.full_name }}</td>
                                        <td>{{ rechazado.extra.plantel }}</td>
                                        <td>{{ rechazado.extra.matricula }}</td>
                                        <td>{{ rechazado.extra.motivo }}</td>
                                        <td>
                                            
                                            <a href="<?=base_url('admin/emails/solicitudes/delete/{{rechazado.id}}')?>" ui-wave class="btn-icon  btn-icon-sm btn-danger" title="Eliminar"><i class="fa fa-close" confirm-action ></i></a>
                                        </td>
                                    </tr> 
                            </tbody>
                        </table>       
            </uib-tab>
        <?php endif;?>
        </uib-tabset>
    </div> 


</div> 
<script type="text/ng-template" id="ModalStatus.html" >
     
    <div class="modal-header">
                                <h3>Solicitud de Email</h3>
    </div>
    <div class="modal-body">
       <div class="alert" ng-bind-html="message" ng-if="message" ng-class="{'alert-danger':!status}"></div>
       <div class="alert" ng-bind-html="message_1" ng-if="message_1" ng-class="{'alert-danger':!status_1,'alert-info':status_1}"></div>


        <div class="ui-tab-container ui-tab-horizontal" >
    
        <uib-tabset justified="false" class="ui-tab">
            
            <uib-tab heading="Datos Generales" >
                <div class="form-group" >
                        <label>Nombre</label>
                         <input type="text" class="form-control" ng-model="recibido.full_name" disabled/>
                </div>
                <div class="form-group" >
                        <label>Plantel</label>
                         <input type="text" class="form-control" ng-model="recibido.extra.plantel" disabled/>
                </div>
                <div class="form-group" >
                        <label>Grupo</label>
                         <input type="text" class="form-control" ng-model="recibido.extra.grupo" disabled/>
                </div>
              

 
            </uib-tab>      
        
            <uib-tab heading="Correo">

                <div class="form-group radio" ng-repeat="correo in emails">
                    <label>
                        <input type="radio" name="correo" ng-model="recibido.email" value="{{correo}}"  required ng-init="$index==0?(recibido.email=correo):''" />{{correo}}
                    </label>
                    

                </div>                              
                  <input type="hidden" name="given_name" class="form-control" ng-model="recibido.given_name" />
                  <input type="hidden" name="full_name" class="form-control" ng-model="recibido.full_name" />
                  <input type="hidden" name="family_name" class="form-control" ng-model="recibido.family_name" />
                  <input type="hidden" name="org_path" class="form-control" ng-model="recibido.org_path" />         
                    
            </uib-tab>
        
        </uib-tabset>
      
    </div> 
    </div>
    <div class="modal-footer">
                                <button class="btn btn-flat  ui-wave ui-wave" ng-click="cancel()">Cancelar</button>
                                <button class="btn btn-flat btn-danger ui-wave ui-wave" ng-click="rechazar()" ng-disabled="!recibido.email">Rechazar</button>
                                <button class="btn btn-flat btn-primary ui-wave ui-wave" ng-click="created()" ng-disabled="!recibido.email">Crear</button>

 
    </div>
</script>

<script type="text/ng-template" id="details.html" >
     
    <div class="modal-header">
                                <h3>Detalles de la Solicitud</h3>
    </div>
    <div class="modal-body">

       
            
            
                <div class="form-group" >
                        <label>Nombre</label>
                         <input type="text" class="form-control" ng-model="solicitud.full_name" disabled/>
                </div>
                <div class="col-lg-6">
                    <div class="form-group" >
                        <label>Grupo</label>
                         <input type="text" class="form-control" ng-model="solicitud.extra.grupo" disabled/>
                    </div>
                </div>
                <div class="col-lg-6">
                <div class="form-group" >
                        <label>Matricula</label>
                         <input type="text" class="form-control" ng-model="solicitud.extra.matricula" disabled/>
                </div>
                </div>
                <div class="form-group" >
                        <label>Plantel</label>
                         <input type="text" class="form-control" ng-model="solicitud.extra.plantel" disabled/>
                </div>
                <div class="form-group" >
                        <label>Fecha de Solicitud</label>
                         <input type="text" class="form-control" ng-model="solicitud.create_on" disabled/>
                </div>
                <div class="form-group" >
                        <label>Motivo de Solicitud</label>
                         <textarea class="form-control" ng-model="solicitud.extra.motivo" disabled><textarea/>
                </div>
              
    </div>
    <div class="modal-footer">
                                <button class="btn btn-flat  ui-wave ui-wave" ng-click="cancel()">Aceptar</button>
 
    </div>
</script>
                   