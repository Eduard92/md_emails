<section ng-controller="InputCtrl">
   <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Buscar correo electrónico" ng-model="search_users" />
                                            <div class="input-group-addon">
                                                <a href="#" ng-click="search(search_users_serv)"><i class="fa fa-google"></i></a>
                                            </div>
                                           
   </div>         
   
    <div ng-if="users.length>0">
                                
                                <hr />
                                
                                
                                <ul class="list-unstyled list-users">
                                    <li ng-repeat="user in users|filter:search_users">
                                        <a href="javascript:;" ng-click="view(user)">
                                        {{user.email}}
                                        <br />
                                        <span class="text-muted">{{user.full_name}}</span>
                                        
                                        </a> 
                                    </li>
                                </ul>
                                
                                <a href="#" ng-click="load_list(org_active)" ng-if="next_page" class="btn btn-default">Cargar más datos</a>
    </div>
</section>
                            
                                <!--div class="form-group">
                                    <label>Uso:</label>
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="check"/>Verificar</label>
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="cron"/>Asignar ChromeBook</label>
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="add"/>Agregar</label>
                                   
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="edit"/>Actualizar</label>
                                    <label class="radio-inline"><input type="radio" name="uso" ng-model="action" value="undel"/>Restaurar</label>
                                    
                                    
                                    
                                    
                                    
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
                                <div class="divider">
                                    <input type="text" class="form-control" ng-model="search_result" />
                                </div>
                                <div class="extra">Total registros: {{users_result.length}}</div>
                                <div class="well" data-slim-scroll data-scroll-height="200px">
                                    
                                    <ul class="list-unstyled list-users-li">
                                        <li ng-repeat="user in users_result | filter:search_result">
                                        {{user.email}}  <br><span class="text-muted">{{user.full_name}}</span>
                                        <span ng-if="user.org_path"> | {{user.org_path}}</span>
                                        
                                        
                                        <i class="fa fa-check text-success" ng-if="user.status"></i> <i class="fa {{user.icon}} text-danger" ng-if="!user.status" title="{{user.message}}"></i></li>
                                    </ul>
                                </div-->
                                
                           