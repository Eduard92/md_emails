<style type="text/css">
.side-folder li{
    overflow: hidden;
    margin-left: 12px;
}
.side-folder li span{
    
    float: left;
    width: 14px;
    display: block;
    height: 14px;
    color:#8BC34A;
    text-align: center;
   
}
.side-folder li a{
    padding-left: 14px;
    display: inline-block;
    color:#3D4051;
    text-decoration: none;
    
}
.side-folder li a:hover{
    color:#8BC34A;
}
.side-folder{
   
    /*padding:6px;
    height: 300px;
    min-height: 300px;
     max-height: 300px*/
}
.list-users li{
    /*border: 1px solid #efefef;*/
    margin-bottom: 2px;
    
}
.list-users li a{
    display: block;
    text-decoration: none;
    padding: 4px 6px;
    
    
}
.list-users li span{
    margin-left: 10px;
}
.list-users li input{
    float: left;
    margin-bottom: 10px;
}
.list-users li a:hover{
background:  #efefef;
}
.list-users-li li{
    background: #EEEEEE;
    
    padding:4px 6px;
    margin-bottom: 2px;

} 
</style>
<section ng-controller="IndexCtrl">
    <div class="lead text-success"><?=lang('email:title')?></div>
    
    <?php if(!group_has_role('emails','admin_organizaciones') && !$orgs_local) {?>
    <div class="alert alert-info text-center"><?=sprintf(lang('email:org_notfound'),Settings::get('contact_email'),Settings::get('contact_email'))?></div>
    <?php }else{?>
    
    
            <?php  echo form_open('admin/emails/action');?>
            
             
                        <div class="row">
                              <div class="col-md-7">
                            
                                <?php if(group_has_role('emails','admin_organizaciones')): ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" ng-model="filter_search" placeholder="Buscar correo electrónico <?=$this->input->get('org')?>" />
                                   
                                    <div class="input-group-addon">
                                        <a href="#" ng-click="open_orgs()" >Organización</a>
                                       
                                    </div>
                                    
                                </div>
                                <?php else: ?>
                                <input type="text" class="form-control" ng-model="filter_search" placeholder="Buscar correo electrónico <?=$this->input->get('org')?>" />
                                <?php endif;?>
                              </div>
                              <div class="col-md-5">
                                <?php if(group_has_role('emails','syncronize')) {?>
                                <button type="button" class="btn" ng-click="open_download()"><i class="fa fa-download"></i> Descargar</button>
                                <button type="button" class="btn" ng-click="open_upload()"><i class="fa fa-upload"></i> Subir</button>
                                <?php }?>
                                 <?php if(group_has_role('emails','create')) {?>
                                 <button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus"></i> Agregar correo</button>
                                <?php }?>
                              </div>
                        </div>
                            
                            <hr />
                            
                           
                            <p class="text-right">Total registros: {{users_local.length }}</p>
                           
                            <table class="table" ng-if="users_local">
                                <thead>
                                    <tr >
                                        <th width="2%"><input type="checkbox"  ng-click="select_all()" /></th>
                                        <th>Email</th>
                                        <th>Given name</th>
                                        <th>Family name</th>
                                        <th>Full name</th>
                                        <th width="14%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="user in users_local   |filter:filter_search ">
                                        <td><input type="checkbox"  name="action_to[]" ng-model="user.checked"  value="{{user.email}}" ng-model="user.checked" /></td>
                                        <td>{{user.email}}<br /><span class="text-muted">{{user.org_path}}</span></td>
                                        <td>{{user.given_name}}</td>
                                        <td>{{user.family_name}}</td>
                                        <td>{{user.full_name}}</td>
                                        <td class="text-center">
                                            <?php if(group_has_role('emails','delete')) {?>
                                            <a href="<?=base_url('admin/emails/delete/?id={{user.id}}')?>" class="btn btn-danger" confirm-action><i class="fa fa-trash"></i></a>
                                            <?php }?>
                                             <?php if(group_has_role('emails','edit')) {?>
                                            <a href="#" class="btn btn-success" ng-click="edit(user)"><i class="fa fa-pencil"></i></a>
                                            <?php }?>
                                            
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="alert alert-info text-center" ng-if="users_local.length==0"><?=lang('global:not_found')?></div>
                          
                        
                        <div class="panel-footer">
                            <input type="hidden" name="org" value="<?=$this->input->get('org')?>" />
                              <?php if(group_has_role('emails','delete')) {?>
                            
                              <button type="submit" value="delete" class="btn btn-danger" confirm-action ng-disabled="!org_active || action_to.length==0" name="btnAction">Eliminar seleccionados</button>
                             <?php }?>
                        </div>
            
            
             <?php echo form_close();?>
      
    <?php }?>
