var dipro;
var path = window.location.pathname.split('/');

if ((path[path.length-2] == 'home' && path[path.length-1] == 'registro') || path[path.length-1] == '')
    dipro = angular.module("dipro", ['ui.select', 'objectTable', 'ui.utils.masks', 'ngMaterial', 'ui.bootstrap', 'ckeditor']);
else {
    dipro = angular.module("dipro", ['ui.select', 'objectTable', 'ui.utils.masks', 'ngMaterial', 'ui.bootstrap']);

    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
    $.fn.selectpicker.Constructor.DEFAULTS.noneSelectedText = 'Seleccione';
    $.fn.selectpicker.Constructor.DEFAULTS.selectedTextFormat = 'count';
    $.fn.selectpicker.Constructor.DEFAULTS.styleBase = 'form-control';
    $.fn.selectpicker.Constructor.DEFAULTS.style = 'form-control';
}

var raiz = '/sil-copia/public';

function cargando()
{
    $('#cargando').data('backdrop', 'static');
    $('#cargando').data('keyboard', 'false');
    $('#cargando').modal('show');
}

function getTime(hour)
{
    var vector = hour.split(':');
    return new Date(moment({hour:vector[0], minute:vector[1]}));
}

function close_cargando()
{
    $('#cargando').modal('hide');
}

function cerrar_modal(nombre)
{
    $('#'+nombre).modal('hide');
}

function open_modal(nombre)
{
    $('#'+nombre).modal('show');
}

function toFormData (fd, objeto, idxs, $filter)
{
    //console.log(objeto);
    $.each(objeto, function(index, contenido){

        if( (''+index).indexOf("fecha_") != -1 )
        {
            contenido = $filter('date')(contenido, 'yyyy-MM-dd');
            //console.log(contenido);
        }
        if(typeof(contenido) == 'object' && (''+index).indexOf("file") == -1 && (''+index).indexOf("fecha") == -1 && typeof(contenido) != 'boolean')
	    {
	        idxs.push(index);
	        toFormData(fd, contenido, idxs);
	        idxs.splice(idxs.length - 1, 1);
	    }
	    else
	    {
	        if(idxs.length == 0)
	        {
	            fd.append(index, contenido);
	        }
	        else
	        {
	            var nombre = '';
	           // console.log(idxs);
	            for(var i=0 ; i < idxs.length ; i++)
	            {
	                if(i==0)
	                {
	                    nombre+=idxs[i]+'[';
	                }
	               // else if(i == (idxs.length - 1))
	               // {
	               //     nombre+=idxs[i]+'[';
	               // }
	                else
	                {
	                    nombre+=idxs[i]+'][';
	                }
	            }
	           // console.log(nombre+index+']');

	            fd.append(nombre+index+']', contenido);
	        }

	    }
    });
    return fd;
}


dipro.directive('uploaderModel', function ($parse) {
	return {
		restrict: 'A',
		link: function (scope, iElement, iAttrs)
		{
			iElement.on("change", function(e)
			{
				$parse(iAttrs.uploaderModel).assign(scope, iElement[0].files[0]);
			});
		}
	};
})

dipro.filter('cortarTexto', function(){
	return function(input, limit){
		if (input==null)
			return "";
		return (input.length > limit) ? input.substr(0, limit)+'...' : input;
	};
})

dipro.filter('estadoPractrica', function(){
	return function(input){
		if (input==0)
			return "En espera";
		else if (input==1)
			return "Autorizada";

	};
});

dipro.filter('estadoVisita', function(){
	return function(input){
		if (input==null)
			return "En espera";
		else if (input==1)
			return "Confirmada";
		else
		    return "No confirmada";

	};
});

dipro.filter('salarioOferta', function($filter){
	return function(input){
		if (isNaN(input))
			return input;
		else
			return '$'+ $filter('currency')(input, '', 0);

	};
});

dipro.filter('asistencia', function(){
	return function(input){
		if (input)
			return "Si";
		else if(input == null)
		    return "No se ha registrado";
	    else
	        return "No";
	};
});

dipro.controller('indexCtrl', function($scope){
    $scope.fecha = new Date();
});


dipro.controller('usuariosCtrl', function($scope, $http){

    $scope.mostrar =false;

    $scope.editar=false;

    $http.get(raiz+'/admin/usuariosjson').success(function(data){
        $scope.usuarios = data;
    });



    $http.get(raiz+'/admin/formulariousuario').success(function(data) {
        $scope.datos = data;
    });

    $('#crearUsuario').on('show.bs.modal', function(e) {

        var id= $(e.relatedTarget).data('id');
        if(id!=null)
        {
            cargando()
            $scope.errores = {};
            $scope.editar=true;
            $http.get(raiz+'/admin/usuario/'+id).success(function(data){
                $scope.usuario = data;
                $('#cargando').modal('hide');
            });
        }
    });

    $scope.newUser = function()
    {
        $scope.usuario = {};
        $scope.editar=false;
        $scope.errores = {};
    }

    $scope.guardarUsuario = function()
    {
        cargando()
        $scope.usuario.editar = $scope.editar;

        $http.post(raiz+'/admin/saveusuario', $scope.usuario).success(function(data){
            $('#crearUsuario').modal('hide');
            if(data == "1")
            {
                swal("Registro exitoso!", "Datos guardados correctamente", "success");
                $http.get(raiz+'/admin/usuariosjson').success(function(data){
                    $scope.usuarios = data;
                });
            }
            else
            {
                swal("Error!", "Los datos no se guardaron correctamente", "error");
            }
            $('#cargando').modal('hide');
            //location.href="/users";
        }).error(function(data){
            $scope.errores=data;
            $('#cargando').modal('hide');
        });

    }

});

dipro.controller('registroCtrl', function($scope, $http, $filter, $timeout) {

    $('#empresaModal').modal({
        keyboard: false,
        show: false,
        backdrop: 'static'
    });

    $scope.usuario = {};

    $('input').attr('placeholder', 'Suministrar informacion');

    $http.get(raiz+'/home/formularioregistro').success(function(data){
        $scope.datos = data;
    });

    $http.get(raiz+'/home/modalidades').success(function(data) {
        $scope.modalidades = data;
    });

    $scope.registrarEmpresa = function () {
        if ($scope.usuario.rol.nombre == 'Empresa') {
            $('#empresaModal').modal('show');
        }
    }

    $scope.elegirArchivo = function() {
        $('#archivo').click();
    }

    $scope.mostrarNombre = function() {
        $timeout(function() {
            var archivo = $('#archivo')[0].files[0];
            if(archivo) $('#mArchivo').attr('value', archivo.name);
            else $('#mArchivo').attr('value', '');
        }, 10);
    }

    $scope.registrarUsuario = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.usuario, idxs, $filter);
        $scope.errores={};
        $http.post(raiz+'/home/registro', formData, {
			headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
		}).success(function(data){
		    close_cargando();
            $scope.usuario ={};
            swal({
                title: data.title,
                text: data.content,
                type: data.type
            }, function() {
                location.href = raiz;
            });
        }).error(function(data){
            close_cargando();
            if(data.length >100)
            {
                swal('Error', 'Hubo un error con la conexión con admisiones, por favor intenta mas tarde', 'error');
            }
            else
            {
                $scope.errores = data;
            }
        });
    }

    $scope.selectPais = function()
    {
        $http.get(raiz+'/home/departamentos/'+$scope.usuario.pais.id).success(function(data){
            $scope.datos.departamentos = data;
        });
    }

    $scope.selectDepartamento= function()
    {
        $http.get(raiz+'/home/municipios/'+$scope.usuario.departamento.id).success(function(data){
            $scope.datos.municipios = data;
        });
    }

});

dipro.controller('loginCtrl', function($scope, $http){
    $scope.errores = {};
    $scope.error = {};
    $scope.usuario = {};
    $scope.roles = [];

    $scope.login = function(){
        cargando();
        $scope.errores={};
        $http.post(raiz+'/home/login', $scope.usuario).success(function(data) {
            if(data.type=="success") location.href = raiz+'/home';
            else if (data.type == 'pendiente') $scope.roles = data.roles
            else {
                $('#cargando').modal('hide');
                swal(data.title, data.content, data.type);
            }

        }).error(function(data){
            $('#cargando').modal('hide');
            $scope.errores = data;
        });
    };

    $scope.restablecerC = function()
    {
        cargando();
        $scope.error={};
        $http.post(raiz+'/home/restablecer', $scope.restablecer).success(function(data){

            close_cargando();
            if(data.type == "error")
            {
                $scope.error.correo=[];
                $scope.error.correo[0]=data.content;
            }
            else
            {
                $('#restablecer').modal('hide');
                swal(data.title, data.content, data.type);
            }



        }).error(function(data){
            close_cargando();
            $scope.error = data;
        });
    }
});

dipro.controller('EmpresaCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    $http.get(raiz+'/adminsil/empresasjson').success(function(data){
        $scope.empresas = data;
    })

    $('#cambiarestado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        var estado = $(e.relatedTarget).data('estado');

        $scope.errores = {};
        $scope.empresa = {};
        $scope.empresa.id = id;
        $http.get(raiz+'/adminsil/estadosempresas/'+estado).success(function(data) {
            $scope.estados = data;
        });
    });

    $('#cambiarestado').on('hide.bs.modal', function(e) {
        $scope.empresa = {};
    });

    $('#detallesEmpresa').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/adminsil/datosempresa/'+id).success(function(data){
            $scope.empresa = data;
        });
    });

    $('#detallesEmpresa').on('hide.bs.modal', function(e) {
        $scope.empresa = {};
    });

    $scope.cambiarEstado= function ()
    {
        $http.post(raiz+'/adminsil/cambiarestadoempresas', $scope.empresa).success(function(data){
            $('#cambiarestado').modal('hide');
            $http.get(raiz+'/adminsil/empresasjson').success(function(data){
                $scope.empresas = data;
            })
            swal(data.title, data.content, data.type);
        }).error(function(data){
            $scope.errores = data;
        });
    }

});

dipro.controller('EmpUsuariosCtrl', function($scope, $http, $rootScope) {

   $http.get(raiz+'/empresa/usuariosbyempresajson').success(function(data){
       $scope.usuarios = data;
   });

   $scope.newUser = function()
   {
       $scope.usuario = {};
       $scope.editar=false;
       $scope.errores = {};
   }

   $('#crearUsuario').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        if(id!=null)
        {
            $scope.errores = {};
            $scope.editar=true;
            $http.get(raiz+'/empresa/usuariojson/'+id).success(function(data){
                $scope.usuario = data;
            });
        }

    });


    $scope.guardarUsuario = function()
    {
        $http.post(raiz+'/empresa/saveusuario', $scope.usuario).success(function(data){

            $('#crearUsuario').modal('hide');

            swal(data.title, data.content, data.type);
            $http.get(raiz+'/empresa/usuariosbyempresajson').success(function(data){
                $scope.usuarios = data;
            });


        }).error(function(data){
            $scope.errores=data;
        });
    }

});

dipro.controller('EmpOfertasCtrl', function($scope, $http){

    $scope.raiz = raiz;
    $http.get(raiz+'/empresa/ofertasjson').success(function(data){
        $scope.ofertas = data;
    });

    $('#mdldetalles').on('show.bs.modal', function(e) {
        cargando();
        $scope.idOferta = $(e.relatedTarget).data('id');

        $http.get(raiz+'/empresa/detallesofertajson/'+$scope.idOferta).success(function(data){
            $scope.oferta = data;
            close_cargando();
        });
    });

    $('#mdldetalles').on('hide.bs.modal', function(e) {
        $scope.oferta = {};
    });

    $scope.seleccionarPostulado = function(id, nombre)
    {
        swal({
            title: 'Invitar postulado al proceso',
            text: '¿Seguro que desea seleccionar a '+nombre+'?',
            type: 'info',
            showCancelButton: true
        }, function(value) {
            if(value) {
                $http.get(raiz+'/empresa/seleccionarpostulado/'+id).success(function(data){
                    $http.get(raiz+'/empresa/postuladosjson/'+$scope.oferta.id).success(function(data){
                        $scope.oferta.getpostulados = data;
                    });
                    $http.get(raiz+'/empresa/ofertasjson').success(function(result){
                        $scope.ofertas = result;
                    });
                    $('#cargando').modal('hide');
                    swal(data.title, data.content, data.type);
                });
            }
        });
    }

    $scope.aceptarPostulado = function(postulado, nombre) {
        swal({
            title: 'Elegir postulado',
            text: '¿Seguro que desea elegir a '+nombre+'?',
            type: 'info',
            showCancelButton: true
        }, function(value) {
            if(value) {
                $http.post(raiz+'/empresa/aceptar-postulado', postulado).then(function(response) {
                    $http.get(raiz+'/empresa/postuladosjson/'+$scope.oferta.id).success(function(data){
                        $scope.oferta.getpostulados = data;
                    });
                    $http.get(raiz+'/empresa/ofertasjson').success(function(result){
                        $scope.ofertas = result;
                    });
                    $('#cargando').modal('hide');
                    swal(response.data.title, response.data.content, response.data.type);
                });
            }
        });
    }

    $scope.eliminarSeleccion = function(id)
    {
        $http.get(raiz+'/empresa/desseleccionarpostulado/'+id).success(function(data){
            $('#postulados').modal('hide');
            $http.get(raiz+'/empresa/ofertasjson').success(function(result){
                $scope.ofertas = result;
            });
            swal(data.title, data.content, data.type);
        });
    }

    $scope.notificarSeleccionado = function(id)
    {
        $http.get(raiz+'/empresa/notificarseleccionado/'+id).success(function(data){
            swal(data.title, data.content, data.type);
        });
    }

    $scope.eliminarOferta = function(id)
    {
        swal({
            title: "Eliminar",
            text: "¿Está seguro que desea eliminar la oferta?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8cd4f5",
            confirmButtonText: "Si",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
            },
            function(isConfirm)
            {
                if(isConfirm)
                {
                    $http.get(raiz+'/empresa/eliminaroferta/'+id).success(function(data){
                        $http.get(raiz+'/empresa/ofertasjson').success(function(data){
                            $scope.ofertas = data;
                        });
                        swal(data.title, data.content, data.type);
                    });
                }
            });
    }


});

dipro.controller('EmpCrearofertaCtrl', function($scope, $http, $filter, $rootScope){

    $scope.oferta = {
        programas: []
    };
    $scope.errores = {};

    $scope.form = {
        pais: {},
        departamento: {}
    };

    // console.log($('textarea'));
    $('textarea').attr('placeholder','Suministrar informacion');

    // $http.get(raiz+'/empresa/formularioofertajson').success(function(data){
    //     $scope.formulario = data;
    // });

//     $scope.uploadFile = function(oferta)
// 	{

// 	}
    $http.get(raiz+'/empresa/formularioofertajson').success(function(dataa) {
        $scope.formulario = dataa;
        $('.bselect').selectpicker();
    });

    $rootScope.$watch('oferta.id', function(){
        if($scope.oferta.id != null)
        {
            $http.get(raiz+'/empresa/ofertajson/'+$scope.oferta.id).success(function(data) {
                data.fecha_cierre = new Date(moment(data.fecha_cierre));
                $scope.oferta = data;
                // setTimeout(function() {console.log($scope.formulario);}, 10);

                $scope.form.pais = $scope.formulario.paises.filter(pais => pais.id == $scope.oferta.getmunicipio.getdepartamento.idPais)[0];
                $scope.form.departamento = $scope.form.pais.departamentos.filter(dep => dep.id == $scope.oferta.getmunicipio.idDepartamento)[0];
                // $scope.form.departamento = $scope.oferta.getmunicipio.getdepartamento;
                if($scope.oferta.salario == 'Por definir')
                {
                    $scope.oferta.pordefinir = true;
                }

                if($scope.oferta.salud == 1)
                {
                    $scope.oferta.saluds = true;
                }
                else
                {
                    $scope.oferta.saludn = true;
                }

                if($scope.oferta.arl == 1)
                {
                    $scope.oferta.arls = true;
                }
                else
                {
                    $scope.oferta.arln = true;
                }
            });

        }
    });

    $scope.saluds = function()
    {
        if($scope.oferta.saluds)
        {
            $scope.oferta.saludn = false;
        }
    }

    $scope.saludn = function()
    {
        if($scope.oferta.saludn)
        {
            $scope.oferta.saluds = false;
        }
    }

    $scope.arls = function()
    {
        if($scope.oferta.arls)
        {
            $scope.oferta.arln = false;
        }
    }

    $scope.arln = function()
    {
        if($scope.oferta.arln)
        {
            $scope.oferta.arls = false;
        }
    }

    $scope.guardarOferta = function()
    {
        cargando()
        if($scope.oferta.saluds)
        {
            $scope.oferta.salud = 1;
        }
        else if($scope.oferta.saludn)
        {
            $scope.oferta.salud = 0;
        }

        if($scope.oferta.arls)
        {
            $scope.oferta.arl = 1;
        }
        else if($scope.oferta.arln)
        {
            $scope.oferta.arl = 0;
        }

		var formData = new FormData();
		var idxs = [];

		toFormData(formData, $scope.oferta, idxs, $filter);
		//console.log(formData.fecha_cierre);
		$http.post(raiz+"/empresa/saveoferta", formData, {
			headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
		})
		.success(function(data)
		{
		    $('#cargando').modal('hide');
			if(data.type == 'error')
            {
                swal(data.title, data.content, data.type);
            }
            else if(data.type == 'success')
            {

                swal({
                    title: data.title,
                    text: data.content,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Ok",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        location.href=raiz+'/empresa';

                    });

            }
		})
		.error(function(data)
		{
		    $('#cargando').modal('hide');
		    $scope.errores=data;
		})
    }
});

