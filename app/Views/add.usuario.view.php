<!-- Content Row -->

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Alta usuario</h6>                                    
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <form action="/usuarios-sistema/add" method="post">         
                    <!--form method="get"-->
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="nombre">Nombre de usuario</label>
                            <input class="form-control" id="username" type="text" name="username" placeholder="MyUsername" value="My@test">
                            <p class="text-danger">El nombre de usuario sólo permite caracteres alfanuméricos y guiones bajos. Longitud entre 5 y 20 caracteres</p>
                        </div>
                        <div class="mb-3 col-sm-3">
                            <label for="pass">Contraseña</label>
                            <input class="form-control" id="pass" type="password" name="pass" placeholder="Sin modificar" value="">
                            <p class="text-danger">Los passwords no coinciden</p>
                        </div>
                        <div class="mb-3 col-sm-3">
                            <label for="pass2">Repetir Contraseña</label>
                            <input class="form-control" id="pass2" type="password" name="pass2" placeholder="Sin modificar" value="">
                            <p class="text-danger"></p>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" type="email" name="email" placeholder="miemail@dominio.org" value="">
                            <p class="text-danger">Campo obligatorio</p>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="id_rol">Rol del usuario</label>
                            <select class="form-control select2-container--default" name="id_rol">
                                <?php foreach ($roles as $rol){ ?>
                                <option value="<?php echo $rol['id_rol'] ?>"><?php echo $rol['nombre_rol'] ;?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger">Seleccione un rol válido</p>
                        </div>
                        <div class="mb-3 col-sm-2">
                            <label for="idioma">Idioma</label>
                            <select class="form-control" name="idioma">                                
                                <?php foreach ($idiomas as $id_idioma => $nombre_idioma ){ ?>
                                <option value="<?php echo $id_idoma ?>"><?php echo $nombre_idioma;?></option>
                                <?php } ?>                                                              
                            </select>
                            <p class="text-danger">Idioma inválido</p>
                        </div>
                        <div class="col-12 text-right">                            
                            <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                            <a href="/usuarios-sistema" class="btn btn-danger ml-3">Cancelar</a>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>