</section>
 <?php if(group_has_role('emails','syncronize')) {?>
<script type="text/ng-template" id="modalDownload.html">
<?php echo form_open(); ?>
    <div class="modal-header" ng-init="org_path='<?=$this->input->get('org')?>'">
                                <h3><?php echo lang('email:download') ?>  </h3>
    </div>
    <div class="modal-body">
         
         <div class="form-group">
             <label>Buscar por en </label>
             <div>
             <label class="radio-inline"><input type="radio"  ng-model="search_by" value="name"/>Nombre</label>
             <label class="radio-inline"><input type="radio"  ng-model="search_by" value="email"/>Correo electrónico</label>
             
             </div>
         </div>
         <div class="input-group">
            
                                            <input type="text" class="form-control" placeholder="Buscar por nombre en {{org_path}}" ng-model="search_users" />
                                            <div class="input-group-addon">
                                                <a href="#" ng-click="search(search_users)">Buscar en google</a>
                                            </div>
                                           
        </div>         
        <div class="alert" ng-if="message" ng-class="{'alert-danger':!status,'alert-success':status}">{{message}}</div>
        <p ng-if="dispose">Espere...</p>
        <div ng-if="users.length>0">
                                
                                <hr />
                                <p class="text-right">Total registros: {{users.length}}</p>
                                
                                <ul class="list-unstyled list-users">
                                    <li ng-repeat="user in users|filter:search_users">
                                        <input type="checkbox" ng-model="user.checked" value="1"  />
                                        <span>{{user.email}} - {{user.org_path}}</span>
                                        <br />
                                        <span class="text-muted">{{user.full_name}}</span>
                                        
                                        
                                    </li>
                                </ul>
                                
                                <a href="#" ng-click="load_list(org_active)" ng-if="next_page" class="btn btn-default">Cargar más datos</a>
         </div>
    </div>
     <div class="modal-footer">
                                <button ui-wave class="btn btn-flat" type="button"  ng-click="cancel()">Cancelar</button>
                                <button ui-wave class="btn btn-flat btn-primary"  ng-disabled="dispose" type="button" ng-if="users.length>0"  ng-click="download()">Descargar</button>
    </div>
<?php echo form_close();?>
</script>
<?php }?>
<script type="text/ng-template" id="items_renderer.html">
                
                    <span>{{org.orgs}}</span>
                