var table = undefined;
dipro.controller('AdminOfertasCtrl', function($scope, $http){
    $scope.raiz = raiz;
    $scope.oferta_form = {};
    $scope.errors = { oferta_form: {} };
    $scope.oferta = { postulados: [] };

    function initDataTable () {
        return $('.dtable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            }
        })
    }

    table = initDataTable();

    $('#detallesOferta').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/adminsil/ofertajson/'+id).success(function(data){
            $scope.oferta = data;

            table.destroy();
            setTimeout(function() { table = initDataTable() }, 10);

        });
    });

    $('#cambiarestado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        var estado = $(e.relatedTarget).data('estado');
        $scope.erroresEstado = {};
        $scope.oferta={};
        $scope.oferta.mensaje = $(e.relatedTarget).data('mensaje');
        $scope.oferta.id = id;
        $scope.oferta.m = false;

        if(!(estado.nombre == 'Por aprobar' || estado.nombre == 'Publicada')) $scope.oferta.d = true;
        if(estado.nombre != 'Por aprobar') $scope.oferta.estado = estado;
        else $scope.oferta.m = true;

        $http.get(raiz+'/adminsil/estadosoferta/'+id).success(function(data){
            $scope.estados = data;
        });
    });

    $('#ofertaModal').on('show.bs.modal', e => {
        var id = $(e.relatedTarget).data('id');

        if (id) {
            $http.get(raiz + '/adminsil/oferta-egresados/' + id).then(response => {
                $scope.oferta_form = response.data;
                $scope.oferta_form.fecha_cierre = new Date($scope.oferta_form.fecha_cierre.replace('-', '/'));
                $scope.oferta_form.departamento = $scope.datos.departamentos.filter(item => item.id == $scope.oferta_form.departamento_id)[0];
            }, error => {
                swal('Error', 'Ocurrio un error, intente de nuevo mas tarde', 'error');
            });
        }
    })

    function getOfertas () {
        $http.get(raiz+'/adminsil/ofertasjson').success(function(data){
            $scope.ofertas=data;
        }, error => { getOfertas(); });
    }

    function init () {
        $http.post('').then(response => {
            $scope.datos = response.data;
            $('.bselect').selectpicker();
        }, error => { init(); });
    }

    init();
    getOfertas();

    $scope.cambiarEstado = function ()
    {
        $http.post(raiz+'/adminsil/cambiarestadooferta', $scope.oferta).success(function(data){
            $('#cambiarestado').modal('hide');
            swal(data.title, data.content, data.type);

            $http.get(raiz+'/adminsil/ofertasjson').success(function(data) {
                $scope.ofertas=data;
            });
        }).error(function(data){
            $scope.erroresEstado = data;
        });
    }

    $scope.abrirModalOferta = function (oferta) {
        $scope.oferta_form = {};
        $('#ofertaModal').modal('show');

        setTimeout(function () { $('.bselect').selectpicker('refresh'); }, 10);
    }

    $scope.guardarOfertaLaboral = function () {
        $http.post(raiz + '/adminsil/guardar-oferta', $scope.oferta_form).then(response => {
            $('#ofertaModal').modal('hide');
            getOfertas();
            swal('Exito', 'Oferta creada con exito!', 'success');
        }, error => {
            if (error.status == 422) $scope.errors.oferta_form = error.data;
        });
    }
});

dipro.controller('EstIndexCtrl', function($scope, $http){

    $scope.modalidad = {};
    $scope.raiz = raiz;


    $http.get(raiz+'/estudiante/infoestudiante').success(function(data){
        $scope.estudiante = data;
    })


    $http.get(raiz+'/home/modalidades').success(function(data) {
        $scope.modalidades = data;
    })

    $scope.solicitarPracticas = function()
    {
        console.log($scope.modalidad);
        $http.post(raiz+'/estudiante/solicitarpracticas', $scope.modalidad).success(function(data){
            if(data.type == 'error')
            {
                swal(data.title, data.content, data.type);
            }
            else if(data)
            {
                $http.get(raiz+'/estudiante/infoestudiante').success(function(data){
                    $scope.estudiante = data;
                });
            }
        }).error(function(data){
            $scope.errores = data;
        })
    }



});

dipro.controller('AdminSolicitantesCtrl', function($scope, $http) {
    $scope.rechazar = {};
    $scope.errores = {};
    $scope.seleccionados = {};
    $scope.seleccionados.seleccionados = [];

    $http.get(raiz+'/admin/solicitantesjson').success(function(data){
        $scope.solicitantes = data;
    });

    $scope.agregarSelccion = function(id)
    {
        var index = buscar(id);
        if(index < $scope.seleccionados.seleccionados.length)
        {
            $scope.seleccionados.seleccionados.splice(index,1 );
        }
        else
        {
            $scope.seleccionados.seleccionados.push(id);
        }
    }

    function buscar(id)
    {
        var i=0;
        for(i=0; i<$scope.seleccionados.seleccionados.length; i++)
        {
            if(parseInt(id) == parseInt($scope.seleccionados.seleccionados[i]))
            {
                break;
            }
        }

        return i;
    }

    $scope.aprobarpracticasmultiple = function()
    {
        $http.post(raiz+'/admin/aprobarpracticasmultiple', $scope.seleccionados).success(function(data) {
            swal(data.title, data.content, data.type);
            $scope.seleccionados = {};
            $scope.seleccionados.seleccionados = [];
            $http.get(raiz+'/admin/solicitantesjson').success(function(data){
                $scope.solicitantes = data;
                $('#cargando').modal('hide');
            });
        })
    }

    $('#rechazarPracticas').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.rechazar.id = id;
    });

    $scope.rechazarPracticas = function()
    {
        cargando();
        $http.post(raiz+'/admin/rechazarpracticas', $scope.rechazar).success(function(data){
            $('#rechazarPracticas').modal('hide');
            close_cargando();
            $http.get(raiz+'/admin/solicitantesjson').success(function(data){
                $scope.solicitantes = data;
            });
            swal(data.title, data.content, data.type);
        }).error(function(data){
            $scope.errores = data;
        })
    }
});

dipro.controller('EstOfertasCtrl', function($scope, $http) {

    $scope.estudiante ={};

    $http.get(raiz+'/estudiante/estudiantejson').success(function(data) {
       $scope.estudiante = data.estudiante;
    });

    $http.get(raiz+'/estudiante/ofertasjson').success(function(data){
        $scope.ofertas = data;
    });

    $('#detallesOferta').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/estudiante/ofertajson/'+id).success(function(data){
            $scope.oferta = data;
        });
    });
});

dipro.controller('EstHojaCtrl', function($scope, $http) {
    $scope.hoja = {};
    $scope.error = {};
    $scope.errorExp = {};
    $scope.experiencia = {};
    $scope.errorIdioma = {};
    $scope.nuevoIdioma = {};
    $scope.errorPersonal = {};
    $scope.errorFamiliar = {};
    $scope.estudios = {};
    $scope.estudios.anio = {};
    $scope.estudios.anio.id = null;
    $scope.estudios.anio.nombre = null;
    $http.get(raiz+'/estudiante/hojajson').success(function(data) {
       $scope.hoja = data;
       $scope.hoja.estudiante.getpersona.fechaNacimiento = new Date(moment(data.estudiante.getpersona.fechaNacimiento));
       if(data.getestudios == null)
       {
           $scope.hoja.getestudios = [];
       }
       if(data.getexperiencias == null)
       {
           $scope.hoja.getexperiencias = [];
       }
       if(data.getidiomas == null)
       {
           $scope.hoja.getidiomas = [];
       }
       if(data.getreferenciasp == null)
       {
           $scope.hoja.getreferenciasp = [];
       }
       if(data.getreferenciasf == null)
       {
           $scope.hoja.getreferenciasf = [];
       }

    });

    $scope.selectPais = function()
    {
        $http.get(raiz+'/home/departamentos/'+$scope.hoja.estudiante.getpersona.getciudad.getdepartamento.getpais.id).success(function(data){
            $scope.hoja.departamentos = data;
        });
    }

    $scope.selectDepartamento= function()
    {
        $http.get(raiz+'/home/municipios/'+$scope.hoja.estudiante.getpersona.getciudad.getdepartamento.id).success(function(data){
            $scope.hoja.municipios = data;
        });
    }

    $scope.agreagarEstudio = function()
    {
        $scope.estudios.anioGrado = $scope.estudios.anio.nombre;
        $http.post(raiz+'/estudiante/estudiorealizado', $scope.estudios).success(function(data){
            //

            var index = getIndex($scope.estudios.titulo);
	        if(index < $scope.hoja.getestudios.length)
	        {
	            $scope.error.titulo = [];
	            $scope.error.titulo[0] = 'Este título ya se encuentra registrado';
	        }
	        else
	        {
	            $scope.hoja.getestudios.push($scope.estudios);
                $scope.estudios = {};
                $scope.error = {};
                $scope.estudios.anio = {};
                $scope.estudios.getmunicipio = {};
                $scope.estudios.anio.id = null;
                $scope.estudios.anio.nombre = null;
                $('#agregarEstudio').modal('hide');
	        }

        }).error(function(data){
            $scope.error = data;
        });

    }

    $scope.quitarEstudio = function(titulo)
	{
	    console.log(titulo);
		var index = getIndex(titulo);
		$scope.hoja.getestudios.splice(index ,1 );
	}

	$scope.cancelarEstudio = function ()
	{
	    $scope.error = {};
	    $scope.estudios = {};
	}

    function getIndex(titulo)
	{
		var i=0;

		for(i=0;i < $scope.hoja.getestudios.length;i++)
		{
			if(titulo.toLowerCase()==$scope.hoja.getestudios[i].titulo.toLowerCase())
			{
				break;
			}
		}
		return i;
	}

	////////////////////////////////

	$scope.agreagarExperiencia = function()
    {
        $http.post(raiz+'/estudiante/experiencialaboral', $scope.experiencia).success(function(data) {
            //

            var index = getIndexExp($scope.experiencia.cargo, $scope.experiencia.empresa);
	        if(index < $scope.hoja.getexperiencias.length)
	        {
	            $scope.errorExp.cargo = [];
	            $scope.errorExp.cargo[0] = 'Esta experiencia ya se encuentra registrada';
	        }
	        else
	        {
	            $scope.hoja.getexperiencias.push($scope.experiencia);
                $scope.experiencia = {};
                $scope.errorExp = {};
                $('#agregarExperiencia').modal('hide');
	        }
            //

	    }).error(function(data){
	        $scope.errorExp = data;
	    });
    }

    $scope.quitarExperiencia = function(cargo, empresa)
	{
        var index = getIndexExp(cargo, empresa);
	    $scope.hoja.getexperiencias.splice(index ,1 );
	}

	$scope.cancelarExperiencia = function ()
	{
	    $scope.errorExp = {};
	    $scope.experiencia = {};
	}

    function getIndexExp(cargo, empresa)
	{
		var i=0;

		for(i=0;i < $scope.hoja.getexperiencias.length;i++)
		{
			if(cargo==$scope.hoja.getexperiencias[i].cargo && empresa==$scope.hoja.getexperiencias[i].empresa)
			{
				break;
			}
		}
		return i;
	}

	$scope.guardarDatosPersonales = function()
	{
	    $http.post(raiz+'/estudiante/actualizardatos', $scope.hoja.estudiante.getpersona).success(function(data){
	        swal(data.title, data.content, data.type);
	    }).error(function(data){
	        $scope.errorDatosPersonales = data;
	    });

	}

	$scope.quitarIdioma = function(nombre)
	{
	    var index = getIndexIdioma(nombre);
	    $scope.hoja.getidiomas.splice(index ,1 );
	}


    function getIndexIdioma(nombre)
	{
		var i=0;

		for(i=0;i < $scope.hoja.getidiomas.length;i++)
		{
			if(nombre==$scope.hoja.getidiomas[i].getidioma.nombre )
			{
				break;
			}
		}
		return i;
	}

	$scope.agreagarIdioma = function()
    {
        console.log($scope.nuevoIdioma);
        $http.post(raiz+'/estudiante/idioma', $scope.nuevoIdioma).success(function(data) {
            //

            var index = getIndexIdioma($scope.nuevoIdioma.getidioma.nombre);
	        if(index < $scope.hoja.getidiomas.length)
	        {
	            $scope.errorIdioma['getidioma.id'] = []
	            $scope.errorIdioma['getidioma.id'][0] = 'Esta idioma ya se encuentra registrado';
	        }
	        else
	        {
	            $scope.hoja.getidiomas.push($scope.nuevoIdioma);
                $scope.nuevoIdioma = {};
                $scope.errorIdioma = {};
                $('#agregarIdioma').modal('hide');
	        }

            //


	    }).error(function(data){
	        $scope.errorIdioma = data;
	    });
    }

	$scope.cancelarIdioma = function ()
	{
	    $scope.errorIdioma = {};
        $scope.nuevoIdioma = {};
	}

	$scope.guardarPerfil = function()
	{
	    console.log($scope.hoja.getcompetencias);
	    $http.post(raiz+'/estudiante/saveperfil', $scope.hoja).success(function(data) {

	        $scope.errores = {};
	        swal(data.title, data.content, data.type);

	    }).error(function(data) {
	        $scope.errores = data;
	    })
	}

	$scope.quitarPersonal = function (telefono)
	{
	    var index = getIndexPersonal(telefono);
	    $scope.hoja.getreferenciasp.splice(index ,1 );
	}

	function getIndexPersonal(telefono)
	{
	    var i=0;

		for(i=0;i < $scope.hoja.getreferenciasp.length;i++)
		{
			if(telefono == $scope.hoja.getreferenciasp[i].telefono )
			{
				break;
			}
		}
		return i;
	}

	$scope.agreagarReferenciaP = function()
	{
	    $http.post(raiz+'/estudiante/referenciapersonal', $scope.referenciaPersonal).success(function(data) {
	        var index = getIndexPersonal($scope.referenciaPersonal.telefono);
	        var index2 = getIndexFamiliar($scope.referenciaPersonal.telefono);
	        if(index < $scope.hoja.getreferenciasp.length || index2 < $scope.hoja.getreferenciasf.length)
	        {
	            $scope.errorPersonal.telefono = [];
	            $scope.errorPersonal.telefono[0] = 'El número de telefono ya se encuentra registrado';
	        }
	        else
	        {
	            $scope.hoja.getreferenciasp.push($scope.referenciaPersonal);
    	        $scope.referenciaPersonal = {};
    	        $scope.errorPersonal = {};
    	        $('#agregarReferenciaP').modal('hide');
	        }
	    }).error(function(data) {
	        $scope.errorPersonal = data;
	    })
	}

	$scope.agreagarReferenciaF = function()
	{
	    $http.post(raiz+'/estudiante/referenciafamiliar', $scope.referenciaFamiliar).success(function(data) {
	        var index = getIndexPersonal($scope.referenciaFamiliar.telefono);
	        var index2 = getIndexFamiliar($scope.referenciaFamiliar.telefono);
	        if(index < $scope.hoja.getreferenciasp.length || index2 < $scope.hoja.getreferenciasf.length)
	        {
	            $scope.errorFamiliar.telefono = [];
	            $scope.errorFamiliar.telefono[0] = 'El número de telefono ya se encuentra registrado';
	        }
	        else
	        {
    	        $scope.hoja.getreferenciasf.push($scope.referenciaFamiliar);
    	        $scope.referenciaFamiliar = {};
    	        $scope.errorFamiliar = {};
    	        $('#agregarReferenciaF').modal('hide');
	        }
	    }).error(function(data) {
	        $scope.errorFamiliar = data;
	    })
	}

    $scope.quitarFamiliar = function (telefono)
	{
	    var index = getIndexFamiliar(telefono);
	    $scope.hoja.getreferenciasf.splice(index ,1 );
	}

	function getIndexFamiliar(telefono)
	{
	    var i=0;

		for(i=0;i < $scope.hoja.getreferenciasf.length;i++)
		{
			if(telefono == $scope.hoja.getreferenciasf[i].telefono )
			{
				break;
			}
		}
		return i;
	}

	$scope.cancelarPersonal = function ()
	{
	    $scope.errorPersonal = {};
	}

	$scope.cancelarFamiliar = function ()
	{
	    $scope.errorFamiliar = {};
	}

	$scope.guardarReferencias = function()
	{
	    $http.post(raiz+'/estudiante/savereferencia', $scope.hoja).success(function(data) {
	        swal(data.title, data.content, data.type);
	    })
	}

});

