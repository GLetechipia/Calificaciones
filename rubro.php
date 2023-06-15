<!-- Bootstrap Modals --> 
<!-- Modal - agregar rubro -->
<div class="modal fade" id="rubro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
   
      <div class="modal-header">
        <h5 class="modal-title">Agregar rubro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
                 
      <div class="modal-body">
        <div class="form-group">
          <label for="id_rubro">No. Rubro</label>
          <input  type="text" id="idrubro" value=""  class="form-control"/>
        </div>
        <div class="form-group">
          <label for="Porcentaje">Porcentaje</label>
          <input type="text" id="Porcentaje" class="form-control" value=""/>
        </div>
        <div class="form-group">
          <label for="Descripcion">Descripcion</label>
          <input type="text" id="descripcion" value=""   class="form-control"/>
        </div>
       
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="addRecord()">Agregar</button>
      </div>
    </div>
  </div>
</div>
<!-- // Modal --> 