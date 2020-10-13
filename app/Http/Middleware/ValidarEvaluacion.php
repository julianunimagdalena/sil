<?php

namespace App\Http\Middleware;

use Closure;

class ValidarEvaluacion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     
    public function handle($request, Closure $next)
    {
        // dd($request->all());
        foreach($request->getsecciones as $seccion)
        {
            if($seccion['estado'])
            {
                foreach($seccion['getpreguntas'] as $pregunta)
                {
                    if($pregunta['estado'])
                    {
                        if(!array_key_exists('respuesta', $pregunta))
                        {
                            return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es obligatoria', 'type'=>'error'];
                        }
                        if($pregunta['gettipo']['nombre']=='Cualitativa')
                        {
                            $opciones=array();
                            foreach($pregunta['getpivoterespuesta'] as $pre_res)
                            {
                                array_push($opciones, $pre_res['id']);
                            }
                            
                            if($pregunta['respuesta']==null || !in_array($pregunta['respuesta'], $opciones))
                            {
                                return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                            }
                        }
                        else if($pregunta['gettipo']['nombre']=='Cuantitativa')
                        {
                            if(!($pregunta['respuesta'] >= $pregunta['minimo'] && $pregunta['respuesta'] <= $pregunta['maximo']))
                            {
                                return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                            }
                        }
                        else if($pregunta['gettipo']['nombre']=='Respuesta libre')
                        {
                            if(is_string(!$pregunta['respuesta']))
                            {
                                return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                            }
                        }
                        else if($pregunta['gettipo']['nombre']=='Booleana')
                        {
                            if(!is_bool((bool)$pregunta['respuesta']))
                            {
                                return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                            }
                        }
                        else if($pregunta['gettipo']['nombre']=='Booleana justificada')
                        {
                            if(!is_bool((bool)$pregunta['respuesta']))
                            {
                                return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                            }
                            if(!array_key_exists('justificacion', $pregunta) || $pregunta['justificacion'] == null)
                            {
                                return ['title'=>'Error', 'content'=>'La justificación de la pregunta: '.$pregunta['enunciado'].' es obligatoria', 'type'=>'error'];
                            }
                        }
                    }
                }
                
                foreach($seccion['getsecciones'] as $hija)//secciones hijas
                {
                    if($hija['estado'])
                    {
                        // ////////////////////////
                        foreach($hija['getpreguntas'] as $pregunta)
                        {
                            if($pregunta['estado'])
                            {
                                if(!array_key_exists('respuesta', $pregunta))
                                {
                                    return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es obligatoria', 'type'=>'error'];
                                }
                                if($pregunta['gettipo']['nombre']=='Cualitativa')
                                {
                                    $opciones=array();
                                    foreach($pregunta['getpivoterespuesta'] as $pre_res)
                                    {
                                        array_push($opciones, $pre_res['id']);
                                    }
                                    // dd($opciones, $pregunta['respuesta']);
                                    if($pregunta['respuesta']==null || !in_array($pregunta['respuesta'], $opciones))
                                    {
                                        return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                                    }
                                }
                                else if($pregunta['gettipo']['nombre']=='Cuantitativa')
                                {
                                    if(!($pregunta['respuesta'] >= $pregunta['minimo'] && $pregunta['respuesta'] <= $pregunta['maximo']))
                                    {
                                        return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                                    }
                                }
                                else if($pregunta['gettipo']['nombre']=='Respuesta libre')
                                {
                                    if(is_string(!$pregunta['respuesta']))
                                    {
                                        return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                                    }
                                }
                                else if($pregunta['gettipo']['nombre']=='Booleana')
                                {
                                    if(!is_bool((bool)$pregunta['respuesta']))
                                    {
                                        return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                                    }
                                }
                                else if($pregunta['gettipo']['nombre']=='Booleana justificada')
                                {
                                    if(!is_bool((bool)$pregunta['respuesta']))
                                    {
                                        return ['title'=>'Error', 'content'=>'La respuesta de la pregunta: '.$pregunta['enunciado'].' es inválida', 'type'=>'error'];
                                    }
                                    if(!array_key_exists('justificacion', $pregunta) || $pregunta['justificacion'] == null)
                                    {
                                        return ['title'=>'Error', 'content'=>'La justificación de la pregunta: '.$pregunta['enunciado'].' es obligatoria', 'type'=>'error'];
                                    }
                                }
                            }
                        }
                        // ////////////////////////
                    }
                        
                    
                }
            }
                
            
                
        }
        
        return $next($request);
    }
}