dipro.controller('EmpHojaCtrl', function($scope, $http, $rootScope) {
    $rootScope.$watch('idEstudiante', function(){
        $http.get(raiz+'/empresa/hojajson/'+$scope.idEstudiante).success(function(data){
            $scope.estudiante = data;
        });
    });
})

dipro.controller('AdminHojaCtrl', function($scope, $http, $rootScope) {
    $rootScope.$watch('idEstudiante', function(){
        $http.get(raiz+'/admin/hojajson/'+$scope.idEstudiante).success(function(data){
            $scope.estudiante = data;
        });
    });
})

dipro.controller('EstPracticaCtrl', function($scope, $http){

    $http.get(raiz+'/estudiante/aptoevaluar').success(function(data){
        $scope.apto = data;
    })

    $http.get(raiz+'/estudiante/practicasjson').success(function(data){
        $scope.practicas = data;
    });

    $http.get(raiz+'/estudiante/visitasjson').success(function(data){
        $scope.visitas = data;
    });

    $scope.confirmarVisita = function(id)
    {
        cargando();
        $http.get(raiz+'/estudiante/confirmarvisita/'+id).success(function(data) {
            swal(data.title, data.content, data.type);

            $http.get(raiz+'/estudiante/visitasjson').success(function(result){
                $scope.visitas = result;
                $('#cargando').modal('hide');
            });


        })
    }

    $scope.confirmarvisita = function(id)
    {
        cargando();
        $http.get(raiz+'/estudiante/noconfirmarvisita/'+id).success(function(data) {
            swal(data.title, data.content, data.type);

            $http.get(raiz+'/estudiante/visitasjson').success(function(result){
                $scope.visitas = result;
                $('#cargando').modal('hide');
            });


        })
    }

})

dipro.controller('JefePracticantesCtrl', function($scope, $http) {

    $scope.aprobados = {};
    $scope.aprobados.ids = [];

    $http.get(raiz+'/jefe/practicantesjson').success(function(data){
        $scope.practicantes = data;
    })

    $scope.aprobarPracticante = function(id)
    {
        var index = getIndex(id);
        if(index < $scope.aprobados.ids.length)
        {
            $scope.aprobados.ids.splice(index,1 );
        }
        else
        {
            $scope.aprobados.ids.push(id);
        }
    }

    $('#verVisitas').on('show.bs.modal', function(e){
        var id = $(e.relatedTarget).data('id');
        $http.get(raiz+'/jefe/visitasjson/'+id).success(function(data){
            $scope.visitas = data;
        })
    });

    $scope.confirmarVisita = function(id)
    {
        cargando();
        $http.get(raiz+'/jefe/confirmarvisita/'+id).success(function(data) {
            swal(data.title, data.content, data.type);
            $http.get(raiz+'/jefe/visitasbyvisitajson/'+id).success(function(result){
                $scope.visitas = result;
                $('#cargando').modal('hide');
            });
        })
    }

    $scope.confirmarvisita = function(id)
    {
        cargando();
        $http.get(raiz+'/jefe/noconfirmarvisita/'+id).success(function(data) {
            swal(data.title, data.content, data.type);
            $http.get(raiz+'/jefe/visitasbyvisitajson/'+id).success(function(result){
                $scope.visitas = result;
                $('#cargando').modal('hide');
            });
        })
    }

    $scope.aprobarpracticas = function()
    {

        $http.post(raiz+'/jefe/aprobarpractcas', $scope.aprobados).success(function(data){
            swal(data.title, data.content, data.type);
            $http.get(raiz+'/jefe/practicantesjson').success(function(result){
                $scope.practicantes = result;
            });
            $('#cargando').modal('hide');
            $scope.aprobados = {};
            $scope.aprobados.ids = [];
        });

    }

    function getIndex(id)
    {
        var i =0;
        for( ; i < $scope.aprobados.ids.length; i++)
        {
            if(id == $scope.aprobados.ids[i])
            {
                break;
            }

        }
        return i;
    }

})

dipro.controller('AdminActasCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/actasjson').success(function(data){
            $scope.actas = data;
        });

        $scope.booleano = false;
    }

    cargarDatos();



    $scope.cambio = function()
    {
        $scope.booleano = true;
    }

    $('#documentos').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/admin/practicantejson/'+id).success(function(data){
            $scope.estudiante = data;
        });

    });

    $('#cambiarEstado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/admin/practicantejson/'+id).success(function(data){
            $scope.estudiante = data;
        });

        $http.get(raiz+'/admin/estadospracticasjson/'+id).success(function(data){
            $scope.estados = data;
        });

    });


    $('#cambiarEstado').on('hide.bs.modal', function(e) {
        $scope.estudiante.practica = {};
        $scope.booleano = false;
    });

    $scope.cambiarEstadoPractica = function(){
        cargando();
        $http.post(raiz+'/admin/aprobarpractica', $scope.estudiante).success(function(data){
            close_cargando();
            cargarDatos();
            $scope.errores = {};
            $('#cambiarEstado').modal('hide');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }


})

dipro.controller('AdminVerActasCtrl', function($scope, $http, $rootScope, $filter){

    $scope.acta = {};
    $scope.mostrar = true;
    $http.get(raiz+'/admin/datosactajson').success(function(data){
        $scope.datos = data;
    })

    $rootScope.$watch('acta.id', function(){
        $http.get(raiz+'/admin/infoacta/'+$scope.acta.id).success(function(data){
            $scope.acta = data;
            $scope.acta.fecha_fin = new Date(moment(data.fecha_fin));
            $scope.acta.fecha_inicio =new Date(moment(data.fecha_inicio));

            if(data.aprobacion_dippro)
            {
                $scope.mostrar=false;
            }
        })
    });

    $scope.guardarActa = function()
    {
        $scope.acta.fecha_fin =new Date(moment($('#fecha_fin_acta').val()));

        var formData = new FormData();

		var idxs = [];

		toFormData(formData, $scope.acta, idxs, $filter);

        // console.log(formData, $scope.acta);

        $http.post(raiz+'/admin/aprobaracta', formData, {

			headers: {
				"Content-type": undefined
			},

			transformRequest: angular.identity

		}).success(function(data){
            $('#cargando').modal('hide');
            swal({
                title: data.title,
                text: data.content,
                type: data.type,
                showCancelButton: false,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Ok",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: true,
                closeOnCancel: false
                },
                function(isConfirm)
                {
                    if(data.type == 'success')
                    {
                        location.href=raiz+'/admin/actas';
                    }


                });
        }).error(function(data){
            $('#cargando').modal('hide');
            $scope.errores = data;
        })

    }
})


dipro.controller('TutorIndexCtrl', function($scope, $http, $rootScope, $filter) {
    $scope.practicante = {};
    $scope.inicio = function()
    {
        $http.get(raiz+'/tutor/practicantesjson').success(function(data){
            $scope.practicantes = data;
        })
    }
    $scope.inicio();

    $('#detallesPracticante').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/tutor/practicantejson/'+id).success(function(data){
            $scope.practicante = data;
        })

    });

    $('#registrarVisita').on('show.bs.modal', function(e){
        $scope.visita={};
        var id = $(e.relatedTarget).data('id');
        $scope.visita.id = id;
    });

    $('#proyectoImpacto').on('show.bs.modal', function(e){
        var id = $(e.relatedTarget).data('id');
        $scope.proyecto = {};
        $scope.proyecto.id = id;
    });

    $('#proyectoImpacto').on('hide.bs.modal', function(e){

        $scope.proyecto = {};
        $scope.errores = {};
    });

    $('#verVisitas').on('show.bs.modal', function(e){
        var id = $(e.relatedTarget).data('id');
        $http.get(raiz+'/tutor/visitasjson/'+id).success(function(data){
            $scope.visitas = data;
        })
    });

    $('#registrarVisita').on('hide.bs.modal', function(e){
        $scope.visita={};
        $scope.errores={};
    });

    $scope.guardarVisita = function()
    {
        cargando()
        $scope.visita.strHora = $filter('date')($scope.visita.hora, 'shortTime');
        $http.post(raiz+'/tutor/registrarvisita', $scope.visita).success(function(data){
            $('#registrarVisita').modal('hide');
            swal(data.title, data.content, data.type);
            $('#cargando').modal('hide');
            $scope.inicio();
        }).error(function(data){
            $scope.errores = data
            $('#cargando').modal('hide');
        })
    }


    $scope.proyectoImpacto = function(id)
    {
        cargando();
        $http.post(raiz+'/tutor/proyectoimpacto', $scope.proyecto).success(function(data) {
            close_cargando();
            cerrar_modal('proyectoImpacto');
            swal(data.title, data.content, data.type);
            $scope.inicio();
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }

})

dipro.controller('NovIndexCtrl', function($scope, $http) {

    $scope.novedad = null;
    $scope.idNovedades = [];

    $http.get(raiz+'/novedad/usuariosjson').success(function(data) {
        if(data.estudiantes == null)
        {
            $scope.usuarios = data;
        }
        else
        {
            $scope.estudiantes = data.estudiantes;
        }

    })

    $scope.usuariosSegunEstudiante = function()
    {
        $http.get(raiz+'/novedad/usuariosbycodigoestudiante/'+$scope.newNovedad.estudiante.id).success(function(data) {
            $scope.usuarios = data;
        })
    }

    $scope.estilo={
        right:"-700px",
    }

    $http.get(raiz+'/novedad/novedadesrecibidasjson').success(function(data){
        $scope.novedades = data;
        console.log(data[0]);
        for(var i=0; i < $scope.novedades.length; i++)
        {
            if($scope.novedades[i].leida == 1)
            {
                $scope.novedades[i].class='leidas';
            }
            else
            {
                $scope.novedades[i].class='no-leidas';
            }
        }
    })

    $http.get(raiz+'/novedad/novedadesenviadasjson').success(function(data){
        $scope.enviadas = data;
        for(var i=0; i < $scope.enviadas.length; i++)
        {
            $scope.enviadas[i].class='leidas';
        }
    })

    $scope.responderNovedad = function()
    {
        // abrir();
        // $scope.newNovedad.idRespuesta = $scope.novedad.id;
        // $scope.newNovedad.asunto = "Re: "+$scope.novedad.asunto;
        // $scope.showDestinatarios = false;

        $('#nuevoMensajeModal').modal('show');
        $scope.showDestinatarios = true;
        $scope.newNovedad = {
            asunto: "Re: "+$scope.novedad.asunto,
            idRespuesta: $scope.novedad.id
        };
    }

    $scope.leerNoverdad =function(id)
    {
        $http.get(raiz+'/novedad/novedadjson/'+id).success(function(data){
            $scope.novedad = data;
        })
        var i;
        for(i=0; i < $scope.novedades.length; i++)
        {
            if($scope.novedades[i].id == id)
            {
                $scope.novedades[i].leida = 1;
                $scope.novedades[i].class = 'leidas';
                break;
            }
        }
    }

    $scope.seleccionarNovedad = function(id)
    {
        if($scope.idNovedades.length == 0)
        {
            $scope.idNovedades.push(id);
        }
        else
        {
            var index = getIndex(id);
            if(index < $scope.idNovedades.length)
            {
                $scope.idNovedades.splice(index, 1);
            }
            else
            {
                $scope.idNovedades.push(id);
            }
            // try
            // {
            //     if($scope.novedades[ getIndexRecibidas($scope.idNovedades[0]) ].recibida && $scope.novedades[ getIndexRecibidas(id) ].recibida)
            //     {
            //         var index = getIndex(id);
            //         if(index < $scope.idNovedades.length)
            //         {
            //             $scope.idNovedades.splice(index, 1);
            //         }
            //         else
            //         {
            //             $scope.idNovedades.push(id);
            //         }
            //     }
            // }
            // catch(error)
            // {
            //     if(!$scope.enviadas[ getIndexEnviadas($scope.idNovedades[0]) ].recibida && !$scope.enviadas[ getIndexEnviadas(id) ].recibida)
            //     {
            //         var index = getIndex(id);
            //         if(index < $scope.idNovedades.length)
            //         {
            //             $scope.idNovedades.splice(index, 1);
            //         }
            //         else
            //         {
            //             $scope.idNovedades.push(id);
            //         }
            //     }
            // }
        }
    }

    function getIndex(id)
    {
        var i;
        for(i=0; i< $scope.idNovedades.length; i++)
        {
            if(id == $scope.idNovedades[i])
            {
                break;
            }
        }
        return i;
    }

    function getIndexRecibidas(id)
    {
        var i;
        for(i=0; i< $scope.novedades.length; i++)
        {
            if(id == $scope.novedades[i].id)
            {
                break;
            }
        }
        return i;
    }

    function getIndexEnviadas(id)
    {
        var i;
        for(i=0; i< $scope.enviadas.length; i++)
        {
            if(id == $scope.enviadas[i].id)
            {
                break;
            }
        }
        return i;
    }

    $scope.eliminarNovedades = function()
    {
        $http.post(raiz+'/novedad/eliminiar', $scope.idNovedades).success(function(data){

            swal(data.title, data.content, data.type);
            $http.get(raiz+'/novedad/novedadesrecibidasjson').success(function(data){
                $scope.novedades = data;
                for(var i=0; i < $scope.novedades.length; i++)
                {
                    if($scope.novedades[i].leida)
                    {
                        $scope.novedades[i].class='leidas';
                    }
                    else
                    {
                        $scope.novedades[i].class='no-leidas';
                    }
                }
            })

            $http.get(raiz+'/novedad/novedadesenviadasjson').success(function(data){
                $scope.enviadas = data;
                for(var i=0; i < $scope.enviadas.length; i++)
                {
                    $scope.enviadas[i].class='leidas';
                }
            })

            $('#cargando').modal('hide');
        })

    }

    $scope.redactarNovedad = function()
    {
        // abrir();
        $('#nuevoMensajeModal').modal('show');
        $scope.showDestinatarios = true;
        $scope.newNovedad = {};
    }

    function abrir()
    {
        $scope.estilo={
            bottom:"-20px",
            right:"0",
            transition:"0.2s",
        }
        $scope.showDestinatarios = true;
        $scope.newNovedad = {};
    }

    $scope.cerrarNovedad = function()
    {
        cerrar();
    }

    function cerrar()
    {
        $scope.estilo={
            right:"-700px",
            transition:"0.2s",
        }
        $scope.newNovedad = {};
    }

    $scope.enviarNovedad = function()
    {
        $http.post(raiz+'/novedad/enviarnovedad', $scope.newNovedad).success(function(data){
            if(data.type!='error')
            {
                cerrar();
            }
            $('#cargando').modal('hide');
            $('#nuevoMensajeModal').modal('hide');

            swal({
                title: data.title,
                text: data.content,
                type: data.type
            }, function() {
                window.location.href = raiz+'/novedad';
            });
        })
    }

    $scope.minimizarNuevaNovedad = function()
    {
        if(!$scope.showDestinatarios)
        {
            $scope.estilo={
                bottom:"-335px",
                transition:"0.5s",
            }
        }
        else if($scope.estudiantes == null)
        {
            $scope.estilo={
                bottom:"-377px",
                transition:"0.5s",
            }
        }
        else
        {
            $scope.estilo={
                bottom:"-422px",
                transition:"0.5s",
            }
        }

    }

    $scope.maximizarNuevaNovedad = function()
    {
        $scope.estilo={
            bottom:"-20px",
            transition:"0.5s",
        }
    }
})


