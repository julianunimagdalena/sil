<div class="toolbar" >

    <span id="title-toolbar"></span>
    <div class="left">
        <!-- <button type="button" data-nav="#menu" title="Opciones"><span class="glyphicon glyphicon-th"></span></button> -->
    </div>
    <div class="right">
        
        <!-- <button type="button" data-nav="#help" title="Ayuda"><span class="glyphicon glyphicon-question-sign"></span></button>
        <button type="button" data-nav="#account" title="Opciones Usuario"><span class="glyphicon glyphicon-user"></span></button> -->
        
    </div>
    <div id="menu" class="nav-lateral nav-lateral-left">
        <a href="/">
            <!-- <div class="brand">

                <img src="https://cdn.unimagdalena.edu.co/images/escudo/bg_light/512.png" alt="Logo" />
                <h1>Talento Magdalena</h1>
            </div> -->
        </a>
        

    </div>

    <div id="help" class="nav-lateral nav-lateral-right">
       <!--  <div class="title-help">
            <span class="ion-help-circled big-glyphicon"></span> <div>Ayuda</div>
        </div>
        <hr />
            
        <p style="padding: 1em;">
            Ayuda
        </p> -->
        
        
    </div>
    <div id="account" class="nav-lateral nav-lateral-right">

            <div style="margin: 1em 0;">
                <div class="photo">
                    <span class="glyphicon glyphicon-user"></span>
                    
                </div>
            </div>
            <hr>
            <h4 style="text-align: center;" ng-if=""></h4>
            <h4 style="text-align: center;" ng-if=""></h4>
            <h6 ng-if=""></h6>
            <h6 ng-if="">[No hay roles asignados a este usuario]</h6>
            </hr>
                
            <!-- <ul>
                <li>
                    @if(Auth::check())
                    <a>
                        <span class="icon"><i class="glyphicon glyphicon-user"></i></span><span class="text">{{Auth::user()->username}}</span>
                    </a>
                       @endif
                </li>
                <li>
                    <a href="/logout">
                        <span class="icon"><i class="glyphicon glyphicon-log-out"></i></span><span class="text">Cerrar sesión</span>
                    </a>
                </li>
            </ul>
 -->
        
    </div>

</div>
