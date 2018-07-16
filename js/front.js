 $(document).ready(function(){

    if(enviados.length > 0)
    {
      //console.log('cargado');
      load(enviados);
      document.getElementById("formPrint").reset();
      document.getElementById("index_form").reset();
      document.getElementById("print_modal").disabled = true;
    }
  
    display_autocomplete = '<div><strong>{{full_name}}</strong> <br/> GRUPO: {{grupo}}</div>';
   
    var input_text = $('.typeahead');

    var list_autocomplete= new Bloodhound({
           datumTokenizer: Bloodhound.tokenizers.obj.whitespace('full_name'),
           queryTokenizer: Bloodhound.tokenizers.whitespace,        
           local: data?data:[]
      });
    

    if(display_autocomplete){ 
        $('#text_auto').typeahead(null,
         {
            name: 'list_autocomplete',
            display: 'full_name',
            source: list_autocomplete,
            templates: {
                empty: [
                  '<div class="empty-message alert">',
                    text_empty,
                  '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile(display_autocomplete)
              }
          });
    
        input_text.bind('typeahead:select',function(e,suggestion){
           // $('button[type="submit"]').attr('disabled',true);
            $('input[name="grupo"]').val(suggestion.grupo);
            $('input[name="alumno"]').val(suggestion.alumno);
            $('input[name="plantel"]').val(suggestion.plantel);
            $('input[name="matricula"]').val(suggestion.matricula);

            $('input[name="id_alumno"]').val(suggestion.id_alumno);
            $('input[name="id_director"]').val(suggestion.id_director);
            $('input[name="full_name"]').val(suggestion.full_name);
            $('input[name="given_name"]').val(suggestion.given_name);
            $('input[name="family_name"]').val(suggestion.family_name);
            $('input[name="org_path"]').val(suggestion.org_path);
           // $('#form').submit();    
           });
      }

      $("#closeSolicitud").on("click",function(event){ 
            event.preventDefault(); 
            $('#notices-modal').html('<div></div>');
            $('#formSolicitud').trigger("reset"); 
            
       });

      $("#closePrint").on("click",function(event){ 
            event.preventDefault(); 
            $('#formPrint').trigger("reset"); 
            $('#index_form').trigger("reset");
            document.getElementById("print_modal").disabled = true;
            document.getElementById("selectAll").removeAttribute("checked");
       });
 
      $('#print_modal').click(function() {

          ids = $('[name="item[]"]').serializeArray();
          $('#ids').val(JSON.stringify(ids));

          //console.log(ids);
          $('#ModalPrint').modal('show');

        });

      $( "#formPrint" ).submit(function( event ) {
           // console.log('enviado');
            $('#ModalPrint').modal('hide');
            var enviado = true;   
            setTimeout('document.formPrint.reset()');
            //setTimeout('document.index.reset()');    
            $('input:checkbox').removeAttr('checked');

                 if(document.getElementById("print_modal").disabled == false)
                 {
                      document.getElementById("print_modal").disabled = true;
                       document.getElementById("selectAll").removeAttribute("checked"); 
                  }       
        });



});

      function marcar(source) 
        {
          checkboxes=document.getElementsByTagName('input'); 
            for(i=0;i<checkboxes.length;i++) 
            {
              if(checkboxes[i].type == "checkbox") 
                 checkboxes[i].checked=source.checked;               
            }
          
            if(source.checked==true){
                 document.getElementById("print_modal").disabled = false;
              }else{
                 document.getElementById("print_modal").disabled = true;
              }
          }

       function submitSolicitud()
       {
          var data = new FormData();
          var id_alumno = $('#id_alumno').val();
          var id_director = $('#id_director').val();
          var given_name = $('#given_name').val();
          var family_name = $('#family_name').val();
          var full_name = $('#full_name').val();
          var org_path = $('#org_path').val();
          var grupo = $('#grupo').val();
          var plantel = $('#plantel').val();
          var matricula = $('#matricula').val();
          var motivo = $('#motivo').val();

          data.append('id_alumno',id_alumno);
          data.append('id_director',id_director);
          data.append('given_name',given_name);
          data.append('family_name',family_name);
          data.append('full_name',full_name);
          data.append('org_path',org_path);
          data.append('grupo',grupo);
          data.append('matricula',matricula);
          data.append('plantel',plantel);
          data.append('motivo',motivo);


              $.ajax({
                  type:'POST',
                  url:SITE_URL+'emails/create',
                  data:data,
                      processData: false,
                      contentType: false,
         
                    success:function(data, textStatus, jqXHR)
                     {
                        var response = data;
                            if(data.status == false)
                            {
                                $('#notices-modal').html(data.message);
                                        $('#text_auto').val('');
                                        $('#plantel').val('');
                                        $('#matricula').val('');
                                        $('#grupo').val('');
                                        $('#id_alumno').val('');
                                        $('#id_director').val('');
                                        $('#given_name').val('');
                                        $('#family_name').val('');
                                        $('#full_name').val('');
                                        $('#org_path').val('');
                                        $('#motivo').val('');
                            }
                            else
                            {
                              if(enviados.length>0){
                                enviados.push({grupo:response.data.grupo,matricula:response.data.matricula,plantel:response.data.plantel,id:response.data.id ,given_name:response.data.given_name,family_name:response.data.family_name, create_on:response.data.create_on, motivo:response.data.motivo, });
                                load(enviados);
                                        $('#text_auto').val('');
                                        $('#plantel').val('');
                                        $('#matricula').val('');
                                        $('#grupo').val('');
                                        $('#id_alumno').val('');
                                        $('#id_director').val('');
                                        $('#given_name').val('');
                                        $('#family_name').val('');
                                        $('#full_name').val('');
                                        $('#org_path').val('');
                                        $('#motivo').val('');
                                $('#modalForm').modal('hide');
                                 var html = '';
                                $.each(enviados,function(index,value){
                                html+='<tr> <td whidth="10%"> <input name="item[]" value="'+value.id+'" id="item" onclick="btPrint.disabled = !this.checked" type="checkbox"> </td> <td>'+value.given_name+'</td><td> '+value.family_name+' </td> <td> '+value.grupo+'</td> <td> '+value.matricula+'</td>  <td> '+value.motivo+'</td>  </tr>';
                                // console.log(value); 
                              });

                            $('#bind-enviados').html(html);
                              }
                              else{
                                $('#modalForm').modal('hide');
                                 location.reload(); 

                              }
                                

                           }
                    }
                });
          
        }

        /*function print()
        {
          //document.formPrint.ids.value = ids;
          console.log('ok');
          
            $.ajax({
                  type: "POST",
                  dataType: 'json',

                  data: { 'oficio': $("#oficio").val(),
                          'semestre': $("#semestre").val(),
                          'subdirec': $("#subdirec").val(),
                          'control_escolar': $("#control_escolar").val(),
                          'ids': JSON.stringify(ids )
                        },
                  url: SITE_URL+'emails/download',
                  success : function(data) {
                    //console.log(data[0].id_acta);

                  }
              });
        }*/

        function load(enviados)
        {
          var html = '';
            $.each(enviados,function(index,value){
              html+='<tr> <td whidth="10%"> <input name="item[]" value="'+value.id+'" id="item" onclick="btPrint.disabled = !this.checked" type="checkbox"> </td> <td>'+value.given_name+'</td><td> '+value.family_name+' </td> <td> '+value.grupo+'</td> <td> '+value.matricula+'</td>  <td> '+value.motivo+'</td>  </tr>';
              // console.log(value); 
            });

          $('#bind-enviados').html(html);

        }