dipro.controller('AdminEvaluacionesCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    $http.get(raiz+'/adminsil/evaluacion').success(function (data){
        $scope.evaluaciones = data;
    })

    $scope.cambiarEstado = function(id)
    {
        cargando()
        $http.get(raiz+'/adminsil/cambiarestadoevaluacion/'+id).success(function(data){
            $http.get(raiz+'/adminsil/evaluacion').success(function (result){
                $scope.evaluaciones = result;
                $('#cargando').modal('hide');
            });
        });
    }
})

dipro.controller('AdminCrearEvaluacionCtrl', function($scope, $http, $rootScope) {

    $scope.nuevaSeccion = {};
    $scope.evaluacion = {};
    $scope.evaluacion.getrolevaluado = {};
    $scope.evaluacion.getrolevaluador = {};

    $rootScope.$watch('id', function(){
        $http.get(raiz+'/adminsil/evaluacion/'+$scope.id).success(function(data){
           $scope.evaluacion = data;
        });
    })

    $http.get(raiz+'/adminsil/datosevaluacion').success(function(data){
       $scope.datos = data;
    });



    $scope.guardarEvaluacion = function()
    {
        $http.post(raiz+'/adminsil/saveeval', $scope.evaluacion).success(function(data){
            if(data.msj.type=='success')
            {
                location.href = '/adminsil/crearevaluacion/'+data.evaluacion.id;
            }
            else
            {
                swal(data.msj.title, data.msj.content, data.msj.type);
                $('#cargando').modal('hide');
                $scope.evaluacion = data.evaluacion;
                $scope.errorEval = {};
            }

        }).error(function(data){
            $scope.errorEval = data;
            $('#cargando').modal('hide');
        });
    }

    $scope.guardarSeccion = function()
    {
        $scope.nuevaSeccion.idEvaluacion = $scope.evaluacion.id;
        $http.post(raiz+'/adminsil/saveseccion', $scope.nuevaSeccion).success(function(data){
            swal(data.msj.title, data.msj.content, data.msj.type);
            if(data.msj.type !="error")
            {
                $('#newSeccion').modal('hide');
                $scope.nuevaSeccion = {};
            }
            $('#cargando').modal('hide');
            $scope.evaluacion = data.evaluacion;
            $scope.erroresSeccion = {};
        }).error(function(data){
            $scope.erroresSeccion = data;
            $('#cargando').modal('hide');
        });
    }

    $scope.cambioTipoPregunta = function()
    {
        if($scope.nuevaPregunta.gettipo.nombre=="Cuantitativa")
        {
            $scope.modalBody = {
                'min-height':"335px",
            }
        }
        else
        {
            $scope.modalBody = {
                'min-height':"auto",
            }
        }
    }

    $('#newSeccion').on('show.bs.modal', function(e){
        var id = $(e.relatedTarget).data('id');
        if(id != null)
        {
            $http.get(raiz+'/adminsil/seccion/'+id).success(function(data) {
                $scope.nuevaSeccion = data;
            })
        }

    });

    $('#newSeccion').on('hide.bs.modal', function(e){
        $scope.nuevaSeccion = {};
    });

    $('#newPregunta').on('show.bs.modal', function(e){
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/adminsil/seccionesbyeval/'+$scope.evaluacion.id).success(function(data){
            $scope.secciones = data;
        })

        $scope.opcionRespuesta = {};

        $scope.modalBody = {
            'min-height':"auto",
        }
        $scope.nuevaPregunta = {};
        $scope.nuevaPregunta.respuestas = [];



        if(id != null)
        {
            $http.get(raiz+'/adminsil/pregunta/'+id).success(function(data) {
                $scope.nuevaPregunta = data;
            })
        }


    })

    $scope.eliminarSeccion = function(id)
    {
        $http.get(raiz+'/adminsil/eliminarseccion/'+id).success(function(data){
            swal(data.msj.title, data.msj.content, data.msj.type);
            $('#cargando').modal('hide');
            $scope.evaluacion = data.evaluacion;

        })
    }

    $scope.eliminarPregunta = function(id)
    {
        $http.get(raiz+'/adminsil/eliminarpregunta/'+id).success(function(data){
            swal(data.msj.title, data.msj.content, data.msj.type);
            $('#cargando').modal('hide');
            $scope.evaluacion = data.evaluacion;
        })
    }

    $scope.hola = function()
    {
        console.log($scope.evaluacion);
    }

    $scope.agregarRespuesta = function()
    {
        cargando()
        var index = getIndex($scope.opcionRespuesta.nombre);
        var json ={};
        json.respuestas = $scope.nuevaPregunta.respuestas;
        json.opcion = $scope.opcionRespuesta;


        // if(index < $scope.nuevaPregunta.getpivoterespuesta.length)
        // {
        //     swal('Error', 'No se pueden repetir las opciones', 'error');
        // }else
        // {
            $http.post(raiz+'/adminsil/saveopcionrespuesta', json).success(function(data) {
                $scope.nuevaPregunta.respuestas = data.respuestas;
                $scope.datos.posiblesRespuestas = data.posiblesRespuestas;
                $scope.opcionRespuesta = {};
                $('#cargando').modal('hide');
            }).error(function(data) {
                swal('Error', data['opcion.nombre'][0], 'error');
                $('#cargando').modal('hide');
            })
            $scope.opcionRespuesta = {};
        // }

    }

    $scope.eliminarOpcion = function(nombre)
    {
        var index = getIndex(nombre);
        $scope.nuevaPregunta.getposiblesrespuestas.splice(index,1 );
    }

    $scope.enterPress = function($event)
    {
        var keyCode = $event.which || $event.keyCode;
        if(keyCode == 13)
        {
            $scope.agregarRespuesta();
        }
    }

    function getIndex(nombre)
    {
        var i=0;
        for(i=0;i<$scope.nuevaPregunta.respuestas.length; i++)
        {
            if(nombre == $scope.nuevaPregunta.respuestas[i].nombre)
            {
                break;
            }
        }
        return i;
    }

    $scope.guardarPregunta = function()
    {
        $http.post(raiz+'/adminsil/savepregunta', $scope.nuevaPregunta).success(function(data){
            swal(data.msj.title, data.msj.content, data.msj.type);
            if(data.msj.type !="error")
            {
                $('#newPregunta').modal('hide');
                $scope.nuevaPregunta = {};
            }
            $('#cargando').modal('hide');
            $scope.evaluacion = data.evaluacion;
            $scope.erroresPregunta = {};
        }).error(function(data){
            $scope.erroresPregunta = data;
            $('#cargando').modal('hide');
        });
    }
})

dipro.controller('EvalResponderCtrl', function($scope, $http, $rootScope) {
    $rootScope.$watch('id', function(){
        $http.get(raiz+'/evaluacion/evaluacion/'+$scope.id).success(function(data){
           $scope.evaluacion = data;
        });
    })

    $scope.guardarEvaluacion = function()
    {
        cargando()
        $scope.evaluacion.idEvaluado = $scope.idEvaluado;
        $http.post(raiz+'/evaluacion/saverespuestasevaluacion', $scope.evaluacion).success(function(data){
            $('#cargando').modal('hide');
            if(data.type=="error")
            {
                swal(data.title, data.content, data.type);
            }
            else
            {
                swal({
                    title: data.title,
                    text: data.content,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Ok",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        location.href=raiz+'/home';

                    });
            }

        })
    }

})

dipro.controller('AdminPracticantesCtrl', function($scope, $http) {
    // $scope.ofertas={};
    $scope.raiz = raiz;
    $http.get(raiz+'/admin/practicantesjson').success(function(data){
        $scope.practicantes = data;
    });

    $scope.filtrar=function()
    {
        cargando();
        $scope.filtro_impacto = null;
        $http.get(raiz+'/admin/practicantesjson/'+$scope.filtro).success(function(data){
            $scope.practicantes = data;
            $('#cargando').modal('hide');
        });
    }

    $scope.filtrar_impacto=function()
    {
        cargando()
        $scope.filtro = null;
        $http.get(raiz+'/admin/practicantesjson/'+$scope.filtro_impacto).success(function(data){
            $scope.practicantes = data;
            $('#cargando').modal('hide');
        });
    }

    $('#postular').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.idEstudiante = id;
        $http.get(raiz+'/admin/ofertasbyestudiante/'+id).success(function(data){
            $scope.ofertas = data;
        })
    });

    $scope.postularEst = function(idOfer)
    {
        cargando()
        var request = {
            idEstudiante:$scope.idEstudiante,
            idOferta:idOfer
        };

        $http.post(raiz+'/admin/postularestudiante', request).success(function(data){
            swal(data.title, data.content, data.type);

            $http.get(raiz+'/admin/practicantesjson').success(function(data){
                $scope.practicantes = data;
            });

            $http.get(raiz+'/admin/ofertasbyestudiante/'+$scope.idEstudiante).success(function(data){
                $scope.ofertas = data;
                $('#cargando').modal('hide');
            })
        });

    }

    $('#verVisitas').on('show.bs.modal', function(e){
        var id = $(e.relatedTarget).data('id');
        $http.get(raiz+'/admin/visitasjson/'+id).success(function(data){
            $scope.visitas = data;
        })
    });

    $('#verNovedades').on('show.bs.modal', function(e){
        var codigo = $(e.relatedTarget).data('codigo');
        cargando();
        $http.get(raiz+'/novedad/novedadesrecibidasbyestudiantejson/'+codigo).success(function(data){
            close_cargando();
            $scope.novedades = data;
        })
    });

})

dipro.controller('AdminCrearofertaCtrl', function($scope, $http, $rootScope) {

    $http.get(raiz+'/adminsil/formulariocrearofertajson').success(function(data){
        $scope.formulario = data;
    })

    $scope.oferta = {};
    $scope.errores = {};

    $rootScope.$watch('oferta.id', function(){
        if($scope.oferta.id != null)
        {
            $http.get(raiz+'/adminsil/ofertajson/'+$scope.oferta.id).success(function(data) {
                data.fechacierre =new Date(moment(data.fechacierre));
                $scope.oferta = data;
                $scope.oferta.empresa = data.getjefe.getsede.getempresa;
                $scope.buscarEst();
                if($scope.oferta.salud)
                {
                    $scope.oferta.saluds = true;
                }
                else
                {
                    $scope.oferta.saludn = true;
                }

                if($scope.oferta.arl)
                {
                    $scope.oferta.arls = true;
                }
                else
                {
                    $scope.oferta.arln = true;
                }
            });
        }
    });

    $scope.jefesbyempresa = function()
    {
        $http.get(raiz+'/adminsil/jefesbyempresa/'+$scope.oferta.empresa.id).success(function(data){
            $scope.formulario.jefes = data;
        });
    }

    $scope.saluds = function()
    {
        if($scope.oferta.saluds)
        {
            $scope.oferta.saludn = false;
        }
    }

    $scope.saludn = function()
    {
        if($scope.oferta.saludn)
        {
            $scope.oferta.saluds = false;
        }
    }

    $scope.arls = function()
    {
        if($scope.oferta.arls)
        {
            $scope.oferta.arln = false;
        }
    }

    $scope.arln = function()
    {
        if($scope.oferta.arln)
        {
            $scope.oferta.arls = false;
        }
    }

    $scope.guardarOferta = function()
    {
        cargando()
        if($scope.oferta.saluds)
        {
            $scope.oferta.salud = true;
        }
        else if($scope.oferta.saludn)
        {
            $scope.oferta.salud = false;
        }

        if($scope.oferta.arls)
        {
            $scope.oferta.arl = true;
        }
        else if($scope.oferta.arln)
        {
            $scope.oferta.arl = false;
        }

        $http.post(raiz+'/adminsil/saveoferta', $scope.oferta).success(function(data){

            if(data.type == 'error')
            {
                swal(data.title, data.content, data.type);
            }
            else if(data.type == 'success')
            {

                swal({
                    title: data.title,
                    text: data.content,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Ok",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        location.href=raiz+'/adminsil/ofertas';
                    });

            }
            $('#cargando').modal('hide');
        }).error(function(data){
            $scope.errores=data;
            $('#cargando').modal('hide');
        });
        // console.log($scope.oferta.programas);
    }

    $scope.buscarEst = function()
    {
        $http.post(raiz+'/adminsil/buscarestudiantesbyprogramas', $scope.oferta.programas).success(function(data){
            $scope.formulario.practicantes = data;
        });
    }
})

dipro.controller('JefeVerActasCtrl', function($scope, $http){

    $scope.acta = {};
    $scope.mostrar = true;

    $scope.aprobarPractica = function()
    {
        cargando()
        $http.get(raiz+'/jefe/aprobaracta/'+$scope.acta.id).success(function(data){
            $('#cargando').modal('hide');
            swal({
                title: data.title,
                text: data.content,
                type: data.type,
                showCancelButton: false,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Ok",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: true,
                closeOnCancel: false
                },
                function(isConfirm)
                {
                    if(data.type == 'success')
                    {
                        location.href=raiz+'/jefe';
                    }


                });
        }).error(function(data){
            $scope.errores = data;
        })

    }
})

dipro.controller('EmpConveniosCtrl', function($scope, $http) {

    function cargarDatos()
    {
        $http.get(raiz+'/empresa/conveniosjson').success(function(data){
            $scope.convenios = data;
        });

        $http.get(raiz+'/empresa/ultimoconvenio').success(function(data){
            $scope.ultimoConvenio = data;
            console.log($scope.ultimoConvenio);
        });
    }

    cargarDatos();

    $scope.solicitarConvenio = function()
    {
        cargando();
        $http.get(raiz+'/empresa/solicitarconvenio').success(function(data){
            close_cargando();
            cargarDatos();
            swal(data.title, data.content, data.type);
        })
    }

    $scope.enviar = function(id)
    {
        cargando();
        $http.get(raiz+'/empresa/enviaradipro/'+id).success(function(data){
            close_cargando();
            cargarDatos();
            swal(data.title, data.content, data.type);
        })
    }

})

dipro.controller('AdminConveniosCtrl', function($scope, $http, $filter) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/conveniosjson').success(function(data){
            $scope.convenios = data;
        })
    }

    cargarDatos();

    $scope.aprobarConvenio = function(id)
    {
        cargando();
        $http.get(raiz+'/admin/aprobarconvenio/'+id).success(function(data){
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        })
    }

    $('#detallesEmpresa').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/adminsil/datosempresa/'+id).success(function(data){
            $scope.empresa = data;
        });
    });

    $('#detallesEmpresa').on('hide.bs.modal', function(e) {
        $scope.empresa = {};
    });

    $('#suscribirConvenio').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $scope.convenio ={};
        $scope.convenio.id = id;

    });

    $('#suscribirConvenio').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
    });

    $('#renovarConvenio').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $scope.convenio ={};
        $scope.convenio.id = id;
        $scope.errores = {};

    });

    $('#renovarConvenio').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
        $scope.errores = {};
    });

    $scope.noAprobarConvenio = function(id)
    {
        cargando();
        $http.get(raiz+'/admin/noaprobarconvenio/'+id).success(function(data){
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        })
    }
    $('#adjuntarMinuta').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
        $scope.errores = {};
    });

    $('#adjuntarMinuta').on('show.bs.modal', function(e){
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/admin/conveniosjson/'+id).success(function(data){
            $scope.convenio = data;
        });
    });


    $scope.revisionDocs = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);
        $http.post(raiz+'/admin/conveniorevisado', formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data){
            close_cargando();
            $('#adjuntarMinuta').modal('hide');
            swal(data.title, data.content, data.type);
            cargarDatos();
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }

    $scope.firmarEmpresa = function(id)
    {
        cargando();

        $http.get(raiz+'/admin/enviarafirma/'+id).success(function(data) {
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        });

    }

    $scope.recepcionDippro = function(id)
    {
        cargando();
        $http.get(raiz+'/admin/recepciondippro/'+id).success(function(data) {
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        });
    }

    $scope.suscribir_convenio = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);

        $http.post(raiz+'/admin/suscribirconvenio', formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data){
            close_cargando();
            cargarDatos();
            $('#suscribirConvenio').modal('hide');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores=data;
        });
    }

    $scope.renovar_convenio = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);
        $http.post(raiz+'/admin/renovarconvenio', formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data){
            close_cargando();
            $('#renovarConvenio').modal('hide');
            swal(data.title, data.content, data.type);
            cargarDatos();
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})



