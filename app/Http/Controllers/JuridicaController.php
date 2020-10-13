<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RevisarConvenioJuridicaRequest;
use App\Http\Controllers\Controller;

use App\Models\Convenio;
use App\Models\EstadoConvenio;
use App\Models\Rol;
use App\Models\User;

use Mail;

class JuridicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('juridica');
    }
    
    public function getIndex()
    {
        return view('juridica.index');
    }
    
    public function getConveniosjson( $id = null)
    {
        $convenios = null;
        if($id == null)
        {
            $convenios = Convenio::with('getempresa')
                                 ->with('getestado')
                                 ->with('getactasrenovacion')
                                 ->where('estado', EstadoConvenio::where('nombre', 'En revisión por la oficina jurídica')->first()->id)
                                 ->get();
        }
        else
        {
            $convenios = Convenio::with('getempresa')
                                 ->with('getestado')
                                 ->with('getactasrenovacion')
                                 ->where('id', $id)
                                 ->where('estado', EstadoConvenio::where('nombre', 'En revisión por la oficina jurídica')->first()->id)
                                 ->first();
        }
        return $convenios;
    }
    
    public function getCertificadoexistencia($id)
    {
        
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->certificado_existencia;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getCedula($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->cedula_representante;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getProcuraduria($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->procuraduria;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getContraloria($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->contraloria;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getRut($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->rut;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getActoadministrativo($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->acto_administrativo;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getActaposesion($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->acta_posesion;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getMilitar($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->certificado_militar;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getMinuta($id)
    {
        $convenio = Convenio::find($id);
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/juridica')->with($data);
        }
        
        $nombre = $convenio->minuta;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function postConveniorevisado(RevisarConvenioJuridicaRequest $request)
    {
        // dd($request->all());
        $convenio = Convenio::find($request->id);
        $empresa = $convenio->getempresa;
        $usuario = User::where('idRol', Rol::where('nombre', 'Administrador Dippro')->first()->id)
                       ->first();
                       
        $data['empresa'] = $empresa;
        $data['user'] = $usuario;
        if ($request->observacion == null || (is_string($request->observacion) && trim($request->observacion) === '')) {
            $data['observaciones'] = 'No hay observaciones';
        } 
        else
        {
            $data['observaciones'] = $request->observacion;
            $convenio->observaciones = $convenio->observaciones.' @ '.$request->observacion;
        }
        
        if($request->estado == '1')
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Aprobado por la oficina jurídica')->first()->id;
            $data['estado'] = 'Aprobado por la oficina jurídica';
        }
        else if($request->estado == '2')
        {
            $data['estado'] = 'En revisión por Dippro';
            $convenio->estado = EstadoConvenio::where('nombre', 'En revisión por Dippro')->first()->id;
        }
        else
        {
            return ['title'=>'Error', 'content'=>'No es posible trabajar con el estado que usted envío', 'type'=>'error'];
        }
        
        $convenio->save();
        
        Mail::send('emails.juridicadippro', $data, function ($m) use ($usuario) {
            $m->from('hello@app.com', env('MAIL_FROM'));

            $m->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Estado del convenio');
        });
        
        return ['title'=>'Éxito', 'content'=>'Cambio de estado realizado con éxito', 'type'=>'success'];
    }
}
