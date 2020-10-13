@extends('master.master')

@section('title', 'Subir documentos de convenio')

@section('contenido')

<div class="row" ng-controller="EmpSubirDocsCtrl" ng-init="convenio.id = {{$id}}">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <form role="form" ng-submit="subirDocs()">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Certificado de existencia y representacion legal</label>
                        <input type="file" uploader-model="convenio.file_existencia"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_existencia[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Cédula del representante legal</label>
                        <input type="file" uploader-model="convenio.file_cedula"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_cedula[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Certificado de la procuraduría del representacion legal</label>
                        <input type="file" uploader-model="convenio.file_procuraduria"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_procuraduria[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Certificado de la contraloría del representacion legal</label>
                        <input type="file" uploader-model="convenio.file_contraloria"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_contraloria[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Rut actualizado (firmado por el representante legal)</label>
                        <input type="file" uploader-model="convenio.file_rut"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_rut[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Acta de posesión del representante legal o delegado competente para suscribir convenios. (En caso de no estar establecido en el certificado de existencia y representación legal)</label>
                        <input type="file" uploader-model="convenio.file_posesion"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_posesion[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Copia del acto administrativo o norma legal expedido por el organo competente que faculta al representante legal para suscribir convenios o contratos, cuando se requiera. Si aplica.</label>
                        <input type="file" uploader-model="convenio.file_acto_administrativo"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_acto_administrativo[0] }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group separator">
                        <label>Certificado de definición de situación militar expedido por la dirección de reclutamiento y control de reservas del ejercito nacional vigente. Si aplica (si el representante legal es menor de 50 años)</label>
                        <input type="file" uploader-model="convenio.file_militar"  class="form-control file"/>
                        <p class="help-block text-danger">
                            @{{ errores.file_militar[0] }}
                        </p>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success" >Guardar</button>
                
        </form>
    </div>
    <div class="col-md-1"></div>
    
</div>

        

@stop