</script>
 <script type="text/ng-template" id="items_renderer.html">
    <span ng-click="org.collapsed=!org.collapsed" > <i class="fa fa-plus-square" ng-if="org.orgs"></i> </span>
    <a class="name" href="#" ng-click="load_list(org.orgUnithPath,true)" >
    <i class="fa fa-folder-o"></i> 
    {{org.name}}
    
    </a>
    <ul class="list-unstyled"   ng-class="{hidden: org.collapsed}">
                    <li  ng-repeat="org in org.orgs"  ng-include="'items_renderer.html'">
                    </li>
    </ul>
 </script>
 
 <script type="text/ng-template" id="modalOrgs.html">
     <?php echo form_open();?>
                            <div class="modal-header">
                                <h3><?php echo lang('email:orgs') ?> </h3>
                            </div>
                            <div class="modal-body">
                                 <div class="side-folder">
                                    <ul class="list-unstyled">
                                      
                                      <li ng-repeat="org in orgs" ng-include="'items_renderer.html'">
                                         
                                      </li>
                                   </ul>
                                </div>
                            </div>
    <?php echo form_close();?>
  </script>                         
  
  <script type="text/ng-template" id="modalCSV.html">
  <?php echo form_open();?>
                            <div class="modal-header" ng-init="org_path='<?=$this->input->get('org')?>'">
                                <h3><?php echo lang('email:csv') ?> </h3>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Uso:</label>
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="check"/>Verificar</label>
                                    <!--label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="cron"/>Asignar ChromeBook</label-->
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="add"/>Agregar</label>
                                   
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="edit"/>Actualizar</label>
                                    <!--label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="undel"/>Restaurar</label-->
                                    
                                    
                                    
                                    
                                    
                                </div>
                                <hr/>
                                
                                <div class="form-group">
                                     <div class="form-group">
                                        <label>Buscar archivo</label>
                                        <input  type="file" accept=".csv" ng-disabled="dispose||!action" ngf-select="upload_file(file_csv)" ng-model="file_csv" name="file_csv" ngf-model-invalid="errorFile"/>
                                        <md-progress-linear md-mode="determinate" ng-show="dispose" value="{{file_csv.progress}}"></md-progress-linear>
                                        <div class="alert" ng-class="{'alert-danger':status==false}" ng-if="message" ng-bind-html="message"></div>
                                    
                                    </div>
                                </div>
                                <div class="divider row" ng-init="search_result.active='1'">
                                    <div class="col-xs-6"><input type="text" class="form-control" ng-model="search_result.email" /></div>
                                    <div class="col-xs-6">
                                        <select ng-model="search_result.icon" class="form-control col-xs-6">
                                            <option value="">Todos<option>
                                            <option value="fa-user" ng-if="action=='check'">Duplicados</option>
                                            <option value="fa-check" ng-if="action=='check'">Encontrados</option>
                                            <option value="fa-check" ng-if="action=='add'">Correctos</option>
                                            <option value="fa-ban" ng-if="action=='check'">No encontrado</option>
                                            <option value="fa-warning" ng-if="action=='add'">Errores</option>
                                        </select>
                                    </div>
                                    <input type="hidden" ng-model="search_result.active" value="1"/>
                                    
                                </div>
                                <div class="extra">Total registros: {{(users_result|filter:search_result).length}}</div>
                                <div class="well" data-slim-scroll data-scroll-height="200px">
                                    
                                    <ul class="list-unstyled list-users-li">
                                        <li ng-repeat="user in users_result | filter:search_result" ng-if="user.active==1">
                                        <strong>{{$index+1}}.-</strong> {{user.email}}  <br><span class="text-muted">{{user.full_name}}</span>
                                        <span ng-if="user.org_path"> | {{user.org_path}}</span>
                                        
                                        
                                        <i class="fa fa-check text-success" ng-if="user.status"></i> 
                                        <i class="fa {{user.icon}} text-danger" ng-if="!user.status" title="{{user.message}}"></i>
                                        </li>
                                    </ul>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" ui-wave class="btn btn-flat" ng-click="cancel()">Cancelar</button>
                            </div>
   <?php echo form_close();?>
  </script>
  <script type="text/ng-template" id="modalForm.html">
     <?php echo form_open('','name="frm" id="frm"'); ?>
        <div class="modal-header" ng-init="org_path='<?=$this->input->get('org')?>'">
                                <h4><?php echo lang('email:create') ?> </h4>
        </div>
        <div class="modal-body">
            <div class="ui-tab-container ui-tab-horizontal">
            
            
            	<uib-tabset justified="false" class="ui-tab">
            	        <uib-tab heading="General">
                            <div class="alert" ng-bind-html="message" ng-if="message" ng-class="{'alert-danger':!status,'alert-success':status}"></div>
                                
                                <input type="hidden" ng-model="form.id" value="{{form.id}}"/>
                                <div class="form-group" >
                                             <label>* OrgPath</label>
                                            
                                             <?php if(group_has_role('emails','admin_organizaciones')): ?>
                                                <?php echo form_dropdown('org_path',array(''=>'Seleccionar','/'=>'/')+$orgs_local,null,'ng-model="form.org_path" class="form-control" required');?>
                                             <?php else:?>
                                                <input type="text" class="form-control" ng-model="form.org_path"/>
                                             <?php endif;?>
                                             <div ng-messages="frm.org_path.$error"  role="alert" ng-if="frm.org_path.$dirty">
                                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                                             </div>
                                 </div>
                                 <div class="row">
                                         <div class="form-group col-md-6">
                                            <label>* Given name</label>
                                            <input type="text" class="form-control" name="given_name" ng-model="form.given_name" ng-blur="form.full_name=form.given_name+' '+form.family_name"  ng-change="form.given_name=(form.given_name|uppercase) " required/>
                                            <div ng-messages="frm.given_name.$error"  role="alert" ng-if="frm.given_name.$dirty">
                                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                                             </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>* Family name</label>
                                            <input type="text" class="form-control" name="family_name" ng-model="form.family_name" ng-blur="form.full_name=form.given_name+' '+form.family_name" ng-change="form.family_name=(form.family_name|uppercase) " required/>
                                            <div ng-messages="frm.family_name.$error"  role="alert" ng-if="frm.family_name.$dirty">
                                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                                             </div>
                                        </div>
                                       
                                 </div>
                                 <div class="form-group ">
                                            <label>* Full name</label>
                                            <input type="text" class="form-control" name="full_name" ng-model="form.full_name"  ng-change="form.full_name=(form.full_name|uppercase) " required/>
                                            <div ng-messages="frm.full_name.$error"  role="alert" ng-if="frm.full_name.$dirty">
                                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                                             </div>
                                 </div>
                                 <div class="form-group ">
                                            <label>* Correo electrónico</label>
                                            <div class="input-group" ng-if="!form.id">
                                                <input type="text" class="form-control" name="email" ng-model="form.email"  required />
                                                <span class="input-group-addon">@cobacam.edu.mx</span>
                                            </div>
                                            <input type="text" class="form-control" name="email" ng-model="form.email"  ng-if="form.id" disabled />
                                            <div ng-messages="frm.given_name.$error"  role="alert" ng-if="frm.given_name.$dirty">
                                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                                             </div>
                                 </div> 
                                 <div class="form-group" ng-if="form.id">
                                            <label>Password</label>
                                            <input type="text" class="form-control" ng-model="form.password"/>
                                            <p class="help-block">Dejar en blanco si  desea conservar la contraseña</p>
                                 </div>
                                        
                                 <div class="form-group" ng-if="form.id">
                                            <label><input type="checkbox" ng-disabled="!form.password" ng-model="form.change" value="1"/> Pedir cambio de contraseña</label>
                                            
                                            
                                 </div>
                                        
                                 <div class="form-group" ng-if="form.id">
                                            <label>Email alterno</label>
                                            <input type="text" class="form-control" ng-disabled="!form.password" ng-model="form.email_altern"/>
                                            <p class="help-block">Envía la contraseña a un correo alterno</p>
                                 </div>
                            </uib-tab>
                            <uib-tab heading="Extra">
                                   <div class="form-group">
                                        <label>Tabla</label>
                                        
                                        <select class="form-control" ng-model="form.table">
                                              <option value="">Ninguno</option>
                                              <option value="alumnos">Alumnos</option>
                                              <option value="empleados">Empleados</option>
                                        </select>
                                   </div>
                                   <div class="form-group">
                                        <label>Valor</label>
                                        <input type="text" value="" class="form-control" ng-model="form.table_id"/>
                                   </div>
                                   
                                    <div class="form-group">
                                        <label>Datos</label>
                                        <textarea class="form-control" ng-model="form.data"></textarea>
                                   </div>
                            </uib-tab>
                      </uib-tabset>
                </div>         
         </div>
         <div class="modal-footer">
                                <button ui-wave type="button" class="btn btn-flat" ng-click="cancel()">Cancelar</button>
                                <button ui-wave type="button" class="btn btn-flat btn-primary"  ng-disabled="!valid_form()" ng-click="save()">Guardar</button>
         </div>
     <?php echo form_close(); ?>
  </script>
 
                            