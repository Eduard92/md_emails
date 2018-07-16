<style type="text/css">

.typeahead, .tt-query, .tt-hint 
{
    width: 568px !important;
    max-width: 568px;
    
}
.alert-danger {
    min-height: 0px !important;
}
</style>

<section>
    <div class="container"> 

         <header><h2>Solicitud de Chromebooks</h2></header>
             {{ theme:partial name="notices" }}
        
        <?php if(!$director){ ?>
            <div class="alert alert-danger">
                <?=lang('email:error_access')?>
            </div>
        <?php }

        else{?>
              <?php if($status == 'enviados'):?>
                 <button class="btn btn-success pull-right" data-toggle="modal" data-target="#modalForm">
                Nueva solicitud </button>
              <?php endif;?>
             <br><br>
         


            <ul class="nav nav-tabs">
                <li class="<?=$status=='enviados'?'active':''?>"><a href="<?=base_url('emails/solicitudes/?tab=enviados')?>">Enviado</a></li>
                <li class="<?=$status=='validados'?'active':''?>"><a href="<?=base_url('emails/solicitudes/?tab=validados')?>">Validados</a></li>
                <li class="<?=$status=='rechazados'?'active':''?>"><a href="<?=base_url('emails/solicitudes/?tab=rechazados')?>">Rechazados</a></li>
            </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active">
              <?php if(empty($solicitudes)==false){?>
               <?=form_open(null,' name="index_form" id="index_form" onsubmit="this.reset();');?>
              <p class="text-right">Total registros: <?php echo $total;?></p>
               <table class="table">
                  <thead>
                    <tr>
                        <?php if($status=='enviados'):?>
                          <th><input type="checkbox" id="selectAll" name="selectAll" onclick="marcar(this); "/></th>
                        <?php endif;?>
                        <th width="">Nombre</th>
                        <th width="">Apellidos</th>
                        <!--th width="">Solicitado</th-->
                          <th width="">Grupo</th>
                          <th width="">Matricula</th>
                          <th width="">Motivo de Solicitud</th>
                        
                                                              
                    </tr>
                  </thead>
                  <tbody id="bind-enviados">
                    <?php foreach($solicitudes as $solicitud):?>
                    <tr class="<?=$solicitud->estatus=='enviado'?'':($solicitud->estatus=='validado'?'success':'danger')?>">
                        <td><?=$solicitud->given_name;?></td>
                        <td><?=$solicitud->family_name;?></td>
                        <td><?=$solicitud->grupo;?></td>
                        <td><?=$solicitud->matricula;?></td>
                        <td><?=$solicitud->motivo;?></td>
                    </tr> 
                  <?php endforeach;?>
                  </tbody>            
               </table>
               <?php if($status=='enviados'):?>
                        <button type="button" name="btPrint" class="btn btn-color-grey-light" id="print_modal" disabled> Imprimir</button>
               <?php endif;?> 
               <?=form_close()?>  
               <?php }
               else{?>
                    <div class="alert alert-info">
                      <?=lang('email:not_found_solicitudes')?>
                    </div>
                 <?php }?>        
            </div>
            
            <p><?=$pagination['links']?></p>
         </div>
        <?php }?>
     </div>


<!-- Modal Solicitud-->
<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="title">Solicitud de Email</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
               <div id="notices-modal"></div>
                <!--form role="form"-->
                  <?=form_open(null,'id="formSolicitud"');?>
                    <div class="form-group">
                        <label>Alumno *</label><br>
                        <?=form_input('alumno',null,'class="form-control typeahead text-uppercase" id="text_auto" required ')?>
                    </div>
                    <div class="form-group">
                         <label>Plantel</label>
                         <?=form_input('plantel',null,'class="form-control" id="plantel" readonly')?>
                    </div>
                    <div class="form-group">
                        <label>Matricula</label>
                        <?=form_input('matricula',null,'class="form-control" id="matricula" text-uppercase" readonly')?>
                    </div>
                    <div class="form-group">
                          <label>Grupo</label>
                         <?=form_input('grupo',null,'class="form-control" id="grupo" readonly')?>
                    </div>
                    <div class="form-group" >
                        <label>Motivo *</label>
                          <?php $data= array('name'=>'motivo','id'=>'motivo',
                          'class'=>'form-control text-uppercase' ,'rows'=>'3',
                          'placeholder'=>'Anota el motivo aquí') ?>
                          <?=form_textarea($data)?>
                    </div>
                    <input type="hidden" value="" id="id_director" name="id_director" />
                    <input type="hidden" value="" id="id_alumno" name="id_alumno" />
                    <input type="hidden" value="" id="given_name" name="given_name" />
                    <input type="hidden" value="" id="full_name" name="full_name" />
                    <input type="hidden" value="" id="family_name" name="family_name" />
                    <input type="hidden" value="" id="org_path" name="org_path">
                  <?=form_close()?>
                <!--/form-->
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-color-grey-light "id="closeSolicitud" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary " onclick="submitSolicitud();">Solicitar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIN Modal Solicitud-->

<!-- Modal Imprimir-->

  <div class="modal fade" id="ModalPrint" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Datos Extra de Oficio</h3>
      </div>
      <?=form_open('solicitudes/download','id="formPrint" name="formPrint" method="post" target="_blank" onsubmit="this.reset(); ');?>
        <div class="modal-body">
                <div class="form-group">
                    <label>N° Oficio *</label><br>
                    <?=form_input('oficio','','class="form-control text-uppercase" id="oficio" required')?>
                </div>
                <div class="form-group">
                    <label>Semestre *</label><br>
                    <?=form_input('semestre','','class="form-control text-uppercase" id="semestre" required')?>
                </div>
                <div class="form-group">
                    <label>Subdirectsubdirecor *</label><br>
                    <?=form_input('subdirec','','class="form-control" id="subdirec" required')?>
                </div>
                <div class="form-group">
                    <label>Responsanble Control Escolar *</label><br>
                    <?=form_input('control_escolar','','class="form-control" id="control_escolar" required ')?>
                </div>
                <input type="hidden" value="" id="ids" name="ids">
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-color-grey-light" id="closePrint" data-dismiss="modal">Cerrar</button>
          <!--button type="button" class="btn btn-primary " onclick="print(ids)">Imprimir</button-->
           <button type="submit" id="submit" class="btn btn-default" '>Imprimir</button>
<?=form_close();?>
        </div>
      </div>
      
    </div>
  </div>
  <!-- FIN Modal Imprimir-->


</section>

