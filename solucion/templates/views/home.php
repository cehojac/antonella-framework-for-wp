<!--aqui va la home-->

<div>
    <div class="text-center">
    <img src="assets/img/destinia.jpg" alt="logo destinia"  />
    </div>
    <h1  class="text-center"><?= _("Buscar Alojamiento")?></h1>
    <div class="container">
        <div class="row">
            <div class="container">
                
          
               
                <div class="form-group">
                    <label for="shorturl"><h2><?= _('Buscar')?></h1></label>
                    <input type="text" class="form-control" name="search" id="search" placeholder="<?= _('Buscar hotel o apartamento')?>"
                </div>
               
           
        </div>
       
        
            
            <div class="table-responsive" id="result">
                <?php if($data):?>
                <table class="table table-striped">
                    <tr>
                        <th><?= _('')?></th>
                        <th><?= _("Url corta")?></th>
                        <th><?= _("Url Larga")?></th>
                        <th><?= _("Descripción")?></th>
                    </tr>
                    <?php  foreach ( $data as $dato ) {?>
                    <tr>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete" value="yes" />
                                <input type="hidden" name="id" value="<?= $dato[0]?>" />
                                <button type="submit" class="btn btn-default"><?= _('Borrar')?></button>
                            </form>
                            
                        </td>
                        <td><a href="http://tipeos.com/<?= $dato[1]  ?>"><?= $dato[1]  ?></a> </td>
                        <td><a href="http://tipeos.com/<?= $dato[1]  ?>"><?= $dato[2] ?></a></td>
                        <td><?= $dato[4] ?></td>
                    </tr>
                    <?php  } ?>
                </table>
                <?php endif; ?>
            </div>
        </div>
     
    </div>
    
</div>