(function () {
    'use strict';

    angular.module('app')
    .controller('InputCtrl',['$scope','$http','$rootScope','$sce','$uibModalInstance','users','user',InputCtrl])
    //.controller('InputSyncron',['$scope','$http','$uibModal','logger',InputSyncron])
    .controller('InputModalDownload',['$scope','$http','$uibModalInstance','logger',InputModalDownload])
    .controller('ModalUserCtrl',['$scope','$http','$uibModalInstance','logger','user',ModalUserCtrl])
    .controller('InputModalStatus',['$scope','$http','$uibModalInstance','$window','logger','recibido',InputModalStatus])
    .controller('InputModalDetails',['$scope','$http','$uibModalInstance','logger','solicitud',InputModalDetails])


    
    .controller('ModalOrgCtrl',['$scope','$http','$uibModalInstance','org_active',ModalOrgCtrl])
    .controller('InputModalUpload',['$scope','$http','$uibModalInstance','$cookies','$timeout','logger','Upload', InputModalUpload])
    .controller('IndexCtrl',['$scope','$http','$uibModal',IndexCtrl])
    .controller('InputConfigCtrl',['$scope','$http',InputConfigCtrl])
    .controller('SolicitudIndexCtrl',['$rootScope','$scope','$http','$uibModal',SolicitudIndexCtrl]);


    function InputModalDownload($scope,$http,$uibModalInstance,logger)
    {
        $scope.orgs = lista_r;
        $scope.users =[];
        $scope.org_path='';
        $scope.dispose = false;
        $scope.search_by = 'name';
        $scope.cancel = function()
        {
             $uibModalInstance.dismiss("cancel");
        }
        $scope.download = function()
        {
            var data_send = [];
            $scope.dispose = true;
            $.each(JSON.parse(angular.toJson($scope.users)),function(key,data){
              
                if(data.checked)
                {
                    data_send.push(data);
                }
                
                
                
            });
            if(data_send.length>0)
            {
                $http.post(SITE_URL+'admin/emails/download',{users:data_send}).then(function(response){
                    
                    var result = response.data;
                    $scope.status = result.status;
                    $scope.message = result.message;
                    if(result.status)
                    {
                        $uibModalInstance.close();
                        location.href = SITE_URL+'admin/emails/?org='+$scope.org_path;
                    }
                    $scope.dispose = false;
                });
            }
            else
            {
                $scope.dispose = false;
                alert('Selecciona al menos un correo para descargar');
            }
        }
        $scope.search = function(search)
        {
            $scope.dispose = true;
           
            $http.get(SITE_URL+'admin/emails/search',{params:{search:search,search_by:$scope.search_by,org_path:$scope.org_path}}).then(function(response){
                $scope.dispose = false;
                var result = response.data;
                
                $scope.status  = result.status;
                $scope.message = result.message;
                
                $scope.users = result.data;
                
                $scope.search_users= '';
            });
        }
    }
    function InputCtrl($scope,$http,$rootScope,$sce,$uibModalInstance,users,user)
    {
        $scope.status  = false;
        $scope.message = false;
        
        $scope.form = user?user:{};
        /*$scope.search = function(search)
        {
            $scope.dispose = true;
            
            $http.get(SITE_URL+'admin/emails',{params:{search:search}}).then(function(response){
                $scope.dispose = false;
                var result = response.data;
                
                $scope.users_serv = result.data;
                
                $scope.search_users_serv = '';
            });
        }*/
        $scope.cancel = function()
        {
             $uibModalInstance.dismiss("cancel");
        }
        $scope.save = function()
        {
            var url_action = user?'admin/emails/edit/'+user.id:'admin/emails/create';
            
           
            $http.post(SITE_URL+url_action,JSON.parse(angular.toJson($scope.form))).then(function(response){
                
                var result = response.data;
                
                $scope.message = result.message;
                $scope.status  = result.status;
                
                if(result.status)
                {
                    if(result.data.org_path!= $scope.org_path)
                    {
                        alert('Consulte la organizacion '+result.data.org_path+' para visualizar este  registro');
                    }
                    else
                    {
                        if(!user)
                            users.push(result.data);
                        ///Agregar nuevo registro a la organizacion visible
                    }
                    
                    $uibModalInstance.close();
                }
                
                
            });
        }
        $scope.valid_form = function () {
            return $scope.frm.$valid;
        }
    }
    function ModalOrgCtrl($scope,$http,$uibModalInstance,org_active)
    {
        $scope.orgs = lista_r;
        
        $scope.load_list = function(orgPath,reset)
        {
             location.href = SITE_URL+'admin/emails?org='+orgPath;
            
            
        }
    }
    function InputModalUpload($scope,$http,$uibModalInstance,$cookies,$timeout,logger,Upload,org_path)
    {
        $scope.users_result = [];
        $scope.action='check';
        $scope.cancel = function()
        {
             $uibModalInstance.dismiss("cancel");
        }
        $scope.upload_file = function(file)
        {
            
            $scope.dispose = true;
            
            if(!file) return false;
            
            file.upload = Upload.upload({
              url: SITE_URL+'admin/emails/upload',
              data: {  file:file,csrf_hash_name:$cookies.get(pyro.csrf_cookie_name),action:$scope.action,org_path:$scope.org_path},
            });
            
            file.upload.then(function (response) {
              var  result = response.data,
                   data   = response.data.data;
              $timeout(function () {
                  file.result = response.data;
                  $scope.dispose = false;
                  
                  if(typeof item == 'undefined' || !item)
                  {
                      //item = {id:data.id_factura,xml:'',pdf:'',total:0,messages:[]};
                  }
                  //$scope.status  = result.status;
                  //$scope.message = result.message;
                  
                  if(result.status)
                  {
                      $scope.users_result = result.data;
                  }
                 // if(type == 'xml' )
                  //{
                      //item['total']    = data.total;
                      //item['messages'] = result.message;
                  //}
                  
                  //$scope.id_factura = response.data.data.id_factura;
                  //item[type] = data.id;
                 
                 
              });
            }, function (response) {
              if (response.status > 0)
                $scope.errorMsg = response.status + ': ' + response.data;
            }, function (evt) {
              
              file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });
        }
    }
    function ModalUserCtrl($scope,$http,$uibModalInstance,logger,user)
    {
        
        
        $scope.user = user?user:{};
        
        $scope.cancel = function()
        {
             $uibModalInstance.dismiss("cancel");
        }
        $scope.update = function()
        {
            //$scope.user.splice(6,1);
            var data_send = {
                'email':$scope.user.email,
                'family_name':$scope.user.family_name,
                'given_name':$scope.user.given_name,
                'full_name':$scope.user.full_name,
                'password':$scope.password,
                'org_path':$scope.user.org_path,
                'change' : $scope.change,
                'email_altern':$scope.email_altern
                
                
            };
            $http.post(SITE_URL+'admin/emails/edit',data_send).then(function(response){
                var result = response.data,
                    message = result.message;
                    
                if(!result.status && result.message)
                {
                    alert(result.message);
                }
                if(result.status && message)
                {
                    logger.logSuccess(message);
                }
                if(!result.status && message)
                {
                    logger.logError(message);
                }
                $uibModalInstance.close();
            });
        }
    }
    function InputSyncron($scope,$http,$uibModal,logger)
    {
        /*$scope.lista_r = lista_r;
        $scope.users_serv  = [];
        $scope.users_local = users_local;
        $scope.org_active  = '';
        $scope.action_to = [];
        $scope.dispose = false;
        $scope.checked = false;
        $scope.search_users_serv ='';
        
        $scope.upload = function()
        {
                    var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalCSV.html',
                            controller: 'ModalCSVCtrl',
                  
                            resolve: {
                                org_path: function () {
                                    return $scope.org_active;
                                }
                            }
                      });
        }
        $scope.view = function(user)
        {
             var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'myModal.html',
                            controller: 'ModalCtrl',
                  
                            resolve: {
                                 user: function () {
                                    return user;
                                }
                            }
                      });
        }
        $scope.deletes=function()
        {
            
           
            var send_data = [];
            
            
            $.each($scope.action_to,function(key,row){
                
                if( key === "$$hashKey" ) {
                    return undefined;
                }
                send_data.push(row.email);
            });
           
             $http.post(SITE_URL+'admin/emails/delete',{action_to:send_data}).then(function(response){
                
                var result = response.data;
                
                if(result.status && result.message)
                {
                    logger.logSuccess(result.message);
                }  
                if(!result.status && result.message)
                {
                    logger.logSuccess(result.message);
                } 
                $scope.load_list($scope.org_active,true);
                
                
                
                
             });
        }
        $scope.download = function()
        {
            
            
            $scope.dispose = true;
            var send_data = [];
            
            
            
            $http.post(SITE_URL+'admin/emails/download',{org_path:$scope.org_active,next_page:$scope.next_page}).then(function(response){
                
                $scope.dispose=false;
                var result = response.data,
                      data = result.data;
                    
                if(result.status && result.message)
                {
                    logger.logSuccess(result.message);
                }  
                if(!result.status && result.message)
                {
                    logger.logSuccess(result.message);
                } 
                if(data){
                    $.each(data,function(index,row){
                        
                       $scope.users_local.push(row);
                    });
                }
                
            });;
            
            
             
            
            
        }
        $scope.select_all = function(){
           
            $scope.checked = !$scope.checked;
            $.each($scope.users_local,function(index,data){
                
                data.checked = $scope.checked;
              
                
            });
        };
        $scope.next_page='';
        $scope.users_serv  = [];
        $scope.search = function(search)
        {
            $scope.dispose = true;
           
            $http.get(SITE_URL+'admin/emails',{params:{search:search}}).then(function(response){
                $scope.dispose = false;
                var result = response.data;
                
                $scope.users_serv = result.data;
                
                $scope.search_users_serv = '';
            });
        }
        $scope.load_list = function(orgPath,reset)
        {
            $scope.action_to =[];
            $scope.org_active = orgPath;
            
            
            $scope.dispose = true;
            
            if(reset){
                $scope.next_page = '';
                $scope.users_serv  = [];
            }
        
            $http.get(SITE_URL+'admin/emails',{params:{org_path:orgPath,next_page:$scope.next_page}}).then(function(response){
                $scope.dispose = false;
                var result = response.data;
                if(result.status){
                    
                    if(reset)
                        $scope.users_serv = result.data;
                    else
                    {
                        $.each(result.data,function(index,row){
                            $scope.users_serv.push(row);
                            
                        });
                    }
                        
                    $scope.next_page = result.next_page;
                }
                else{
                    if(result.message)
                    {
                        alert(result.message);
                    }
                    
                }
            });
        }
        $scope.$watch('users_local',function(newValue,oldValue){
            
            
            if(newValue === oldValue) return false;
            $scope.action_to = [];
            $.each(newValue,function(index,data){
                
                if(data.checked)
                $scope.action_to.push(data);
                
            });
            
        },true);*/
       
    }
    function IndexCtrl($scope,$http,$uibModal)
    {
       
       $scope.users_local = users_local;
       $scope.lista_r = lista_r;
       $scope.org_active = '/Alumnos';
       $scope.$watch('org_active',function(n,o){
           
        
       });
       
       $scope.open_upload = function()
        {
                    var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalCSV.html',
                            controller: 'InputModalUpload',
                  
                            resolve: {
                                
                            }
                      });
        }
        $scope.open_download = function()
        {
            
             var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalDownload.html',
                            controller: 'InputModalDownload',
                  
                            resolve: {
                                 //user: function () {
                                   // return user;
                                 //}
                            }
                      });
        }
        $scope.edit = function(user)
        {
            
             var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalForm.html',
                            //controller: 'ModalUserCtrl',
                            controller:'InputCtrl',
                            resolve: {
                                 user: function () {
                                    return user;
                                 },
                                 users: function () {
                                    return $scope.users_local;
                                 }
                            }
                      });
        }
        $scope.create = function()
        {
            var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalForm.html',
                            controller: 'InputCtrl',
                  
                            resolve: {
                                 user:function()
                                 {
                                    return false;  
                                 },
                                 users: function () {
                                    return $scope.users_local;
                                 }
                            }
                      });
        }
       $scope.open_orgs = function()
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalOrgs.html',
                            controller: 'ModalOrgCtrl',
                  
                            resolve: {
                                
                                org_active: function () {
                                    return $scope.org_active;
                                }
                            }
                      });
       }   
    }
    function InputConfigCtrl($scope,$http)
    {
        $scope.modules    = modules?modules:[];
        $scope.table = {


        };

        $scope.select_index = function(module)
        {
            //console.log($scope.modules);
            //var index = $scope.modules.indexOf({slug:module.slug});
            //console.log(index);

            $.each($scope.modules,function(index,value){

                if(value.slug == module.slug)
                {
                    $scope.index_table = index;
                    return false;
                }

            });
        }
        $scope.$watch('table',function(newValue,oldValue){

            //console.log(newValue);
            //console.log(oldValue);


            if(!newValue){
                $scope.rows_left = []

                return false;
            }


            $.each($scope.modules,function(index,value){

                if(newValue.slug == value.slug)
                {
                    $scope.index_table = index;
                    $scope.table       = $scope.modules[index];
                }

            });
            $scope.rows_left = newValue?newValue.rows:[];
        });



    }

    function SolicitudIndexCtrl($rootScope,$scope,$http,$uibModal)
    {
         $scope.recibidos    = recibidos?recibidos:[];
         $scope.rechazados   = rechazados?rechazados:[];
         $scope.validados    = validados?validados:[];


        $scope.changeStatus = function(recibido)
        {
           var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'ModalStatus.html',
                            //controller: 'ModalUserCtrl',
                            controller:'InputModalStatus',
                            resolve: {
                                 recibido: function () {
                                    return recibido;
                                 }
                            }
                      });

        }

        $scope.details = function(solicitud)
        {
           var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'details.html',
                            //controller: 'ModalUserCtrl',
                            controller:'InputModalDetails',
                            resolve: {
                                 solicitud: function () {
                                    return solicitud;
                                 }
                            }
                      });

        }

    }

    function InputModalStatus($scope,$http,$uibModalInstance,$window,logger,recibido)
    {
        $scope.status_1  = false;
        $scope.message_1 = false;
        $scope.recibido = recibido?recibido:[];


        $scope.form={};


        var data_send = {
                'family_name':$scope.recibido.family_name,
                'given_name':$scope.recibido.given_name,
            };
            //console.log(data_send);
            
            $http.post(SITE_URL+'admin/emails/solicitudes/validar',data_send).then(function(response){
                var result = response.data;
                   $scope.message_1 = result.message;
                   $scope.status_1  = result.status;

                        $scope.emails = result.data; 
                        //$scope.selected = {email: $scope.email};
                        console.log($scope.emails);
                        if(result.status = false)
                        {
                            alert(result.message);
                        }   
                }); 
                 
               

        
        $scope.cancel = function()
        {
             $uibModalInstance.dismiss("cancel");
        }


         function download (id)
        {
            
          $window.open(SITE_URL+'admin/emails/acuse/?id='+id); 

        }
        function validar()
            {
                var data_send = {
                'family_name':$scope.recibido.family_name,
                //'full_name':$scope.recibido.full_name,
                'given_name':$scope.recibido.given_name,
               // 'org_path':$scope.recibido.org_path,
                 };

            console.log(data_send);
            
            $http.post(SITE_URL+'admin/emails/solicitudes/validar',data_send).then(function(response){
                var result = response.data,
                    message = result.message;

                    recibido.push(result.data);
                    
                    console.log(recibido);                 
               
                //$uibModalInstance.close();
            }); 

            }

        $scope.rechazar = function()
        {
            var index =  $scope.recibido.index;
             var data_send = {
                'id_solicitud':$scope.recibido.id,
                 };
            $http.post(SITE_URL+'admin/emails/solicitudes/rechazar',data_send).then(function(response){
                var result = response.data;
                   $scope.message = result.message;
                   $scope.status  = result.status;

                if(result.status == true)
                {
                    var data_push = {
                        'extra':{'motivo':$scope.recibido.extra.motivo,'matricula':$scope.recibido.extra.matricula,'plantel':$scope.recibido.extra.plantel},
                        'full_name':$scope.recibido.full_name,
                        'id':$scope.recibido.id
                    }
                    
                                       
                     if(rechazados.length>0)
                     {
                         rechazados.push(data_push);
                         console.log(rechazados);
                         recibidos.splice(index,1);
                     }
                     else{
                        location.reload(); 
                     }
                                                 
                   
                  $uibModalInstance.close();
                }
               
                
            }); 
        }
        $scope.created = function()
        {       
            $scope.status  = false;
            $scope.message = false;

            var index =  $scope.recibido.index;
            var data_send = {
                'email':$scope.recibido.email,
                'org_path':$scope.recibido.org_path,
                'given_name':$scope.recibido.given_name,
                'family_name':$scope.recibido.family_name,
                'full_name':$scope.recibido.full_name,
                'module_id':$scope.recibido.module_id,
                'id_solicitud':$scope.recibido.id,
                 };

            $http.post(SITE_URL+'admin/emails/solicitudes/created',data_send).then(function(response){
                var result = response.data;
                   $scope.message = result.message;
                   $scope.status  = result.status;

                if(result.status == true)
                {
                    var data_push = {
                        'extra':{'motivo':$scope.recibido.extra.motivo,'matricula':$scope.recibido.extra.matricula,'plantel':$scope.recibido.extra.plantel},
                        'full_name':$scope.recibido.full_name,
                        'id':$scope.recibido.id
                    }

                     download(result.data.email);
                     if(validados.length>0)
                     {
                         validados.push(data_push);
                         console.log(validados);
                         recibidos.splice(index,1);
                     }
                     else
                     {
                        location.reload(); 
                     }
                    
                                       
                   
                  $uibModalInstance.close();
                }
               
                
            }); 
          console.log(data_send);
        }
        

    }

    function InputModalDetails($scope,$http,$uibModalInstance,logger,solicitud)
    {
        $scope.status_1  = false;
        $scope.message_1 = false;
        $scope.solicitud = solicitud;



        
        $scope.cancel = function()
        {
             $uibModalInstance.dismiss("cancel");
        }

       

    }

})();