dipro.controller('EmpSubirDocsCtrl', function($scope, $http, $rootScope, $filter) {

    $rootScope.$watch('convenio.id', function(){
        $http.get(raiz+'/empresa/conveniosjson/'+$scope.convenio.id).success(function(data){
            $scope.convenio = data;
            console.log($scope.convenio);
        })
    })

    $scope.subirDocs = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);
		$http.post("/empresa/subirdocs", formData, {
			headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
		})
        .success(function(data){
            close_cargando();
            if(data.type=="success")
            {
                swal({
                    title: data.title,
                    text: data.content,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Ok",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        location.href=raiz+'/empresa/convenio';

                    });
            }
            else
            {
                swal(data.title, data.content, data.type);
            }

        })
        .error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})

dipro.controller('JuridicaConveniosCtrl', function($scope, $http, $filter) {

    function cargarDatos()
    {
        $http.get(raiz+'/juridica/conveniosjson').success(function(data){
            $scope.convenios = data;
        })
    }

    cargarDatos();

    $('#adjuntarMinuta').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
    });

    $('#adjuntarMinuta').on('show.bs.modal', function(e){
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/juridica/conveniosjson/'+id).success(function(data){
            $scope.convenio = data;
        });
    });


    $scope.revisionDocs = function()
    {
        cargando();

        $http.post(raiz+'/juridica/conveniorevisado', $scope.convenio).success(function(data){
            close_cargando();
            $('#adjuntarMinuta').modal('hide');
            swal(data.title, data.content, data.type);
            cargarDatos();
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})

dipro.controller('EstOtrasLegalizarCtrl', function($scope, $http, $filter) {

    function cargarDatos()
    {
        $scope.practica = {};
        $http.get(raiz+'/estudiante/practicantejson').success(function(data){
            $scope.estudiante = data;
        });

        $http.get(raiz+'/estudiante/ciudadesjson').success(function(data){
            $scope.ciudades = data;
        });
    }

    cargarDatos();

    $scope.legalizar = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];

		console.log($scope.practica);

		toFormData(formData, $scope.practica, idxs, $filter);

        // console.log(formData);

        $http.post("/estudiante/otraslegalizar", formData, {
			headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
		}).success(function(data){
            close_cargando();
            swal({
                title: data.title,
                text: data.content,
                type: data.type,
                showCancelButton: false,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Ok",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
                },
                function(isConfirm)
                {
                    location.href=raiz+'/estudiante/practicas';

                });
        }).error(function(data){
            $scope.errores = data;
            close_cargando();
            // swal(data.title, data.content, data.type);
        });

    }
})

dipro.controller('EstOtrasPracticaCtrl', function($scope, $http) {
    $http.get(raiz+'/estudiante/practicantejson').success(function(data){
        $scope.estudiante = data;
    });
})

dipro.controller('programaIndexCtrl', function($scope, $http) {
    function cargarDatos()
    {
        $http.get(raiz+'/programa/practicantesjson').success(function(data){
            $scope.practicantes = data;
        });

        $http.get(raiz+'/programa/datosprogramajson').success(function(data){
            $scope.programa = data;
            console.log($scope.programa);
        })

        $scope.mostrar = false;
        $scope.errores = {};
    }

    $scope.cambiarCodigoPRacticas = function()
    {
        cargando();
        $http.post(raiz+'/programa/savecodigo', $scope.programa).success(function(data){
            close_cargando();
            cargarDatos();
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }


    cargarDatos();
})

dipro.controller('practicantesOriCtrl', function($scope, $http) {
    function cargarDatos()
    {
        $http.get(raiz+'/ori/practicantesjson').success(function(data){
            $scope.practicantes = data;
        });
    }

    cargarDatos();

    $scope.booleano = false;

    $scope.cambio = function()
    {
        $scope.booleano = true;
    }

    $('#cambiarEstado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/ori/practicantesjson/'+id).success(function(data){
            $scope.practicante = data;
        });

    });


    $('#cambiarEstado').on('hide.bs.modal', function(e) {
        $scope.practicante.practica = {};
        $scope.booleano = false;
    });


    $scope.cambiarEstadoPractica = function(){
        cargando();
        $http.post(raiz+'/ori/aprobarpractica', $scope.practicante).success(function(data){
            close_cargando();
            cargarDatos();
            $scope.errores = {};
            $('#cambiarEstado').modal('hide');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})

dipro.controller('AdminPrepracticasCtrl', function($scope, $http) {
    $scope.cargarDatos = function()
    {
        $http.get(raiz+'/admin/prepracticasjson').success(function(data){
            $scope.estudiantes = data;
        })
    }

    $scope.cargarDatos();

    $scope.estudiantes_id = [];

    $scope.seleccionar = function(id)
    {
        var index = buscar(id);
        if(index < $scope.estudiantes_id.length)
        {
            $scope.estudiantes_id.splice(index, 1);
        }
        else
        {
            $scope.estudiantes_id.push(id);
        }
    }

    function buscar(id)
    {
        var i = 0;

        for(i=0; i < $scope.estudiantes_id.length; i++)
        {
            if($scope.estudiantes_id[i] == id)
            {
                break;
            }
        }

        return i;
    }

    $scope.seleccionarTodo = function()
    {
        for(var i=0; i< $scope.estudiantes.length; i++)
        {
            var id = $scope.estudiantes[i].id;
            var index = buscar(id);
            if(index >= $scope.estudiantes_id.length)
            {
                $scope.estudiantes[i].seleccionado = true;
                $scope.estudiantes_id.push(id);
            }
        }
    }

    $scope.quitarSeleccion = function()
    {
        $scope.estudiantes_id = [];
        for(var i=0; i< $scope.estudiantes.length; i++)
        {
            $scope.estudiantes[i].seleccionado = false;
        }
    }

    $scope.aprobarPrepracticas = function()
    {
        cargando();

        $http.post(raiz+'/admin/aprobarprepracticas', $scope.estudiantes_id).success(function(data){
            close_cargando();
            swal(data.title, data.content, data.type);
            $scope.cargarDatos();
            $scope.estudiantes_id = [];
        });
    }
})

dipro.controller('AdminCharlasCtrl', function($scope, $http, $filter) {
    $scope.raiz = raiz;

    function inicializar()
    {
        $scope.mostrar = false;

        $scope.charla = {};

        $scope.charla.getconferencia = {};
        $scope.charla.getorador = {};
    }

    function cargarDatos()
    {
        $scope.filtro = {};
        $http.get(raiz+'/admin/periodosjson').success(function(data){
            $scope.periodos = data;
        })

        $http.get(raiz+'/admin/charlasjson').success(function(data){
            $scope.charlas = data;
        })

        $http.get(raiz+'/admin/programasjson').success(function(data){
            $scope.programas = data;
        })

        $http.get(raiz+'/admin/conferenciasjson').success(function(data){
            $scope.conferencias = data;
        })

        $http.get(raiz+'/admin/conferenciasjson2').success(function(data){
            $scope.conferencias2 = data;
        })

        $http.get(raiz+'/admin/prepracticantesjson').success(function(data){
            $scope.estudiantes = data;
        })
    }
    inicializar();
    cargarDatos();

    $scope.guardarCharla = function()
    {
        // cargando();
        $scope.charla.str_hora_inicial = $filter('date')($scope.charla.hora_inicial, 'shortTime');
        $scope.charla.str_hora_final = $filter('date')($scope.charla.hora_final, 'shortTime');
        $http.post(raiz+'/admin/savecharla', $scope.charla).success(function(data){
            // close_cargando();
            cerrar_modal('crearCharla');
            cargarDatos();
            inicializar();
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }

    $scope.buscarPersona = function()
    {

        // cargando();
        var identificacion = $scope.charla.getorador.identificacion;
        $http.get(raiz+'/admin/persona/'+identificacion).success(function(data){
            // close_cargando();
            if(data.length > 0)
            {
                $scope.charla.getorador = data[0];
            }
            else
            {
                $scope.charla.getorador = {};
                $scope.charla.getorador.identificacion = identificacion;
                swal("Busqueda vacia", "No se encontró una persona con esa identificación. Por favor complete los datos de conferencista para guardarlos", "info");
                $scope.mostrar = true;
            }
            // $('#crearCharla').focus();
        });
    }

    $('#crearCharla').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        var editar = $(e.relatedTarget).data('editar');
        $scope.charla.getconferencia.id = id;
        if(id != null && editar)
        {
            $http.get(raiz+'/admin/charlajson/'+id).success(function(data) {
                $scope.charla = data;
                $scope.charla.editar = true;
                $scope.charla.id = id;
                $scope.charla.fecha = new Date(moment(data.fecha));
                $scope.charla.hora_inicial = getTime(data.horaInicial);
                $scope.charla.hora_final = getTime(data.horaFinal);
                console.log($scope.charla);
                // $scope.charla.hora_final = new Date(moment($scope.charla.horaFinal));
            })
        }
    });

    $('#addHorario').on('hide.bs.modal', function(e) {
        inicializar();
    });

    $('#lista').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.lista = {};
        $scope.lista.idCharla = id;
    });

    $('#lista').on('hide.bs.modal', function(e) {
        $scope.lista = {};
    });

    $('#asistencia').on('show.bs.modal', function(e) {
        $scope.asistencia = {};
        $scope.errores = {};
    });

    $('#asistencia').on('hide.bs.modal', function(e) {
        $scope.asistencia = {};
        $scope.errores = {};
    });

    $('#mdlCalificacion').on('show.bs.modal', function(e) {
        inicializar();
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/admin/charlajson/'+id).success(function(data) {
            $scope.charla = data;
        })
    });

    $('#mdlCalificacion').on('hide.bs.modal', function(e) {
        inicializar();
    });

    $scope.guardarHorario = function()
    {
        // cargando();
        $scope.charla.str_hora_inicial = $filter('date')($scope.charla.hora_inicial, 'shortTime');
        $scope.charla.str_hora_final = $filter('date')($scope.charla.hora_final, 'shortTime');
        $http.post(raiz+'/admin/addhorario', $scope.charla).success(function(data){
            // close_cargando();
            cerrar_modal('addHorario');
            cargarDatos();
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }

    $scope.filtrarPeriodo = function()
    {
        cargando();
        $http.get(raiz+'/admin/charlasjson/'+$scope.filtro.periodo.id).success(function(data){
            close_cargando();
            $scope.charlas = data;
        })
    }

    $scope.generarLista = function()
    {
        window.open(raiz+'/admin/generarlista/'+$scope.lista.idCharla+'/'+$scope.lista.programa.id);
    }

    $scope.guardarAsistencia = function ()
    {
        cargando();
        console.log($scope.asistencia);
        $http.post(raiz+'/admin/guardarasistencia', $scope.asistencia).success(function(data){
            close_cargando();
            cerrar_modal('asistencia');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }

    $scope.verAsistencia = function(id)
    {
        cargando();
        $http.get(raiz+'/admin/asistentesjson/'+id).success(function(data) {
            $scope.asistentes = data;
            close_cargando();
            open_modal('mdlAsistentes');
        });
    }
})


dipro.controller('EstConferenciasCtrl', function($scope, $http) {
    function cargarDatos()
    {
        $http.get(raiz+'/estudiante/conferenciasjson').success(function(data){
            $scope.conferencias = data;
        })

        $http.get(raiz+'/estudiante/faltantejson').success(function(data){
            $scope.faltante = data;
        })
    }
    cargarDatos();

    $scope.agregarConferencia = function(id)
    {
        cargando();
        $http.get(raiz+'/estudiante/addconferencia/'+id).success(function(data){
            close_cargando();
            cargarDatos();
            swal(data.title, data.content, data.type);
        })
    }
})

dipro.controller('EstHorarioCtrl', function($scope, $http) {
    function cargarDatos()
    {
        $http.get(raiz+'/estudiante/asistenciasjson').success(function(data){
            $scope.conferencias = data;
        })
    }
    cargarDatos();

    $('#calificar').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.calificacion = {};
        $scope.calificacion.idAsistencia = id;
        $scope.errores={};
    });

    $('#calificar').on('hide.bs.modal', function(e) {
        if($scope.data != null)
        {
            swal({
                title: $scope.data.title,
                text: $scope.data.content,
                type: $scope.data.type,
                showCancelButton: false,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Ok",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
                },
                function(isConfirm)
                {
                    document.location = '/estudiante/horario';
                });
        }
    });

    $scope.unaestrella = function()
    {
        $scope.calificacion.valor = 1;
    }
    $scope.dosestrella = function()
    {
        $scope.calificacion.valor = 2;
    }
    $scope.tresestrella = function()
    {
        $scope.calificacion.valor = 3;
    }
    $scope.cuatroestrella = function()
    {
        $scope.calificacion.valor = 4;
    }
    $scope.cincoestrella = function()
    {
        $scope.calificacion.valor = 5;
    }

    $scope.guardarCalificacion = function()
    {
        cargando();
        $http.post(raiz+'/estudiante/calificarconferencia', $scope.calificacion).success(function(data){
            close_cargando();
            $scope.data = data;
            cerrar_modal('calificar');
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }
})

dipro.controller('AdminCartasCtrl', function($scope, $http) {

    function cargarDatos()
    {
        $http.get(raiz+'/admin/cartasjson').success(function(data){
            $scope.cartas = data;
        })

        $http.get(raiz+'/admin/estadoscartajson').success(function(data){
            $scope.estados = data;
        })
    }

    cargarDatos();

    $('#mdlEstado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.carta = {};
        $scope.carta.id = id;
        $scope.errores={};
    });

    $('#mdlEstado').on('hide.bs.modal', function(e) {
        $scope.carta = {};
        $scope.errores={};
    });

    $scope.cambiarEstadoCarta = function()
    {
        cargando();
        $http.post(raiz+'/admin/cambiarestadocarta', $scope.carta).success(function(data){
            close_cargando();
            cargarDatos();
            cerrar_modal('mdlEstado');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})

dipro.controller('homeCartasCtrl', function($scope, $http){
    $scope.enlace = null;

    $scope.verificarCarta = function()
    {
        $scope.enlace = '/home/validar/'+$scope.carta.codigo;
    }
})


dipro.controller('CdnSolicitantesCtrl', function($scope, $http) {
    $scope.rechazar = {};
    $scope.errores = {};
    $scope.seleccionados = {};
    $scope.seleccionados.seleccionados = [];

    $http.get(raiz+'/cdn/solicitantesjson').success(function(data){
        $scope.solicitantes = data;
    });

    $scope.agregarSelccion = function(id)
    {
        var index = buscar(id);
        if(index < $scope.seleccionados.seleccionados.length)
        {
            $scope.seleccionados.seleccionados.splice(index,1 );
        }
        else
        {
            $scope.seleccionados.seleccionados.push(id);
        }
    }

    function buscar(id)
    {
        var i=0;
        for(i=0; i<$scope.seleccionados.seleccionados.length; i++)
        {
            if(parseInt(id) == parseInt($scope.seleccionados.seleccionados[i]))
            {
                break;
            }
        }

        return i;
    }

    $scope.aprobarpracticasmultiple = function()
    {
        $http.post(raiz+'/cdn/aprobarpracticasmultiple', $scope.seleccionados).success(function(data) {
            swal(data.title, data.content, data.type);
            $scope.seleccionados = {};
            $scope.seleccionados.seleccionados = [];
            $http.get(raiz+'/cdn/solicitantesjson').success(function(data){
                $scope.solicitantes = data;
                $('#cargando').modal('hide');
            });
        })
    }

    $('#rechazarPracticas').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.rechazar.id = id;
    });

    $scope.rechazarPracticas = function()
    {
        cargando();
        $http.post(raiz+'/cdn/rechazarpracticas', $scope.rechazar).success(function(data){
            $('#rechazarPracticas').modal('hide');
            close_cargando();
            $http.get(raiz+'/cdn/solicitantesjson').success(function(data){
                $scope.solicitantes = data;
            });
            swal(data.title, data.content, data.type);
        }).error(function(data){
            $scope.errores = data;
        })
    }
});


dipro.controller('CdnPracticantesCtrl', function($scope, $http) {
    // $scope.ofertas={};
    $http.get(raiz+'/cdn/practicantesjson').success(function(data){
        $scope.practicantes = data;
    });

    $scope.filtrar=function()
    {
        cargando();
        $scope.filtro_impacto = null;
        $http.get(raiz+'/cdn/practicantesjson/'+$scope.filtro).success(function(data){
            $scope.practicantes = data;
            $('#cargando').modal('hide');
        });
    }

    $scope.filtrar_impacto=function()
    {
        cargando()
        $scope.filtro = null;
        $http.get(raiz+'/cdn/practicantesjson/'+$scope.filtro_impacto).success(function(data){
            $scope.practicantes = data;
            $('#cargando').modal('hide');
        });
    }

    $('#postular').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.idEstudiante = id;
        $http.get(raiz+'/cdn/ofertasbyestudiante/'+id).success(function(data){
            $scope.ofertas = data;
        })
    });

    $scope.postularEst = function(idOfer)
    {
        cargando()
        var request = {
            idEstudiante:$scope.idEstudiante,
            idOferta:idOfer
        };

        $http.post(raiz+'/cdn/postularestudiante', request).success(function(data){
            swal(data.title, data.content, data.type);

            $http.get(raiz+'/cdn/practicantesjson').success(function(data){
                $scope.practicantes = data;
            });

            $http.get(raiz+'/cdn/ofertasbyestudiante/'+$scope.idEstudiante).success(function(data){
                $scope.ofertas = data;
                $('#cargando').modal('hide');
            })
        });

    }

    $('#verVisitas').on('show.bs.modal', function(e){
        var id = $(e.relatedTarget).data('id');
        $http.get(raiz+'/cdn/visitasjson/'+id).success(function(data){
            $scope.visitas = data;
        })
    });

    $('#verNovedades').on('show.bs.modal', function(e){
        var codigo = $(e.relatedTarget).data('codigo');
        cargando();
        $http.get(raiz+'/novedad/novedadesrecibidasbyestudiantejson/'+codigo).success(function(data){
            close_cargando();
            $scope.novedades = data;
        })
    });

})

dipro.controller('CdnHojaCtrl', function($scope, $http, $rootScope) {
    $rootScope.$watch('idEstudiante', function(){
        $http.get(raiz+'/cdn/hojajson/'+$scope.idEstudiante).success(function(data){
            $scope.estudiante = data;
        });
    });
})

dipro.controller('CdnOfertasCtrl', function($scope, $http){

    $http.get(raiz+'/cdn/ofertasjson').success(function(data){
        $scope.ofertas=data;
    });

    $('#detallesOferta').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/cdn/ofertajson/'+id).success(function(data){
            $scope.oferta = data;
        });
    });

     $('#cambiarestado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        var estado = $(e.relatedTarget).data('estado');

        $scope.erroresEstado = {};
        $scope.oferta={};
        $scope.oferta.id = id;
        $http.get(raiz+'/cdn/estadosoferta/'+id).success(function(data){
            $scope.estados = data;
        });


    });

    $scope.cambiarEstado = function ()
    {
        $http.post(raiz+'/cdn/cambiarestadooferta', $scope.oferta).success(function(data){
            $('#cambiarestado').modal('hide');
            swal(data.title, data.content, data.type);

            $http.get(raiz+'/cdn/ofertasjson').success(function(data){
                $scope.ofertas=data;
            });
        }).error(function(data){
            $scope.erroresEstado = data;
        });
    }
});

