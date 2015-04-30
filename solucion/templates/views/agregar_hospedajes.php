<div class="container">
    
  <div class="row">
      <div class="container">
    <h1>Insertar Hospedaje</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="insert" value="yes" />
        <div class="form-group">
            <label for="nombre"><?= _('Nombre')?></label>
            <input type="text" class="form-control" name="nombre" placeholder="<?= _('Nombre hospedaje')?>">
        </div>
        <div class="form-group">
            <label for="tipo"><?= _('Tipo de Hospedaje')?></label>
            <select class="form-control" name="tipo">
              <option value="hotel">Hotel</option>
              <option value="apartamento">Apartamento</option>
            </select>
        </div>
        
        <div class="apartamento">
            <div class="form-group">
                <label for="apartamento"><?= _('Número de apartamentos')?></label>
                <input type="number" class="form-control" name="apartamentos" placeholder="<?= _('Número de apartamentos')?>">
            </div>
            <div class="form-group">
                <label for="capacidad"><?= _('Capacidad por apartamento')?></label>
                <input type="number" class="form-control" name="capacidad_apartamento" placeholder="<?= _('Capacidad por apartamento')?>">
            </div>
        </div>
        <div class="hotel">
            <div class="form-group">
                <label for="estrellas"><?= _('Estrellas del Hotel')?></label>
                <select class="form-control" name="estrellas">
                     <option value="0">-</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>              
                </select>
            </div>
            <div class="form-group">
                <label for="tipo"><?= _('Tipo de Habitacion')?></label>
                <input type="text" class="form-control" name="tipo_habitacion" placeholder="<?= _('Tipo de Habitacion')?>">
            </div>
        </div>
        <div class="form-group">
            <label for="ciudad"><?= _('Ciudad')?></label>
            <input type="text" class="form-control" name="ciudad" placeholder="<?= _('Ciudad')?>">
        </div>
        <div class="form-group">
            <label for="provincia"><?= _('Provincia')?></label>
            <input type="text" class="form-control" name="provincia" placeholder="<?= _('Provincia')?>">
        </div>
       
      
      <button type="submit" class="btn btn-default"><?= _('Agregar')?></button>
    </form>
      </div>
  </div>
</div>