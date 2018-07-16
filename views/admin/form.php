<section>
    <?php echo form_open_multipart(uri_string(), ' id="page-form"  data-mode="'.$this->method.'"'); ?>



	    <div class="ui-tab-container ui-tab-horizontal">
        
        
        	<uib-tabset justified="false" class="ui-tab">
        	        <uib-tab heading="Personal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre(s)</label>
                                    <?=form_input('name',null,'class="form-control" ng-model="name"')?>
                                </div>
                                <div class="form-group">
                                    <label>Apellido materno</label>
                                    <?=form_input('last_name1',null,'class="form-control" ng-model="last_name2"')?>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <div class="radio">
                                        <label><input type="radio" />{{name | lowercase}}.{{last_name1 | lowercase}}@cobacam.edu.mx</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" />{{name | lowercase}}.{{last_name1 | lowercase}}2@cobacam.edu.mx</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" />{{name | lowercase}}.{{last_name1 | lowercase}}.{{last_name2| lowercase}}@cobacam.edu.mx</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" />{{name | lowercase}}.{{last_name1 | lowercase}}.{{last_name2| lowercase}}@cobacam.edu.mx</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" />{{name | lowercase}}.{{last_name1 | lowercase}}.{{last_name2| lowercase}}2@cobacam.edu.mx</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Apellido paterno</label>
                                    <?=form_input('last_name2',null,'class="form-control" ng-model="last_name1"')?>
                                </div>
                                
                            </div>
                        </div>
                    </uib-tab>
                    <uib-tab heading="Via CSV">
                    </uib-tab>
            </uib-tabset>
         </div>
         <div class="divider"></div>
         <div class="buttons clearfix">
        	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))) ?>
          </div>
    <?php echo form_close();?> 

</section>