dipro.controller('CdnActasCtrl', function($scope, $http) {

    $http.get(raiz+'/cdn/actasjson').success(function(data){
        $scope.actas = data;
    });

    $scope.booleano = false;

    $scope.cambio = function()
    {
        $scope.booleano = true;
    }

    $('#documentos').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/cdn/practicantejson/'+id).success(function(data){
            $scope.estudiante = data;
        });

    });

    $('#cambiarEstado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/cdn/practicantejson/'+id).success(function(data){
            $scope.estudiante = data;
        });

        $http.get(raiz+'/cdn/estadospracticasjson/'+id).success(function(data){
            $scope.estados = data;
        });

    });


    $('#cambiarEstado').on('hide.bs.modal', function(e) {
        $scope.estudiante.practica = {};
        $scope.booleano = false;
    });

    $scope.cambiarEstadoPractica = function(){
        cargando();
        $http.post(raiz+'/cdn/aprobarpractica', $scope.estudiante).success(function(data){
            close_cargando();
            $scope.errores = {};
            $('#cambiarEstado').modal('hide');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }


})

dipro.controller('CdnVerActasCtrl', function($scope, $http, $rootScope, $filter){

    $scope.acta = {};
    $scope.mostrar = true;
    $http.get(raiz+'/cdn/datosactajson').success(function(data){
        $scope.datos = data;
    })

    $rootScope.$watch('acta.id', function(){
        $http.get(raiz+'/cdn/infoacta/'+$scope.acta.id).success(function(data){
            $scope.acta = data;
            $scope.acta.fecha_fin = new Date(moment(data.fecha_fin));
            $scope.acta.fecha_inicio =new Date(moment(data.fecha_inicio));

            if(data.aprobacion_dippro)
            {
                $scope.mostrar=false;
            }
        })
    });

    $scope.guardarActa = function()
    {
        $scope.acta.fecha_fin =new Date(moment($('#fecha_fin_acta').val()));

        var formData = new FormData();

		var idxs = [];

		toFormData(formData, $scope.acta, idxs, $filter);

        // console.log(formData, $scope.acta);

        $http.post(raiz+'/cdn/aprobaracta', formData, {

			headers: {
				"Content-type": undefined
			},

			transformRequest: angular.identity

		}).success(function(data){
            $('#cargando').modal('hide');
            swal({
                title: data.title,
                text: data.content,
                type: data.type,
                showCancelButton: false,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Ok",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: true,
                closeOnCancel: false
                },
                function(isConfirm)
                {
                    if(data.type == 'success')
                    {
                        location.href=raiz+'/cdn/actas';
                    }


                });
        }).error(function(data){
            $('#cargando').modal('hide');
            $scope.errores = data;
        })

    }
})

dipro.controller('CdnCartasCtrl', function($scope, $http) {

    function cargarDatos()
    {
        $http.get(raiz+'/cdn/cartasjson').success(function(data){
            $scope.cartas = data;
        })

        $http.get(raiz+'/cdn/estadoscartajson').success(function(data){
            $scope.estados = data;
        })
    }

    cargarDatos();

    $('#mdlEstado').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.carta = {};
        $scope.carta.id = id;
        $scope.errores={};
    });

    $('#mdlEstado').on('hide.bs.modal', function(e) {
        $scope.carta = {};
        $scope.errores={};
    });

    $scope.cambiarEstadoCarta = function()
    {
        cargando();
        $http.post(raiz+'/cdn/cambiarestadocarta', $scope.carta).success(function(data){
            close_cargando();
            cargarDatos();
            cerrar_modal('mdlEstado');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})

dipro.controller('CdnPrepracticasCtrl', function($scope, $http) {
    $scope.cargarDatos = function()
    {
        $http.get(raiz+'/cdn/prepracticasjson').success(function(data){
            $scope.estudiantes = data;
        })
    }

    $scope.cargarDatos();

    $scope.estudiantes_id = [];

    $scope.seleccionar = function(id)
    {
        var index = buscar(id);
        if(index < $scope.estudiantes_id.length)
        {
            $scope.estudiantes_id.splice(index, 1);
        }
        else
        {
            $scope.estudiantes_id.push(id);
        }
    }

    function buscar(id)
    {
        var i = 0;

        for(i=0; i < $scope.estudiantes_id.length; i++)
        {
            if($scope.estudiantes_id[i] == id)
            {
                break;
            }
        }

        return i;
    }

    $scope.seleccionarTodo = function()
    {
        for(var i=0; i< $scope.estudiantes.length; i++)
        {
            var id = $scope.estudiantes[i].id;
            var index = buscar(id);
            if(index >= $scope.estudiantes_id.length)
            {
                $scope.estudiantes[i].seleccionado = true;
                $scope.estudiantes_id.push(id);
            }
        }
    }

    $scope.quitarSeleccion = function()
    {
        $scope.estudiantes_id = [];
        for(var i=0; i< $scope.estudiantes.length; i++)
        {
            $scope.estudiantes[i].seleccionado = false;
        }
    }

    $scope.aprobarPrepracticas = function()
    {
        cargando();

        $http.post(raiz+'/cdn/aprobarprepracticas', $scope.estudiantes_id).success(function(data){
            close_cargando();
            swal(data.title, data.content, data.type);
            $scope.cargarDatos();
            $scope.estudiantes_id = [];
        });
    }
})

dipro.controller('CdnCharlasCtrl', function($scope, $http, $filter) {


    function inicializar()
    {
        $scope.mostrar = false;

        $scope.charla = {};

        $scope.charla.getconferencia = {};
        $scope.charla.getorador = {};
    }

    function cargarDatos()
    {
        $scope.filtro = {};
        $http.get(raiz+'/cdn/periodosjson').success(function(data){
            $scope.periodos = data;
        })

        $http.get(raiz+'/cdn/charlasjson').success(function(data){
            $scope.charlas = data;
        })

        $http.get(raiz+'/cdn/programasjson').success(function(data){
            $scope.programas = data;
        })

        $http.get(raiz+'/cdn/conferenciasjson').success(function(data){
            $scope.conferencias = data;
        })

        $http.get(raiz+'/cdn/prepracticantesjson').success(function(data){
            $scope.estudiantes = data;
        })
    }
    inicializar();
    cargarDatos();

    $scope.guardarCharla = function()
    {
        // cargando();
        $scope.charla.getconferencia.id = 0;
        $scope.charla.str_hora_inicial = $filter('date')($scope.charla.hora_inicial, 'shortTime');
        $scope.charla.str_hora_final = $filter('date')($scope.charla.hora_final, 'shortTime');
        $http.post(raiz+'/cdn/savecharla', $scope.charla).success(function(data){
            // close_cargando();
            cerrar_modal('crearCharla');
            cargarDatos();
            inicializar();
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }

    $scope.buscarPersona = function()
    {

        // cargando();
        var identificacion = $scope.charla.getorador.identificacion;
        $http.get(raiz+'/cdn/persona/'+identificacion).success(function(data){
            // close_cargando();
            if(data.length > 0)
            {
                $scope.charla.getorador = data[0];
            }
            else
            {
                $scope.charla.getorador = {};
                $scope.charla.getorador.identificacion = identificacion;
                swal("Busqueda vacia", "No se encontró una persona con esa identificación. Por favor complete los datos de conferencista para guardarlos", "info");
                $scope.mostrar = true;
            }
            // $('#crearCharla').focus();
        });
    }

    $('#addHorario').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        var editar = $(e.relatedTarget).data('editar');
        $scope.charla.getconferencia.id = id;
        if(id != null && editar)
        {
            $http.get(raiz+'/cdn/charlajson/'+id).success(function(data) {
                $scope.charla = data;
                $scope.charla.editar = true;
                $scope.charla.id = id;
                $scope.charla.fecha = new Date(moment(data.fecha));
                $scope.charla.hora_inicial = getTime(data.horaInicial);
                $scope.charla.hora_final = getTime(data.horaFinal);
            })
        }
    });

    $('#addHorario').on('hide.bs.modal', function(e) {
        inicializar();
    });

    $('#lista').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $scope.lista = {};
        $scope.lista.idCharla = id;
    });

    $('#lista').on('hide.bs.modal', function(e) {
        $scope.lista = {};
    });

    $('#asistencia').on('show.bs.modal', function(e) {
        $scope.asistencia = {};
        $scope.errores = {};
    });

    $('#asistencia').on('hide.bs.modal', function(e) {
        $scope.asistencia = {};
        $scope.errores = {};
    });

    $('#mdlCalificacion').on('show.bs.modal', function(e) {
        inicializar();
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/cdn/charlajson/'+id).success(function(data) {
            $scope.charla = data;
        })
    });

    $('#mdlCalificacion').on('hide.bs.modal', function(e) {
        inicializar();
    });

    $scope.guardarHorario = function()
    {
        // cargando();
        $scope.charla.str_hora_inicial = $filter('date')($scope.charla.hora_inicial, 'shortTime');
        $scope.charla.str_hora_final = $filter('date')($scope.charla.hora_final, 'shortTime');
        $http.post(raiz+'/cdn/addhorario', $scope.charla).success(function(data){
            // close_cargando();
            cerrar_modal('addHorario');
            cargarDatos();
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }

    $scope.filtrarPeriodo = function()
    {
        cargando();
        $http.get(raiz+'/cdn/charlasjson/'+$scope.filtro.periodo.id).success(function(data){
            close_cargando();
            $scope.charlas = data;
        })
    }

    $scope.generarLista = function()
    {
        window.open(raiz+'/cdn/generarlista/'+$scope.lista.idCharla+'/'+$scope.lista.programa.id);
    }

    $scope.guardarAsistencia = function ()
    {
        cargando();
        $http.post(raiz+'/cdn/guardarasistencia', $scope.asistencia).success(function(data){
            close_cargando();
            cerrar_modal('asistencia');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })
    }

    $scope.verAsistencia = function(id)
    {
        cargando();
        $http.get(raiz+'/cdn/asistentesjson/'+id).success(function(data) {
            $scope.asistentes = data;
            close_cargando();
            open_modal('mdlAsistentes');
        });
    }
})

dipro.controller('CdnConveniosCtrl', function($scope, $http, $filter) {

    function cargarDatos()
    {
        $http.get(raiz+'/cdn/conveniosjson').success(function(data){
            $scope.convenios = data;
        })
    }

    cargarDatos();

    $scope.aprobarConvenio = function(id)
    {
        cargando();
        $http.get(raiz+'/cdn/aprobarconvenio/'+id).success(function(data){
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        })
    }

    $('#detallesEmpresa').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $http.get(raiz+'/cdn/datosempresa/'+id).success(function(data){
            $scope.empresa = data;
        });
    });

    $('#detallesEmpresa').on('hide.bs.modal', function(e) {
        $scope.empresa = {};
    });

    $('#suscribirConvenio').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $scope.convenio ={};
        $scope.convenio.id = id;

    });

    $('#suscribirConvenio').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
    });

    $('#renovarConvenio').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');

        $scope.convenio ={};
        $scope.convenio.id = id;
        $scope.errores = {};

    });

    $('#renovarConvenio').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
        $scope.errores = {};
    });

    $scope.noAprobarConvenio = function(id)
    {
        cargando();
        $http.get(raiz+'/cdn/noaprobarconvenio/'+id).success(function(data){
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        })
    }
    $('#adjuntarMinuta').on('hide.bs.modal', function(e) {
        $scope.convenio = {};
        $scope.errores = {};
    });

    $('#adjuntarMinuta').on('show.bs.modal', function(e){
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/cdn/conveniosjson/'+id).success(function(data){
            $scope.convenio = data;
        });
    });


    $scope.revisionDocs = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);
        $http.post(raiz+'/cdn/conveniorevisado', formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data){
            close_cargando();
            $('#adjuntarMinuta').modal('hide');
            swal(data.title, data.content, data.type);
            cargarDatos();
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }

    $scope.firmarEmpresa = function(id)
    {
        cargando();

        $http.get(raiz+'/cdn/enviarafirma/'+id).success(function(data) {
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        });

    }

    $scope.recepcionDippro = function(id)
    {
        cargando();
        $http.get(raiz+'/cdn/recepciondippro/'+id).success(function(data) {
            cargarDatos();
            close_cargando();
            swal(data.title, data.content, data.type);
        });
    }

    $scope.suscribir_convenio = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);

        $http.post(raiz+'/cdn/suscribirconvenio', formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data){
            close_cargando();
            cargarDatos();
            $('#suscribirConvenio').modal('hide');
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores=data;
        });
    }

    $scope.renovar_convenio = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.convenio, idxs, $filter);
        $http.post(raiz+'/cdn/renovarconvenio', formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data){
            close_cargando();
            $('#renovarConvenio').modal('hide');
            swal(data.title, data.content, data.type);
            cargarDatos();
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });
    }
})

