<style type="text/css">

.typeahead, .tt-query, .tt-hint 
{
    width: 540px !important;
    max-width: 540px;
    
}
.alert-danger {
    min-height: 0px !important;
}
</style>

<section>
    <div class="container">
        <header><h2>Solicitud de Email</h2></header>
            {{ theme:partial name="notices" }}

                <?php if(validation_errors()){?>
                    <div class="alert alert-danger"><?=validation_errors()?></div>
                <?php }?>

        <div class="row">
            <?=form_open(null,'id="form"');?>
               <div class="col-md-6">

                        <div class="form-group">
                                <label>Alumno *</label><br>
                                <?=form_input('alumno',$solicitud->alumno,'class="form-control typeahead text-uppercase" id="text_auto"  '.($this->method=='details'?'disabled':''))?>
                        </div>

                        <div class="form-group">
                              <label>Plantel</label>
                              <?=form_input('plantel',$solicitud->plantel,'class="form-control" disabled')?>
                        </div>

                        


               </div>
               <div class="col-md-6">
                         <div class="form-group">
                              <label>Matricula</label>
                              <?=form_input('matricula',$solicitud->matricula,'class="form-control text-uppercase" disabled')?>
                         </div>
                         <div class="form-group">
                              <label>Grupo</label>
                              <?=form_input('grupo',$solicitud->grupo,'class="form-control" disabled')?>
                        </div>


                            <?php if($this->method=='create'):?>
                            <input type="hidden" value="" name="id_director" />
                            <input type="hidden" value="" name="id_alumno" />
                            <input type="hidden" value="" name="given_name" />
                            <input type="hidden" value="" name="full_name" />
                            <input type="hidden" value="" name="family_name" />
                            <input type="hidden" value="" name="org_path">
                            <?php endif;?>
               </div>
               <hr />
               <div class="col-md-12">
              <div class="buttons">
                <a href="<?=base_url('chromebooks/load')?>" class="btn btn-color-grey-light"><i class="fa fa-reply"></i> Regresar</a>
                <?php if($this->method != 'details'){?>
                   <button type="submit" class="btn btn-success" value="save" id="btn-save" confirm-action>Guardar</button>
                <?php }?>
              </div>
            </div>
            <?=form_close()?>
        </div>
    </div>
</section>