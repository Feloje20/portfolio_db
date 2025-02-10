
<label for="titulo">Título del Trabajo</label>
<input type="text" id="titulo" name="trabajos[titulo]" placeholder="Título del Trabajo" class="search-input <?php echo !empty($data["msjErrorTitulo"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['titulo']) ? $data['titulo'] : '' ?>"><?php if ($data["msjErrorTitulo"] != "") echo "<p class='error'>" . $data["msjErrorTitulo"] . "</p>";?>

<label for="descripcion">Descripción del Trabajo</label>
<textarea id="descripcion" name="trabajos[descripcion]" placeholder="Descripción del Trabajo" class="search-input <?php echo !empty($data["msjErrorDescripcion"]) ? 'errorInput' : '';?>"><?php echo isset($data['descripcion']) ? $data['descripcion'] : '' ?></textarea><?php if ($data["msjErrorDescripcion"] != "") echo "<p class='error'>" . $data["msjErrorDescripcion"] . "</p>";?>

<label for="fecha_inicio">Fecha Inicio</label>
<input type="date" id="fecha_inicio" name="trabajos[fecha_inicio]" placeholder="Fecha Inicio" class="search-input <?php echo !empty($data["msjErrorFechaInicial"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['fecha_inicio']) ? $data['fecha_inicio'] : '' ?>"><?php if ($data["msjErrorFechaInicial"] != "") echo "<p class='error'>" . $data["msjErrorFechaInicial"] . "</p>";?>

<label for="fecha_final">Fecha Final</label>
<input type="date" id="fecha_final" name="trabajos[fecha_final]" placeholder="Fecha Final" class="search-input <?php echo !empty($data["msjErrorFechaFinal"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['fecha_final']) ? $data['fecha_final'] : '' ?>"><?php if ($data["msjErrorFechaFinal"] != "") echo "<p class='error'>" . $data["msjErrorFechaFinal"] . "</p>";?>

<label for="logros">Logros del Trabajo</label>
<input type="text" id="logros" name="trabajos[logros]" placeholder="Logros del Trabajo" class="search-input <?php echo !empty($data["msjErrorLogros"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['logros']) ? $data['logros'] : '' ?>"><?php if ($data["msjErrorLogros"] != "") echo "<p class='error'>" . $data["msjErrorLogros"] . "</p>";?>

<label for="visible">Visible en el portfolio
<input type="checkbox" id="visible" name="trabajos[visible]" value="1" <?php echo isset($data['visible']) && $data['visible'] ? 'checked' : '' ?>>
</label>
