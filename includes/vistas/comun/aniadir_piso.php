<div class="container centrado up-pad">
            <h2>Mi perfil</h2>
            <div class="main-login centrado">
                <form action="logic/procesar_registro_piso.php" method="post" name="form_registrar_piso">
                    <div class="marg">
                        <h2>Añadir piso</h2>
                
                        <input class="input_ancho" type="text" placeholder="Calle" name="calle" required>
                        <input class="input_ancho" type="text" placeholder="Barrio" name="barrio">
                        <input class="input_ancho" type="text" placeholder="Ciudad" name="ciudad" required>
            
                        <input id="input_ancho" name="imagenes" size="30" type="file" placeholder="imagenes" required> 

                        <h5>Permite mascota?</h5>
                        <label>
                            <input class="input_ancho" type="radio" value="si" name="mascota">
                            Sí
                        </label>
                        <label>
                            <input class="input_ancho" type="radio" value="no" name="mascota">
                            No
                        </label>
            
                        <h5>Servicios ofrecidos</h5>
                        <div role="group" aria-labelledby="id-group-label">
                            <div class="checkboxes">
                                <input type="checkbox" name="servicios[]" value="Terraza">
                                <label for="Terraza">Terraza</label>
            
                                <input type="checkbox" name="servicios[]" value="Parking">
                                <label for="Parking">Parking</label>
                        
                                <input type="checkbox" name="servicios[]" value="Lavaplatos">
                                <label for="Lavaplatos">Lavaplatos</label><br>
                            </div>
                        </div>
            
                        <h5>Una breve descripción sobre el piso:</h5>
                        <textarea name="descripcion" rows="4" cols="100%" placeholder="Este piso ofrece..."></textarea>
                    </div>
                    <input type="submit" value="Registrar" style="color:white;">
                </form>
            </div>
        </div>
        <div class="container centrado up-pad">
            <h2>Mis pisos</h2>
            <div class="main-login centrado">
                <a href="logic/get_pisos_host.php">Ver mis pisos</a>
            </div>
        </div>