dipro.controller('CorreoMasivoCtrl', function($scope, $http, $filter) {
    $scope.options = {
        language: 'en',
        allowedContent: true,
        entities: false
    };

    $scope.onReady = function () {};
    function cargarDatos()
    {

        $http.get(raiz+'/admin/rolescorreomasivo').success(function(data){
            $scope.roles = data;

            $http.get(raiz+'/admin/programasjson').success(function(data){
                $scope.programas = data;
            })

            $scope.correo = {};
            $scope.correo.roles = [];
            $scope.mostrar = false;

            $http.post(raiz+'/admin/usuarioscorreomasivo', $scope.correo).success(function(data){

                $scope.usuarios = data;
            })
        })


    }

    cargarDatos();

    $scope.changeRoles = function()
    {
        cargando();
        var i=0;
        for(i=0; i<$scope.correo.roles.length; i++)
        {
            if($scope.correo.roles[i].nombre=='Estudiante')
            {
                $scope.mostrar = true;
                break;
            }
        }
        if(i == $scope.correo.roles.length)
        {
            $scope.mostrar = false;
        }

        $http.post(raiz+'/admin/usuarioscorreomasivo', $scope.correo).success(function(data){
            close_cargando();
            $scope.usuarios = data;
        })
    }

    $scope.changeProgramas = function()
    {
        cargando();
        $http.post(raiz+'/admin/usuarioscorreomasivo', $scope.correo).success(function(data){
            close_cargando();
            $scope.usuarios = data;
        })
    }

    $scope.enviar = function()
    {
        cargando();
        var formData = new FormData();
		var idxs = [];
		toFormData(formData, $scope.correo, idxs, $filter);
        $http.post(raiz+'/admin/enviocorreo',  formData, {
            headers: {
				"Content-type": undefined
			},
			transformRequest: angular.identity
        }).success(function(data) {
            close_cargando();
            cargarDatos();
            swal(data.title, data.content, data.type);

        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        });

    }
});

dipro.controller('AdminExteriorCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/exteriorjson').success(function(data){
            $scope.exterior = data;
        });

        $http.get(raiz+'/admin/periodosmejson').success(function(data){
            $scope.periodos = data;
        });

        $scope.periodo = {};
    }

    cargarDatos();

    $scope.filtrar = function()
    {
        $http.get(raiz+'/admin/exteriorjson/'+$scope.periodo).success(function(data){
            $scope.exterior = data;
        });
    }
})

dipro.controller('AdminVinculacionCtrl', function($scope, $http) {

    function cargarDatos()
    {
        $http.get(raiz+'/admin/vinculacionjson').success(function(data){
            $scope.vinculacion = data;
            console.log(data);
        });

        $http.get(raiz+'/admin/periodosmejson').success(function(data){
            $scope.periodos = data;
            console.log(data);
        });

        $scope.periodo = {};
    }

    cargarDatos();

    $scope.filtrar = function()
    {
        $http.get(raiz+'/admin/vinculacionjson/'+$scope.periodo).success(function(data){
            $scope.vinculacion = data;
        });
    }
})


dipro.controller('AdminFSantaMartaCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/fsantamartajson').success(function(data){
            $scope.fsantamarta = data;
        });

        $http.get(raiz+'/admin/periodosmejson').success(function(data){
            $scope.periodos = data;
        });

        $scope.periodo = {};
    }

    cargarDatos();

    $scope.filtrar = function()
    {
        $http.get(raiz+'/admin/fsantamartajson/'+$scope.periodo).success(function(data){
            $scope.fsantamarta = data;
        });
    }
})

dipro.controller('AdminUbicacionCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/ubicacionjson').success(function(data){
            $scope.ubicacion = data;
        });

        $http.get(raiz+'/admin/periodosmejson').success(function(data){
            $scope.periodos = data;
        });

        $scope.periodo = {};
    }

    cargarDatos();

    $scope.filtrar = function()
    {
        $http.get(raiz+'/admin/ubicacionjson/'+$scope.periodo).success(function(data){
            $scope.ubicacion = data;
        });
    }
})


dipro.controller('AdminLaborandoCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/laborandojson').success(function(data){
            $scope.practicantes = data;
        });

        $http.get(raiz+'/admin/periodosmejson').success(function(data){
            $scope.periodos = data;
        });

        $scope.periodo = {};
    }

    cargarDatos();

    $scope.filtrar = function()
    {
        $http.get(raiz+'/admin/laborandojson/'+$scope.periodo).success(function(data){
            $scope.practicantes = data;
        });
    }
})

dipro.controller('AdminImpactoCtrl', function($scope, $http) {
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/admin/impactojson').success(function(data){
            $scope.impactos = data;
        });

        $http.get(raiz+'/admin/periodosmejson').success(function(data){
            $scope.periodos = data;
        });

        $scope.periodo = {};
    }

    cargarDatos();

    $scope.filtrar = function()
    {
        $http.get(raiz+'/admin/impactojson/'+$scope.periodo).success(function(data){
            $scope.impactos = data;
        });
    }
})

dipro.controller('AdminConferenciasCtrl', function($scope, $http) {
    $scope.accion = "";

    $scope.accionar = function()
    {
        $scope.accion = 'Editar';
    }

    $http.get(raiz+'/admin/programasjson').success(function(data){
        $scope.programas = data;
    });

    function cargarDatos()
    {
        $http.get(raiz+'/admin/conferenciasjson2').success(function(data){
            $scope.conferencias = data;
        })
    }

    cargarDatos();

    $('#mdlConferencia').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        if(id!=null)
        {
            cargando();
            $scope.errores = {};
            $http.get(raiz+'/admin/conferenciajson/'+id).success(function(data){
                close_cargando();
                $scope.conferencia = data;
            });
        }
    });

    $('#mdlConferencia').on('hide.bs.modal', function(e) {
        $scope.conferencia = {};
        $scope.errores = {};
    });

    $scope.crearConferencia = function()
    {
        cargando();
        $http.post(raiz+'/admin/saveconferencia', $scope.conferencia).success(function(data){
            close_cargando();
            cerrar_modal('mdlConferencia');
            cargarDatos();
            swal(data.title, data.content, data.type);
        }).error(function(data){
            $scope.errores = data;
            close_cargando();
        });
    }
})

dipro.controller('EstCartasCtrl', function($scope, $http) {

    $scope.carta = {};

    $http.get(raiz+'/estudiante/empresasjson').success(function(data){
        $scope.empresas = data;
    })

    $http.get(raiz+'/estudiante/infoestudiante').success(function(data){
        $scope.estudiante = data;
    })

    $scope.solicitar_carta = function()
    {
        cargando();
        $http.post(raiz+'/estudiante/solicitarcarta', $scope.carta).success(function(data){
            close_cargando();
            cerrar_modal('solicitarCarta');
            $http.get(raiz+'/estudiante/infoestudiante').success(function(data){
                $scope.estudiante = data;
            })
            $scope.carta ={};
            swal(data.title, data.content, data.type);
        }).error(function(data){
            close_cargando();
            $scope.errores = data;
        })

    }
})

dipro.controller('CambiarClaveCtrl', function($scope, $http){
    $scope.cambioclave = function()
    {
        cargando();
        $http.post(raiz+'/home/cambiarclave', $scope.user).success(function(data){
            close_cargando();
            swal({
                    title: data.title,
                    text: data.content,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Ok",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        location.href=raiz+'/home';

                    });
        }).error(function(error){
            close_cargando();
            $scope.errores = error;
        });
    }
})

dipro.controller('GenerarClaveCtrl', function($scope, $http){
    $scope.generarclave = function()
    {
        cargando();
        $http.post(raiz+'/home/generarclave', $scope.user).success(function(data){
            close_cargando();
            swal({
                    title: data.title,
                    text: data.content,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Ok",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        location.href=raiz+'/home';
                    });
        }).error(function(error){
            close_cargando();
            $scope.errores = error;
        });
    }
})

dipro.controller('EmpHojasdevidaCtrl', function($scope, $http){
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/empresa/hojasdevidajson').success(function(data){
            $scope.graduados = data;
        });
    }

    $scope.invitar=function(id)
    {
        cargando();
        $http.get(raiz+'/empresa/invitar/'+id).success(function(data){
            close_cargando();
            swal(data.title, data.content, data.type);
        }).error(function(error){
            close_cargando();
        });
    }

    cargarDatos();
});

dipro.controller('EmpHoja2Ctrl', function($scope, $http, $rootScope) {
    $rootScope.$watch('idPersona', function(){
        $http.get(raiz+'/empresa/hoja2json/'+$scope.idPersona).success(function(data){
            var hoy = new Date();
            var fn = new Date(data.fechaNacimiento);
            fn.setDate(fn.getDate()+1);

            var edad = hoy.getFullYear() - fn.getFullYear();

            fn.setFullYear(hoy.getFullYear());
            if(hoy < fn) edad--;

            $scope.persona = data;
            $scope.persona.edad = edad;

            setTimeout(function() {
                $('legend h4').css('font-weight', 'bold');
            }, 10);
        });
    });
})

dipro.controller('GraduadoIndexCtrl', function($scope, $http){

});

dipro.controller('GraduadoConfigCtrl', function($scope, $http) {

    cargarDatos();

    function cargarDatos()
    {
        $http.get(raiz+'/graduado/configjson').success(function(data){
            $scope.config = data
        });
    }

    $scope.recibirMails = function()
    {
        cargando();
        $http.get(raiz+'/graduado/recibirmails/'+$scope.config.getuser.recibir_mails).success(function(data){
            close_cargando();
            swal(data.title, data.content, data.type);
        });
    }

    $scope.visibilidadHojavida = function()
    {
        cargando();
        $http.get(raiz+'/graduado/visibilidadhojavida/'+$scope.config.getuser.gethojadevida[0].activa).success(function(data){
            close_cargando();
            swal(data.title, data.content, data.type);
        });
    }

    $scope.cuenta = function()
    {
        swal({
            title: "Desactivar cuenta",
            text: "¿Está seguro que desea desactivar su cuenta? Si desactiva su cuenta no podrá acceder a los servicios que ofrece el Centro de Egresados a través del sistema de intermediación laboral",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8cd4f5",
            confirmButtonText: "Si",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
            },
            function(isConfirm)
            {
                if(isConfirm)
                {
                    $http.get(raiz+'/graduado/desactivar').success(function(data){
                        swal({
                            title: data.title,
                            text: data.content,
                            type: data.type,
                            showCancelButton: false,
                            confirmButtonColor: "#8cd4f5",
                            confirmButtonText: "Si",
                            cancelButtonText: "No",
                            closeOnConfirm: true,
                            closeOnCancel: true
                            },
                            function(isConfirm)
                            {
                                if(isConfirm)
                                {
                                    $http.get(raiz+'/auth/logout').success(function(){
                                        location.href=raiz;
                                    });
                                }
                            });
                    });
                }
            });
    }

});

dipro.controller('GraduadoReporteCtrl', function($scope, $http){
    alert('reporte');
});

dipro.controller('GraduadoOfertasCtrl', function($scope, $http){
    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/graduado/ofertasjson').success(function(data){
            $scope.ofertas = data;
        }).error (function (data) {
            window.location.href = window.location.href;
        });
    }

    cargarDatos();

    $scope.postularEgresados = function (oferta) {
        var texto = `Para postularse a esta oferta, favor enviar hoja de vida a ${oferta.correo_egresados}`;
        swal('Informacion', texto, 'info');
    }

    $scope.postular = function (oferta_id) {
        var letra = $scope.genero == 'Masculino' ? 'o':'a';

        swal({
            title: 'Postular a oferta',
            text: `¿Segur${letra} que desea postularse a esta oferta?`,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }, function () {
            $http.get(`${raiz}/graduado/postularse/${oferta_id}`).then(function (response) {
                if (response.data.status == 'success') swal('Exito', response.data.content, 'success');
                if (response.data.status == 'error') swal('Error', response.data.content, 'error');

                cargarDatos();
            }, function (error) {
                swal('Error', 'Error interno del servidor', 'error');
            });
        });
    }

    $scope.despostular = function (oferta_id) {
        var letra = $scope.genero == 'Masculino' ? 'o':'a';

        swal({
            title: 'Cancelar postulacion a oferta',
            text: `¿Segur${letra} que desea cancelar la postulacion a esta oferta?`,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }, function () {
            $http.get(`${raiz}/graduado/nopostularse/${oferta_id}`).then(function (response) {
                if (response.data.status == 'success') swal('Exito', response.data.content, 'success');
                if (response.data.status == 'error') swal('Error', response.data.content, 'error');

                cargarDatos();
            }, function (error) {
                swal('Error', 'Error interno del servidor', 'error');
            });
        });
    }

    $('#detallesOferta').on('show.bs.modal', function(e) {
        var id= $(e.relatedTarget).data('id');
        $http.get(raiz+'/graduado/ofertasjson/'+id).success(function(data){
            $scope.oferta = data;

            $scope.oferta.programas = [];
            $scope.oferta.getprogramas.forEach(function (value, key) {
                $scope.oferta.programas.push(value.nombre);
            });

            console.log($scope.oferta.programas);
        });
    });

    $scope.aceptarOferta = function (oferta_id) {
        swal({
            title: '¿Aceptar oferta?',
            text: 'La empresa podra ver sus datos de contacto, para así comunicarse con usted para continuar el proceso.',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }, function () {
            window.location.href = raiz + '/graduado/aceptaroferta/' + oferta_id;
        });
    }
});

