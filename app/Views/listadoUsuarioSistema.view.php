<div class="row">       
    <div class="col-12">
        <?php if(isset($_SESSION['mensaje'])){ ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['class']; ?>"><p> <?php echo $_SESSION['mensaje']['texto']; ?></p></div>
        <?php } ?>
    </div>
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <div class="col-6">
                    <h6 class="m-0 installfont-weight-bold text-primary">Usuarios del sistema</h6> 
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/usuarios-sistema/add/" class="btn btn-primary ml-1 float-right"> Nuevo Usuario del Sistema <i class="fas fa-plus-circle"></i></a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body" id="card_table">
                <div id="button_container" class="mb-3"></div>
                <!--<form action="./?sec=formulario" method="post">                   -->
                <table id="tabladatos" class="table table-striped">                    
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>                          
                            <th>Email</th>                            
                            <th>Rol</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($usuarios > 0) {
                        foreach ($usuarios as $usuario) {
                        ?>
                        <tr class="<?php echo ($usuario['baja'] == 1) ? "table-danger" : ""; ?>">
                            <td><?php echo $usuario['id_usuario'] ?></td>
                            <td><?php echo $usuario['username'] ?></td>
                            <td><?php echo $usuario['email'] ?></td>    
                            <td><?php echo $usuario['nombre_rol'] ?></td>   
                            <td>
                                <?php if (strpos($_SESSION['permisos']['usuarios'], 'w') !== false){ ?>
                                <a href="/usuarios-sistema/edit/<?php echo $usuario['id_usuario'] ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                                <?php
                                }
                                if (strpos($_SESSION['permisos']['usuarios'], 'd') !== false){
                                ?>
                                <a href="/usuarios-sistema/baja/<?php echo $usuario['id_usuario'] ?>" class="btn btn-<?php echo ($usuario['baja'] == 1) ? 'secondary' : 'primary' ?>"> <i class="fas fa-toggle-<?php echo ($usuario['baja'] == 1) ? 'off' : 'on' ?>"></i></a>
                                <a href="/usuarios-sistema/delete/<?php echo $usuario['id_usuario'] ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                <?php } ?>
                            </td>

                        </tr>
                        <?php
                        }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        Total de registros: 3 </tfoot>
                </table>
            </div>
        </div>
    </div>                        
</div>
