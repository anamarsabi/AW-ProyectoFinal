<div class="container centrado up-pad">
            <h2>Mi perfil</h2>
            <div class="main-login centrado">
                <form action="logic/procesar_habitacion.php" method="post" name="form_registrar_habitacion">
                    <div class="marg">
                        <p>Añadir habitación</p>
                
                        <label for="cama">Cama</label><br>
                        <input type="number" placeholder="Dimensiones en centimentros " name="cama" required><br>
                    
                        <label for="precio">Precio</label><br>
                        <input type="int" placeholder="Precio" name="precio" required><br><br>
                    
                        <label for="banio"> Baño
                        <input type="checkbox" name="banio" value='banio'></label><br>
                
                        <label for="gastos_incluidos">Gastos incluidos
                        <input type="checkbox" name="gastos_incluidos" value="gastos_incluidos"><label for="gastos_incluidos"></label><br>
                        
                        Una breve descripción sobre la habitacion
                        <textarea name="descripcion" rows="4" cols="100%" placeholder="Este habitacion ofrece..."></textarea><br>
                    
                        Disponibilidad
                        <input type="date" name="date" aria-describedby="date-format" min="1950-01-01" max="2031-01-01" />
                    </div>
                    <input type="submit" value="Registrar" style="color:white;">
                </form>
            </div>
        </div>