dipro.controller('GraduadoHojaCtrl', function($scope, $http){

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    cargarDatos();

    function cargarDatos()
    {
        cargando();
        $scope.estudios ={};
        $scope.estudios.anio={};
        $scope.nuevoIdioma ={};
        $scope.errorFamiliar =[];
        $scope.gethojadevida = [];
        $scope.gethojadevida[0] = {};

        $http.get(raiz+'/graduado/hojavidajson').success(function (data) {
            $scope.persona = data;
            $scope.persona.fechaNacimiento = new Date(moment($scope.persona.fechaNacimiento));
            if(data.gethojadevida[0].getestudios == null)
            {
                $scope.persona.gethojadevida[0].getestudios = [];
            }
            if(data.gethojadevida[0].getexperiencias == null)
            {
                $scope.persona.gethojadevida[0].getexperiencias = [];
            }
            if(data.gethojadevida[0].getidiomashv == null)
            {
                $scope.persona.gethojadevida[0].getidiomashv = [];
            }
            if(data.gethojadevida[0].getreferenciasp == null)
            {
                $scope.persona.gethojadevida[0].getreferenciasp = [];
            }
            if(data.gethojadevida[0].getreferenciasf == null)
            {
                $scope.persona.gethojadevida[0].getreferenciasf = [];
            }

            if(data.gethojadevida[0].getcompetencias == null)
            {
                $scope.persona.gethojadevida[0].getcompetencias = [];
            }
            close_cargando();

            $('.bselect').selectpicker();
        }).error(function (data) {
            // window.location.href = window.location.href;
        });

        $scope.departamentos = function () {
            console.log($scope.persona.getciudadres);

            $scope.persona.ciudades = [];
            var idPais = $scope.persona.getciudadres.getdepartamento.getpais.id;
            $http.get(raiz + '/graduado/departamentos/' + idPais).then(function (response) {
                $scope.persona.departamentos = response.data;
            });
        };

        $scope.ciudades = function () {
            console.log($scope.persona.getciudadres);

            var idDepartamento = $scope.persona.getciudadres.getdepartamento.id;
            $http.get(raiz + '/graduado/ciudades/' + idDepartamento).then(function (response) {
                $scope.persona.ciudades = response.data;
            });
        };
    }

    $scope.guardarDatosPersonales = function()
    {
        cargando();
        // $scope.persona.paises = [];
        // $scope.persona.departamentos = [];
        // $scope.persona.municipios = [];
        // $scope.persona.generos = [];
        // $scope.persona.estadosciviles = [];

        $http.post(raiz+'/graduado/savedatospersonales', $scope.persona).success(function(data){
            close_cargando();
            swal(data.title, data.content, data.type);
        }).error(function(error){
            $scope.errores = error;
            close_cargando();
        });
    }

    $scope.selectPais = function()
    {
        cargando();
        $http.get(raiz+'/home/departamentos/'+$scope.persona.getciudad.getdepartamento.getpais.id).success(function(data){
            $scope.persona.departamentos = data;
            close_cargando();
        });
    }

    $scope.selectDepartamento= function()
    {
        cargando();
        $http.get(raiz+'/home/municipios/'+$scope.persona.getciudad.getdepartamento.id).success(function(data){
            $scope.persona.municipios = data;
            close_cargando();
        });
    }

    ////////////////////////////////

    $scope.agregarEstudio = function()
    {
        cargando();
        $scope.estudios.anioGrado = $scope.estudios.anio.nombre;
        $http.post(raiz+'/graduado/estudiorealizado', $scope.estudios).success(function(data){
            //

            var index = getIndex($scope.estudios.titulo);
            if(index < $scope.persona.gethojadevida[0].getestudios.length)
            {
                $scope.error.titulo = [];
                $scope.error.titulo[0] = 'Este título ya se encuentra registrado';
            }
            else
            {
                $scope.persona.gethojadevida[0].getestudios.push($scope.estudios);
                $scope.estudios = {};
                $scope.error = {};
                $scope.estudios.anio = {};
                $scope.estudios.getmunicipio = {};
                $scope.estudios.anio.id = null;
                $scope.estudios.anio.nombre = null;
                $('#agregarEstudio').modal('hide');
            }

            close_cargando();

        }).error(function(data){
            $scope.error = data;
            close_cargando();
        });

    }

    $scope.quitarEstudio = function(titulo)
    {
        var index = getIndex(titulo);
        $scope.persona.gethojadevida[0].getestudios.splice(index ,1 );
    }

    $scope.cancelarEstudio = function ()
    {
        $scope.error = {};
        $scope.estudios = {};
    }

    function getIndex(titulo)
    {
        var i=0;

        for(i=0;i < $scope.persona.gethojadevida[0].getestudios.length;i++)
        {
            if(titulo.toLowerCase()==$scope.persona.gethojadevida[0].getestudios[i].titulo.toLowerCase())
            {
                break;
            }
        }
        return i;
    }

    ////////////////////////////////

    $scope.agreagarExperiencia = function()
    {
        $http.post(raiz+'/graduado/experiencialaboral', $scope.experiencia).success(function(data) {
            //

            var index = getIndexExp($scope.experiencia.cargo, $scope.experiencia.empresa);
            if(index < $scope.persona.gethojadevida[0].getexperiencias.length)
            {
                $scope.errorExp.cargo = [];
                $scope.errorExp.cargo[0] = 'Esta experiencia ya se encuentra registrada';
            }
            else
            {
                $scope.persona.gethojadevida[0].getexperiencias.push($scope.experiencia);
                $scope.experiencia = {};
                $scope.errorExp = {};
                $('#agregarExperiencia').modal('hide');
            }
            //

        }).error(function(data){
            $scope.errorExp = data;
        });
    }

    $scope.quitarExperiencia = function(cargo, empresa)
    {
        var index = getIndexExp(cargo, empresa);
        $scope.persona.gethojadevida[0].getexperiencias.splice(index ,1 );
    }

    $scope.cancelarExperiencia = function ()
    {
        $scope.errorExp = {};
        $scope.experiencia = {};
    }

    function getIndexExp(cargo, empresa)
    {
        var i=0;

        for(i=0;i < $scope.persona.gethojadevida[0].getexperiencias.length;i++)
        {
            if(cargo==$scope.persona.gethojadevida[0].getexperiencias[i].cargo && empresa==$scope.persona.gethojadevida[0].getexperiencias[i].empresa)
            {
                break;
            }
        }
        return i;
    }

    ////////////////ahora empieza lo del idioma





    function getIndexIdioma(nombre)
    {
        var i=0;

        for(i=0;i < $scope.persona.gethojadevida[0].getidiomashv.length;i++)
        {
            if(nombre==$scope.persona.gethojadevida[0].getidiomashv[i].getidioma.nombre )
            {
                break;
            }
        }
        return i;
    }

    $scope.agreagarIdioma = function()
    {
        console.log($scope.nuevoIdioma);
        $http.post(raiz+'/graduado/idioma', $scope.nuevoIdioma).success(function(data) {

            var index = getIndexIdioma($scope.nuevoIdioma.getidioma.nombre);
            if(index < $scope.persona.gethojadevida[0].getidiomashv.length)
            {
                $scope.errorIdioma['getidioma.id'] = []
                $scope.errorIdioma['getidioma.id'][0] = 'Esta idioma ya se encuentra registrado';
            }
            else
            {
                $scope.persona.gethojadevida[0].getidiomashv.push($scope.nuevoIdioma);
                $scope.nuevoIdioma = {};
                $scope.errorIdioma = {};
                $('#agregarIdioma').modal('hide');
            }

        }).error(function(data){
            $scope.errorIdioma = data;
        });
    }

    $scope.cancelarIdioma = function ()
    {
        $scope.errorIdioma = {};
        $scope.nuevoIdioma = {};
    }

    $scope.quitarIdioma = function(nombre)
    {
        var index = getIndexIdioma(nombre);
        $scope.persona.gethojadevida[0].getidiomashv.splice(index ,1 );
    }

    //Distincion

    $scope.agregarDistincion = function ()
    {
        if ($scope.nuevaDistincion != undefined && $scope.nuevaDistincion.trim() != '') {
            $scope.persona.gethojadevida[0].getdistinciones.push({ nombre: $scope.nuevaDistincion });
            $scope.errorIdioma = {};
            $('#agregarDistincion').modal('hide');
        }
    }

    $scope.cancelarDistincion = function ()
    {
        $scope.errorDistincion = {};
        $scope.nuevaDistincion = {};
    }

    $scope.quitarDistincion = function(nombre)
    {
        var i;
        for (i = 0; i < $scope.persona.gethojadevida[0].getdistinciones.length; i++) {
            if (nombre == $scope.persona.gethojadevida[0].getdistinciones[i].nombre) break;
        }

        $scope.persona.gethojadevida[0].getdistinciones.splice(i, 1);
    }

    $scope.guardarPerfil = function()
    {
        $http.post(raiz+'/graduado/saveperfil', $scope.persona.gethojadevida[0]).success(function(data) {

            $scope.errores = {};
            swal(data.title, data.content, data.type);

        }).error(function(data) {
            $scope.errores = data;
        })
    }

    $scope.quitarPersonal = function (telefono)
    {
        var index = getIndexPersonal(telefono);
        $scope.persona.gethojadevida[0].getreferenciasp.splice(index ,1 );
    }

    function getIndexPersonal(telefono)
    {
        var i=0;

        for(i=0;i < $scope.persona.gethojadevida[0].getreferenciasp.length;i++)
        {
            if(telefono == $scope.persona.gethojadevida[0].getreferenciasp[i].telefono )
            {
                break;
            }
        }
        return i;
    }

    $scope.agreagarReferenciaP = function()
    {
        $http.post(raiz+'/graduado/referenciapersonal', $scope.referenciaPersonal).success(function(data) {
            var index = getIndexPersonal($scope.referenciaPersonal.telefono);
            var index2 = getIndexFamiliar($scope.referenciaPersonal.telefono);
            if(index < $scope.persona.gethojadevida[0].getreferenciasp.length || index2 < $scope.persona.gethojadevida[0].getreferenciasf.length)
            {
                $scope.errorPersonal.telefono = [];
                $scope.errorPersonal.telefono[0] = 'El número de telefono ya se encuentra registrado';
            }
            else
            {
                $scope.persona.gethojadevida[0].getreferenciasp.push($scope.referenciaPersonal);
                $scope.referenciaPersonal = {};
                $scope.errorPersonal = {};
                $('#agregarReferenciaP').modal('hide');
            }
        }).error(function(data) {
            $scope.errorPersonal = data;
        })
    }

    $scope.agreagarReferenciaF = function()
    {
        $http.post(raiz+'/graduado/referenciafamiliar', $scope.referenciaFamiliar).success(function(data) {
            var index = getIndexPersonal($scope.referenciaFamiliar.telefono);
            var index2 = getIndexFamiliar($scope.referenciaFamiliar.telefono);
            if(index < $scope.persona.gethojadevida[0].getreferenciasp.length || index2 < $scope.persona.gethojadevida[0].getreferenciasf.length)
            {
                $scope.errorFamiliar.telefono = [];
                $scope.errorFamiliar.telefono[0] = 'El número de telefono ya se encuentra registrado';
            }
            else
            {
                $scope.persona.gethojadevida[0].getreferenciasf.push($scope.referenciaFamiliar);
                $scope.referenciaFamiliar = {};
                $scope.errorFamiliar = {};
                $('#agregarReferenciaF').modal('hide');
            }
        }).error(function(data) {
            $scope.errorFamiliar = data;
        })
    }

    $scope.quitarFamiliar = function (telefono)
    {
        var index = getIndexFamiliar(telefono);
        $scope.persona.gethojadevida[0].getreferenciasf.splice(index ,1 );
    }

    function getIndexFamiliar(telefono)
    {
        var i=0;

        for(i=0;i < $scope.persona.gethojadevida[0].getreferenciasf.length;i++)
        {
            if(telefono == $scope.persona.gethojadevida[0].getreferenciasf[i].telefono )
            {
                break;
            }
        }
        return i;
    }

    $scope.cancelarPersonal = function ()
    {
        $scope.errorPersonal = {};
    }

    $scope.cancelarFamiliar = function ()
    {
        $scope.errorFamiliar = {};
    }

    $scope.guardarReferencias = function()
    {
        $http.post(raiz+'/graduado/savereferencia', $scope.persona.gethojadevida[0]).success(function(data) {
            swal(data.title, data.content, data.type);
        })
    }


});

dipro.controller('AdminHojasdevidaCtrl', function($scope, $http){

    $scope.raiz = raiz;
    function cargarDatos()
    {
        $http.get(raiz+'/sil/hojasdevidajson').success(function(data){
            $scope.graduados = data;
        });
    }

    cargarDatos();

})

dipro.controller('AdminHoja2Ctrl', function($scope, $http, $rootScope) {
    $rootScope.$watch('idPersona', function(){
        $http.get(raiz+'/sil/hojajson/'+$scope.idPersona).success(function(data){
            $scope.persona = data;
            setTimeout(function() {
                $('legend h4').css('font-weight', 'bold');
            }, 10);
        });
    });
})

dipro.controller('SilUsuariosCtrl', function($scope, $http){

    $scope.mostrar =false;

    $scope.editar=false;


    function cargarDatos()
    {
        $http.get(raiz+'/sil/usuariosjson').success(function(data){
            $scope.usuarios = data;
        });

        $http.get(raiz+'/sil/formulariousuario').success(function(data) {
            $scope.datos = data;
        });

        $scope.mostrar=false;
    }

    cargarDatos();

    $('#crearUsuario').on('show.bs.modal', function(e) {

        var id= $(e.relatedTarget).data('id');
        if(id!=null)
        {
            cargando()
            $scope.errores = {};
            $scope.editar=true;
            $http.get(raiz+'/admin/usuario/'+id).success(function(data){
                $scope.usuario = data;
                $('#cargando').modal('hide');

            });
        }



    });

    $scope.newUser = function()
    {
        $scope.usuario = {};
        $scope.editar=false;
        $scope.errores = {};
    }

    $scope.guardarUsuario = function()
    {
        cargando();

        $http.post(raiz+'/sil/saveusuario', $scope.usuario).success(function(data){
            $scope.mostrar = false;
            $('#crearUsuario').modal('hide');

            swal(data.title, data.content, data.type);
            cargarDatos();

            $('#cargando').modal('hide');

        }).error(function(data){
            close_cargando();
            $scope.mostrar = true;
            $scope.errores=data;
        });

    }

    $scope.activar = function(id, rol)
    {
        cargando();
        $http.get(raiz+'/sil/activar/'+id+'/'+rol).success(function(data){
            close_cargando();
            cargarDatos();
            swal(data.title, data.content, data.type);
        });
    }

    $scope.buscar = function ($event)
    {
        if($event.keyCode == 13)
        {
            cargando();
            $http.get(raiz+'/sil/usuariojson/'+$scope.usuario.identificacion).success(function(data){
                close_cargando();
                $scope.usuario = data;
            });
        }
    }

});


dipro.controller('ContatanosCtrl', function($scope, $http){
    $scope.contacto = {};

    function cargarDatos()
    {
        $http.get(raiz+'/home/contactojson').success(function(data){
            $scope.contacto.capcha = data;
        });
    }

    cargarDatos();

    $scope.refresh = function()
    {
        cargando();
        $http.get(raiz+'/home/contactojson').success(function(data){
            close_cargando();
            $scope.contacto.capcha = data;
        });
    }

    $scope.enviar = function()
    {
        cargando();
        $http.post(raiz+'/home/savecontacto', $scope.contacto).success(function(data){
            if(data.codigo_de_verificacion != null)
            {
                $scope.errores=data;
                cargarDatos();
            }
            else
            {
                $scope.errores={};
                $scope.contacto = {};
                swal(data.title, data.content, data.type);
            }
            close_cargando();
            //location.href="/users";
        }).error(function(data){
            $scope.errores=data;
            close_cargando();
        });
    }

});

dipro.controller('AdminIndicadoresCtrl', function ($scope, $http, $timeout) {
    var root = $('meta[name="root"]').attr('content');

    function graficoGraduadosPorEdad () {
        data = new google.visualization.DataTable();
        data.addColumn('string', 'Genero');
        data.addColumn('number', 'Cantidad');
        data.addRows([
            ['Masculino', $scope.datos.graduadosMasculinos],
            ['Femenino', $scope.datos.graduadosFemeninos],
        ]);

        options = {
            'backgroundColor': '#FAFAFA',
            width: '100%',
            height: '300'
        };

        chart = new google.visualization.PieChart(document.querySelector('#generos-chart'));
        chart.draw(data, options);
    }

    function graficoEmpresasPorCategoria () {
        empresas = {};
        angular.forEach($scope.datos.empresas, function (value, key) {
            if(!empresas[value.gettiponit.nombre]) empresas[value.gettiponit.nombre] = 1;
            else empresas[value.gettiponit.nombre]++;
        });

        data = new google.visualization.DataTable();
        data.addColumn('string', 'Tipo de empresa');
        data.addColumn('number', 'Cantidad');
        data.addRows(Object.entries(empresas));

        options = {
            'backgroundColor': '#FAFAFA',
            width: '100%',
            height: '300'
        };

        chart = new google.visualization.PieChart(document.querySelector('#empresas-chart'));
        chart.draw(data, options);
    }

    function graficoNumeroConvocatorias () {
        ofertas = {};
        angular.forEach($scope.datos.ofertas, function (value, key) {
            if(!ofertas[value.getestado.nombre+'s']) ofertas[value.getestado.nombre+'s'] = 1;
            else ofertas[value.getestado.nombre+'s']++;
        });

        console.log(Object.entries(ofertas));

        data = new google.visualization.DataTable();
        data.addColumn('string', 'Estado de convocatoria');
        data.addColumn('number', 'Cantidad');
        data.addRows(Object.entries(ofertas));

        options = {
            'backgroundColor': '#FAFAFA',
            width: '100%',
            height: '300'
        };

        chart = new google.visualization.PieChart(document.querySelector('#convocatorias-chart'));
        chart.draw(data, options);
    }

    function graficoMapa () {
        empresas = {};
        angular.forEach($scope.datos.empresas, function (value, key) {
            nombre = value.getsedes[0].getmunicipio.getdepartamento.nombre;
            if(!empresas[nombre]) empresas[nombre] = 1;
            else empresas[nombre]++;
        });

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Country');
        data.addColumn('number', 'Cantidad');
        data.addRows(Object.entries(empresas));

        var options = {
            region: 'CO',
            resolution: 'provinces',
            backgroundColor: '#81d4fa',
            colorAxis: {colors: ['#FFE082', '#FFA000']},
        };

        var chart = new google.visualization.GeoChart(document.getElementById('mapa-chart'));

        chart.draw(data, options);
    }

    google.charts.load('current', {
        'packages': ['corechart', 'geochart'],
        'mapsApiKey': 'AIzaSyAwC3mqLgfatGfJ2_jRlvLi6ftdtWds9Aw'
    });

    google.charts.setOnLoadCallback(function () {
        $http.get(root + '/adminsil/datos-indicadores').then(function (response) {
            $scope.datos = response.data;

            graficoGraduadosPorEdad();
            graficoEmpresasPorCategoria();
            graficoNumeroConvocatorias();
            graficoMapa();
        });
    });
});