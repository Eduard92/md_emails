<page backtop="30mm" backbottom="10mm" backleft="10mm" backright="10mm">
	<page_header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%;text-align:left;">
                    {{ asset:image file="pdf/cintillo_header.png" style="width:100%;" }}  
                </td>
            </tr>     
        </table>
        <table style="text-align:center;font-size: 10px;">
            <tr>
                <td width="200"></td>            
                <td width="300">
                 COLEGIO DE BACHILLERES DEL ESTADO DE CAMPECHE
                </td>
                <td width="200"></td>
            </tr>
            <tr>
                <td width="200"></td>            
                <td width="300">
                     {{plantel}}
                </td>
                <td width="200"></td>             
            </tr>
            <tr>
                <td width="200"></td>            
                <td width="300">
                     CLAVE: {{clave}}
                </td>
                <td width="200"></td>             
            </tr>
            <tr>
                <td width="200"></td>            
                <td width="300">
                     DIRECCION DEL PLANTEL
                </td>
                <td width="200"></td>             
            </tr>
            <tr>
                <td width="200"></td>            
                <td width="300">
                     Num. de oficio: {{oficio}}
                </td>
                <td width="200"></td>             
            </tr>
        </table>
    </page_header>
    <br />
    <br />    
    <br />
    <br />
    
    <p style="text-align: right;"><strong>Asunto:</strong> Solicitud de Correo Institucional.</p>



    <p style="text-align: right;">{{fecha}}</p>


    <p><strong>C.P. JOSÉ ALONSO SAGUNDO RODRIGUEZ<br />DIRECTOR ACADEMICO DEL COBACAM.</strong></p>

    <p style="text-align: right;"> <strong>AT'N. ING. CARLOS BENJAMIN ZUBIETA ROJAS<br />JEFE DEPTO. DES. ACAD. INC. REV. EST.</strong> </p>



    <br />
    
    <p style="text-align: justify; font-size: 14px; line-height: 20px;">
    Por medio de la presente solicito la creacion y asignacion de correo institucional para el(los) siguiente(s) alumno(s), por el(los) motivo(s) abajo mencionado(s) y actualmente si se encuentra(n) matriculado(s).</p>

    <br />
    <br />
    <table >
        <thead>
            <tr>
                <th width="240"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">ALUMNO</th>
                <th width="90"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px; font-size: 10px;">MATRICULA</th>
                <th width="50"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px;text-align: center;font-size: 10px;">GRUPO</th>
                <th width="240"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px;font-size: 10px;">MOTIVO</th>
                <!--th width="150"; align="center" style="border-bottom: #a6ce39 2px solid;padding: 3px;font-size: 10px; max-width:10px overflow-x: hidden;">MOTIVO</th-->
            </tr>
        </thead>
        <tbody>
                            {{table}}
        </tbody>
    </table>
    <p>Sin otro particular aprovecho la ocacion para enviarle un cordial saluda.</p>

    <br />
    <table>
        <tr>
            <td width="200"></td> 
           
            <td width="300">
                 <p style="text-align: center;">ATENTAMENTE</p>
            </td>
            <td width="200"></td>
            </tr>

        <tr>
            <td width="200"></td> 
           
            <td width="300" style="border-bottom:#000000 1px solid;">
                 <p style="text-align: center;">{{director}}</p>
            </td>
            <td width="200"></td>
             
        </tr>
        <tr>
            <td width="200"></td> 
           
            <td width="300">
                 <p style="text-align: center;">DIRECTOR DEL PLANTEL</p>
            </td>
            <td width="200"></td>
             
        </tr>
    </table>

    <page_footer>
        <table style="font-size: 8px;">
            <tr>
                <td width="30">
                    C.c.p
                </td>  
                <td>
                    Ing. Adlemi Santiago Ramirez.- Directora General del Colegio del Estado de Campeche.- Para su conocimiento.
                </td>              
            </tr> 
            <tr>
                <td width="30"></td>  
                <td>
                    Lic. Marcos Pablo Flores.- Jefe depto. de Asuntos Jurídicos.- Mismo fin 
                </td>              
            </tr>   
            <tr>
                <td width="30"></td>  
                <td>
                    L.I. Ivan Ariel Lanz Vera.- Jefe Depto. De informatica de la Direccion General.- Igual fin 
                </td>              
            </tr>
            <tr>
                <td width="30"></td>  
                <td>
                    Ing. José Ramón Naal Mukul.- Enc. Depto. Rec. Materiales y Servicios del Cobacam.- Mismo fin 
                </td>              
            </tr>
            <tr>
                <td width="30"></td>  
                <td>
                    Lic. Jorge Ivan Avila Rosado.- Jefe Depto. De Evaluacion Institucional del Cobacam.- Mismo fin 
                </td>              
            </tr>  
            <tr>
                <td width="30"></td>  
                <td>
                    {{subdirec}} .- Subdirector Academico {{plantel}} .-Mismo fin
                </td>              
            </tr>
            <tr>
                <td width="30"></td>  
                <td>
                    {{control_escolar}} .- Responsable del Depto. de Control Escolar del {{plantel}} .-Mismo fin
                </td>              
            </tr>
            <tr>
                <td width="30"></td>  
                <td>
                    Archivo
                </td>              
            </tr>        </table>

        <table style="width: 100%;">
            <tr>
                <td style="width: 100%;text-align:left;">
                    {{ asset:image file="pdf/cintillo_footer.png" style="width:100%;" }}
                    
                </td>
                
                
            </tr>
            
        </table>
    </page_footer>
   


 </page>