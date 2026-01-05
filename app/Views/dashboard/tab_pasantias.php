<div id="pasantias" class="tab-pane">
    <h2 class="text-3xl font-bold text-superarse-morado-oscuro mb-6 border-b pb-2">Gestión de Prácticas
        Pre-Profesionales</h2>

    <div class="flex justify-between items-center mb-6 border-b pb-2">

        <?php if (!empty($data['infoPractica']['ruc'])): // Solo si la Fase 1 está completa
        ?>
            <?php $id_practica = htmlspecialchars($data['infoPractica']['id_practica'] ?? ''); ?>

            <a href="<?php echo $this->basePath; ?>/pasantias/generatePdf/<?php echo $id_practica; ?>"
                target="_blank"
                class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-superarse-morado-oscuro hover:bg-superarse-rosa focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-superarse-morado-oscuro transition duration-300">
                <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
            </a>
        <?php endif; ?>
    </div>

    <!-- ###################################################################### -->
    <!--                                 FASE 1: REGISTRO INICIAL               -->
    <!-- ###################################################################### -->
    <div style="<?php
                if (isset($data['infoPractica'])) {
                    echo 'display:none;';
                } else {
                    echo 'display:block;';
                }
                ?>">
        <div class="bg-yellow-100 border-l-4 border-superarse-rosa text-gray-800 p-4 mb-6 rounded-md">
            <p class="font-bold">FASE 1: REGISTRO PENDIENTE</p>
            <p class="text-sm">Completa el formulario de registro y asignación para desbloquear la Fase 2.
            </p>
        </div>
    </div>

    <form action="<?php echo $this->basePath; ?>/pasantias/saveFaseOne" method="POST"
        class="space-y-8 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
        <h3 class="text-xl font-semibold text-superarse-morado-medio">1. Información del Estudiante</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm bg-gray-50 p-4 rounded-lg">
            <p><strong>Código:</strong>
                <?php echo htmlspecialchars($data['infoPersonal']['codigo_matricula'] ?? 'N/D'); ?></p>
            <p><strong>Número de Identificación:</strong>
                <?php echo htmlspecialchars($data['infoPersonal']['numero_identificacion'] ?? 'N/D'); ?>
            </p>
            <p class="md:col-span-3"><strong>Nombre:</strong>
                <?php echo htmlspecialchars($data['nombreCompleto']); ?></p>
            <p><strong>Correo Inst.:</strong>
                <?php echo htmlspecialchars($data['infoPersonal']['usuario'] ?? 'N/D'); ?>
            </p>
            <p><strong>Nivel:</strong>
                <?php echo htmlspecialchars($data['infoPersonal']['nivel'] ?? 'N/D'); ?></p>
            <p><strong>Carrera/Campus:</strong>
                <?php echo htmlspecialchars($data['infoPersonal']['programa'] ?? 'N/D'); ?>
                <?php echo htmlspecialchars($data['infoPersonal']['sede'] ?? 'N/D'); ?></p>
        </div>

        <hr class="border-superarse-morado-medio/20">

        <h3 class="text-xl font-semibold text-superarse-morado-medio">2. Selección de Práctica y
            Asignaciones</h3>

        <!-- Docente Asignado -->
        <div>
            <label for="tutor_academico" class="block text-gray-700 font-medium mb-2">
                Su Tutor Académico es <span class="text-superarse-rosa">*</span>
            </label>

            <?php if ($data['cantidadTutores'] > 1): ?>
                <!-- cuando hay múltiples tutores -->
                <select
                    id="tutor_academico"
                    name="tutor_academico"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-superarse-rosa focus:border-transparent"
                    required
                    onchange="actualizarInfoTutor()">
                    <option value="" data-email="" data-telefono="">Seleccione un tutor académico</option>
                    <?php foreach ($data['tutoresAcademicos'] as $tutor): ?>
                        <option
                            value="<?php echo htmlspecialchars($tutor['id'] ?? ''); ?>"
                            data-email="<?php echo htmlspecialchars($tutor['email'] ?? ''); ?>"
                            data-telefono="<?php echo htmlspecialchars($tutor['telefono'] ?? ''); ?>"
                            data-nombre="<?php echo htmlspecialchars($tutor['nombre_completo'] ?? ''); ?>">
                            <?php echo htmlspecialchars($tutor['nombre_completo'] ?? 'Sin nombre'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($data['cantidadTutores'] == 1): ?>
                <!-- Input readonly cuando hay solo un tutor -->
                <input
                    type="text"
                    id="tutor_academico"
                    name="tutor_academico"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                    readonly
                    value="<?php echo htmlspecialchars($data['tutoresAcademicos'][0]['nombre_completo'] ?? 'N/D'); ?>">
                <input type="hidden" name="tutor_academico_id" value="<?php echo htmlspecialchars($data['tutoresAcademicos'][0]['id'] ?? ''); ?>">
            <?php else: ?>
                <!-- Input cuando no hay tutores -->
                <input
                    type="text"
                    id="tutor_academico"
                    name="tutor_academico"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                    readonly
                    value="N/D - Sin tutor asignado">
            <?php endif; ?>
        </div>

        <div>
            <label for="correo_tutor" class="block text-gray-700 font-medium mb-2">
                Correo del Tutor Académico
            </label>
            <input
                type="email"
                id="correo_tutor"
                name="correo_tutor"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                readonly
                value="<?php
                        if ($data['cantidadTutores'] == 1) {
                            echo htmlspecialchars($data['tutoresAcademicos'][0]['email'] ?? 'N/D');
                        } else {
                            echo 'N/D';
                        }
                        ?>">
        </div>

        <!-- Modalidad -->
        <div>
            <label for="modalidad" class="block text-gray-700 font-medium mb-2">
                Escoja la Modalidad de Práctica <span class="text-superarse-rosa">*</span>
            </label>
            <select id="modalidad" name="modalidad" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                <?php echo !empty($data['infoPractica']['modalidad']) ? 'disabled' : ''; ?>>

                <option value="<?php echo htmlspecialchars($data['infoPractica']['id_practica_modalidad'] ?? ''); ?>"><?php echo htmlspecialchars($data['infoPractica']['modalidad'] ?? '-- Seleccione una opción --'); ?></option>

                <?php if (!empty($data['modalidades']) && is_array($data['modalidades'])): ?>
                    <?php foreach ($data['modalidades'] as $modalidad): ?>
                        <option value="<?php echo htmlspecialchars($modalidad['id_practica_modalidad']); ?>">
                            <?php echo htmlspecialchars($modalidad['modalidad']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <hr class="border-superarse-morado-medio/20">

        <!-- Registro de Empresa (Entidad) -->
        <div id="seccion-empresa" class="hidden">
            <h3 class="text-xl font-semibold text-superarse-morado-medio">3. Registro de Empresa / Institución</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- RUC de la Empresa -->
                <div class="md:col-span-2">
                    <label for="entidad_ruc" class="block text-gray-700 font-medium mb-2">
                        RUC de la Empresa <span class="text-superarse-rosa">*</span>
                    </label>
                    <div class="flex gap-2 relative">
                        <input type="text" id="entidad_ruc" name="entidad_ruc" required
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                            placeholder="Ingrese el RUC de la empresa"
                            value="<?php echo htmlspecialchars($data['infoPractica']['ruc'] ?? ''); ?>"
                            <?php echo !empty($data['infoPractica']['ruc']) ? 'disabled' : ''; ?>>

                        <?php if (empty($data['infoPractica']['ruc'])): ?>
                            <button type="button" id="btn_buscar_ruc"
                                class="px-6 py-2 bg-superarse-morado-medio text-white rounded-lg hover:bg-superarse-morado-oscuro transition duration-300 flex items-center gap-2">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        <?php endif; ?>
                    </div>
                    <div id="mensaje_busqueda_ruc" class="mt-2 text-sm"></div>
                    <!-- Resultado de la búsqueda -->
                    <div id="entidad_resultado" class="mt-3 p-3 rounded-lg hidden animate-slide-in-up">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-building text-superarse-morado-medio text-lg"></i>
                            <span class="text-sm font-semibold text-gray-700">Empresa Encontrada:</span>
                        </div>
                        <p id="entidad_nombre_resultado" class="text-sm font-bold text-superarse-morado-medio ml-6"></p>
                    </div>
                    <small class="text-gray-500 text-xs mt-1 block">
                        <i class="fas fa-info-circle"></i> Busque la empresa en nuestra base de datos o ingrese los datos manualmente
                    </small>
                </div>

                <!-- Nombre de la Empresa -->
                <div>
                    <label for="entidad_nombre_empresa" class="block text-gray-700 font-medium mb-2">
                        Nombre de la Empresa <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="entidad_nombre_empresa" name="entidad_nombre_empresa" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Nombre comercial de la empresa" value="<?php echo htmlspecialchars($data['infoPractica']['nombre_empresa'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['nombre_empresa']) ? 'disabled' : ''; ?>>
                </div>

                <!-- Razón Social -->
                <div>
                    <label for="entidad_razon_social" class="block text-gray-700 font-medium mb-2">
                        Razón Social <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="entidad_razon_social" name="entidad_razon_social" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Razón social de la empresa" value="<?php echo htmlspecialchars($data['infoPractica']['razon_social'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['razon_social']) ? 'disabled' : ''; ?>>
                </div>

                <!-- Persona de Contacto -->
                <div id="EntidadPersonaContacto">
                    <label for="entidad_persona_contacto" class="block text-gray-700 font-medium mb-2">
                        Persona de Contacto <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="entidad_persona_contacto" name="entidad_persona_contacto" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Nombre completo del contacto" value="<?php echo htmlspecialchars($data['infoPractica']['persona_contacto'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['persona_contacto']) ? 'disabled' : ''; ?>>
                </div>

                <!-- Teléfono de Contacto -->
                <div id="EntidadTelefonoContacto">
                    <label for="entidad_telefono_contacto" class="block text-gray-700 font-medium mb-2">
                        Teléfono de Contacto <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="entidad_telefono_contacto" name="entidad_telefono_contacto" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Teléfono de la empresa" value="<?php echo htmlspecialchars($data['infoPractica']['telefono_contacto'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['telefono_contacto']) ? 'disabled' : ''; ?>>
                </div>

                <!-- Email de Contacto -->
                <div id="EntidadEmailContacto">
                    <label for="entidad_email_contacto" class="block text-gray-700 font-medium mb-2">
                        Email de Contacto <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="email" id="entidad_email_contacto" name="entidad_email_contacto" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="email@empresa.com" value="<?php echo htmlspecialchars($data['infoPractica']['email_contacto'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['email_contacto']) ? 'disabled' : ''; ?>>
                </div>


                <div id="EntidadPlazasDisponibles">
                    <label for="plazas_disponibles" class="block text-gray-700 font-medium mb-2">
                        Vacantes Disponibles <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="number" id="plazas_disponibles" name="plazas_disponibles"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Ingrese el número de vacantes"
                        value="<?php echo htmlspecialchars($data['infoPractica']['plazas_disponibles'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['plazas_disponibles']) ? 'readonly' : ''; ?>>
                </div>

                <div id="EntidadAfiliacionIESS" style="display: none;">
                    <label for="afiliacion_iees" class="block text-gray-700 font-medium mb-2">
                        Años o meses de afiliación al IESS <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="afiliacion_iees" name="afiliacion_iees"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Ej: 1 año, 6 meses, etc."
                        value="<?php echo htmlspecialchars($data['infoPractica']['afiliacion_iess'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['afiliacion_iess']) ? 'readonly' : ''; ?>>
                </div>

                <!-- Dirección (opcional) -->
                <div class="md:col-span-2">
                    <label for="entidad_direccion" class="block text-gray-700 font-medium mb-2">
                        Dirección
                    </label>
                    <input type="text" id="entidad_direccion" name="entidad_direccion"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Dirección completa de la empresa" value="<?php echo htmlspecialchars($data['infoPractica']['direccion'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['direccion']) ? 'disabled' : ''; ?>>
                </div>

                <div id="TablaProyectos" class="md:col-span-2">
                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                        <thead>
                            <tr>
                                <th style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">Proyecto</th>
                                <th style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">Carreras</th>
                                <th style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">Lugar</th>
                                <th style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['infoProyectos'])): ?>
                                <?php foreach ($data['infoProyectos'] as $key => $proyecto): ?>
                                    <tr>
                                        <td style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">
                                            <?php echo htmlspecialchars($proyecto['descripcion']); ?>
                                        </td>
                                        <td style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">
                                            <?php echo htmlspecialchars($proyecto['carreras']); ?>
                                        </td>
                                        <td style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">
                                            <?php echo htmlspecialchars($proyecto['lugar']); ?>
                                        </td>
                                        <td style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">
                                            <input
                                                type="radio"
                                                name="proyecto_seleccionado"
                                                value="<?php echo htmlspecialchars($proyecto['id']); ?>"
                                                required>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="border: 2px solid #000; padding: 10px; text-align: left; vertical-align: top; background-color: #ffffffff; color: #000000ff;">No hay proyectos disponibles.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <hr class="border-superarse-morado-medio/20">

        <!-- Registro del Tutor Empresarial -->
        <div id="seccion-tutor-empresa" class="hidden">
            <h3 id="labelInfoTutor" class="text-xl font-semibold text-superarse-morado-medio">4. Información del Tutor Empresarial</h3>
            <div id="informacion-tutor-empresarial" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tutor_emp_nombre_completo" class="block text-gray-700 font-medium mb-2">
                        Nombre Completo del Tutor <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="tutor_emp_nombre_completo" name="tutor_emp_nombre_completo" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Nombre completo del tutor" value="<?php echo htmlspecialchars($data['infoPractica']['nombre_completo'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['nombre_completo']) ? 'disabled' : ''; ?>>
                </div>

                <div>
                    <label for="tutor_emp_cedula" class="block text-gray-700 font-medium mb-2">
                        Cédula del Tutor <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="tutor_emp_cedula" name="tutor_emp_cedula" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Número de cédula" value="<?php echo htmlspecialchars($data['infoPractica']['cedula'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['cedula']) ? 'disabled' : ''; ?>>
                </div>

                <div>
                    <label for="tutor_emp_funcion" class="block text-gray-700 font-medium mb-2">
                        Función / Cargo <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="tutor_emp_funcion" name="tutor_emp_funcion" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Cargo del tutor" value="<?php echo htmlspecialchars($data['infoPractica']['funcion'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['funcion']) ? 'disabled' : ''; ?>>
                </div>

                <div>
                    <label for="tutor_emp_email" class="block text-gray-700 font-medium mb-2">
                        Email del Tutor <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="email" id="tutor_emp_email" name="tutor_emp_email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="email@tutor.com" value="<?php echo htmlspecialchars($data['infoPractica']['email'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['email']) ? 'disabled' : ''; ?>>
                </div>

                <div>
                    <label for="tutor_emp_telefono" class="block text-gray-700 font-medium mb-2">
                        Teléfono <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="tutor_emp_telefono" name="tutor_emp_telefono" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Teléfono del tutor" value="<?php echo htmlspecialchars($data['infoPractica']['telefono'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['telefono']) ? 'disabled' : ''; ?>>
                </div>

                <div>
                    <label for="tutor_emp_departamento" class="block text-gray-700 font-medium mb-2">
                        Departamento/Área <span class="text-superarse-rosa">*</span>
                    </label>
                    <input type="text" id="tutor_emp_departamento" name="tutor_emp_departamento" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio"
                        placeholder="Departamento del tutor" value="<?php echo htmlspecialchars($data['infoPractica']['departamento'] ?? ''); ?>"
                        <?php echo !empty($data['infoPractica']['departamento']) ? 'disabled' : ''; ?>>
                </div>
            </div>
        </div>

        <?php
        // Verificamos si la práctica ya tiene el RUC de la empresa asociado.
        // Asumimos que si tiene RUC, la Fase 1 está completa y ya NO se debe guardar, sino descargar.
        $fase_uno_completada = !empty($data['infoPractica']['ruc']);
        ?>

        <?php if (!$fase_uno_completada): ?>
            <button type="submit"
                class="w-full bg-superarse-rosa hover:bg-superarse-morado-medio text-white font-bold py-3 rounded-lg transition duration-300 mt-6">
                Guardar Registro e Iniciar Práctica (Fase 1 Completa)
            </button>

        <?php else: ?>
            <?php
            // Obtenemos el ID de la práctica, necesario para la URL del controlador
            $id_practica = htmlspecialchars($data['infoPractica']['id_practica'] ?? '');

            // Verificación de seguridad: solo mostrar si tenemos un ID
            if (!empty($id_practica)):
            ?>
                <a href="<?php echo $this->basePath; ?>/pasantias/generatePdf/<?php echo $id_practica; ?>"
                    target="_blank"
                    class="inline-block w-full text-center bg-superarse-morado-oscuro hover:bg-superarse-rosa text-white font-bold py-3 rounded-lg transition duration-300 mt-6">
                    <i class="fas fa-file-pdf mr-2"></i> Descargar Registro de Práctica (Fase 1)
                </a>
            <?php endif; ?>

        <?php endif; ?>

    </form>

    <!-- ###################################################################### -->
    <!--                               FASE 2: SEGUIMIENTO Y GESTIÓN                         -->
    <!-- ###################################################################### -->
    <div style="<?php
                if (isset($data['infoPractica']) && isset($data['infoStatusPractica']['estado_fase_uno_completado']) && $data['infoStatusPractica']['estado_fase_uno_completado'] == 1) {
                    echo 'display:block;';
                } else {
                    echo 'display:none;';
                }
                ?>">
        <div class="bg-green-100 border-l-4 border-green-500 text-gray-800 p-4 mb-6 rounded-lg">
            <p class="font-bold">FASE 2: EN EJECUCIÓN</p>
            <p class="text-sm">Tu registro ha sido aprobado. Gestiona tu práctica a continuación.</p>
        </div>
    </div>
    <div style="<?php
                if (isset($data['infoPractica']) && isset($data['infoStatusPractica']['estado_fase_uno_completado']) && $data['infoStatusPractica']['estado_fase_uno_completado'] == 1) {
                    echo 'display:block;';
                } else {
                    echo 'display:none;';
                }
                ?>" x-data="{ currentTab: 'programa' }">

        <!-- Menú de pestañas -->
        <div class="flex border-b border-gray-200 overflow-x-auto">
            <button @click="currentTab = 'programa'"
                :class="{ 'border-superarse-morado-oscuro text-superarse-morado-oscuro font-bold': currentTab === 'programa' }"
                class="flex-shrink-0 py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-superarse-morado-medio hover:text-superarse-morado-medio transition duration-150 rounded-t-lg">
                <i class="fas fa-list-check mr-2"></i> Programa de Trabajo
            </button>

            <button @click="currentTab = 'actividades'"
                :class="{ 'border-superarse-morado-oscuro text-superarse-morado-oscuro font-bold': currentTab === 'actividades' }"
                class="flex-shrink-0 py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-superarse-morado-medio hover:text-superarse-morado-medio transition duration-150 rounded-t-lg">
                <i class="fas fa-calendar-check mr-2"></i> Actividades Diarias
            </button>

            <button @click="currentTab = 'calificaciones'"
                :class="{ 'border-superarse-morado-oscuro text-superarse-morado-oscuro font-bold': currentTab === 'calificaciones' }"
                class="flex-shrink-0 py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-superarse-morado-medio hover:text-superarse-morado-medio transition duration-150 rounded-t-lg">
                <i class="fas fa-graduation-cap mr-2"></i> Calificaciones
            </button>

            <button @click="currentTab = 'manual'"
                :class="{ 'border-superarse-morado-oscuro text-superarse-morado-oscuro font-bold': currentTab === 'manual' }"
                class="flex-shrink-0 py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-superarse-morado-medio hover:text-superarse-morado-medio transition duration-150 rounded-t-lg">
                <i class="fas fa-book-open mr-2"></i> Documentación
            </button>
        </div>

        <div class="p-4 bg-gray-50 border border-gray-200 rounded-b-lg">
            <div x-show="currentTab === 'programa'">
                <?php
                include __DIR__ . './../estudiantes/programa_trabajo.php';
                ?>
            </div>


            <div x-show="currentTab === 'actividades'">
                <?php
                include __DIR__ . './../estudiantes/actividades_diarias.php';
                ?>
            </div>

            <div x-show="currentTab === 'calificaciones'">
                <p>Aquí verás tus <strong>Calificaciones</strong>.
                    <a href="https://site2.q10.com/login?ReturnUrl=%2F&aplentId=610f5afd-3e65-4c60-9932-bff02c235882"
                        target="_blank"
                        class="text-blue-600 hover:underline font-semibold">
                        Acceder a Q10 →
                    </a>
                </p>
            </div>

            <div x-show="currentTab === 'manual'">
                <div style="font-size: 80%;">
                    <h2 class="text-2xl font-extrabold text-purple-800 mb-4">Manuales y Video Tutoriales</h2>
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Manual Usuario -->
                        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <!-- Icono PDF -->
                                <svg class="text-red-500 w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9a1 1 0 0 0 1 1h5.5L13 3.5z" />
                                </svg>
                                <span class="text-lg md:text-xl font-semibold text-purple-700">Manual de Usuario del Proceso</span>
                            </div>
                            <p class="text-gray-600 mb-4 text-sm md:text-base">
                                Descarga el manual completo para conocer los lineamientos de las prácticas.
                            </p>
                            <a href="URL_DEL_MANUAL.pdf" target="_blank" class="inline-flex items-center gap-1 text-pink-600 font-bold text-base hover:underline">
                                Abrir Manual (PDF)
                                <!-- Icono Descarga -->
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 3a1 1 0 1 0 2 0v7.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L9 10.586V3z" />
                                    <path d="M5 18a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-2a1 1 0 1 0-2 0v1H7v-1a1 1 0 1 0-2 0v2z" />
                                </svg>
                            </a>
                        </div>
                        <!-- Video Tutorial -->
                        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <!-- Icono Video -->
                                <svg class="text-blue-500 w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 7l-5.197 2.598A1 1 0 0 1 14 10.382V13.618a1 1 0 0 1 1.803.784L21 17M5 7h12a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z" />
                                </svg>
                                <span class="text-lg md:text-xl font-semibold text-purple-700">Video Tutorial (Registro de Actividades)</span>
                            </div>
                            <p class="text-gray-600 mb-4 text-sm md:text-base">
                                Mira el video que explica cómo registrar tus actividades diarias correctamente.
                            </p>
                            <a href="URL_DEL_VIDEO" target="_blank" class="inline-flex items-center gap-1 text-pink-600 font-bold text-base hover:underline">
                                Ver Video Ahora
                                <!-- Icono Play -->
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="currentColor" />
                                    <polygon points="10,8 16,12 10,16" fill="#fff" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="mt-4 p-3 border rounded-lg
            <?php echo strpos($_SESSION['mensaje'], 'Error') !== false ? 'bg-red-100 border-red-300 text-red-700' : 'bg-green-100 border-green-300 text-green-700'; ?>">
                    <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?> -->
        </div>
    </div>

    <div class="pt-6">
        <!-- Contenido: Programa de Trabajo -->
        <div x-show="currentTab === 'programa'"
            class="bg-white p-4 sm:p-6 rounded-lg shadow-inner border border-gray-100">
            <h4 class="text-xl font-bold mb-4 text-superarse-morado-oscuro">Planificación de
                Actividades</h4>

            <!-- Formulario de nueva actividad -->
            <form @submit.prevent="addActivity"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 border border-indigo-100 rounded-lg bg-indigo-50">
                <div class="md:col-span-2">
                    <label for="newActivity" class="block text-sm font-medium text-gray-700">Actividad
                        Planificada</label>
                    <input type="text" id="newActivity" x-model="newActivity" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-superarse-morado-oscuro focus:ring-superarse-morado-oscuro p-2 border">
                </div>
                <div>
                    <label for="newDate" class="block text-sm font-medium text-gray-700">Fecha
                        Estimada</label>
                    <input type="date" id="newDate" x-model="newDate" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-superarse-morado-oscuro focus:ring-superarse-morado-oscuro p-2 border">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-superarse-morado-oscuro hover:bg-superarse-morado-medio transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Agregar
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto mt-4">
                <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actividad Planificada</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                Fecha</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="actividad in programa_trabajo" :key="actividad.id">
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900" x-text="actividad.actividad">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="actividad.fecha">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="console.log('Editando actividad', actividad.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3 text-xs md:text-sm">Editar</button>
                                    <button @click="deleteActivity(actividad.id)"
                                        class="text-red-600 hover:text-red-900 text-xs md:text-sm">Eliminar</button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="programa_trabajo.length === 0">
                            <td colspan="3" class="text-center py-6 text-gray-500 italic">Aún no hay
                                actividades registradas en el Programa de Trabajo.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contenido: Actividades Diarias -->
        <div x-show="currentTab === 'actividades'" style="display: none;"
            class="bg-white p-4 sm:p-6 rounded-lg shadow-inner border border-gray-100">
            <h4 class="text-xl font-bold mb-4 text-superarse-morado-oscuro">Reporte Diario de Horas
            </h4>

            <!-- Formulario para registrar actividad diaria (con max 6 horas) -->
            <form @submit.prevent="addDailyActivity"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 border border-superarse-rosa rounded-lg bg-pink-50">
                <div>
                    <label for="dailyDate" class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="date" id="dailyDate" x-model="newDailyDate" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-superarse-rosa focus:ring-superarse-rosa p-2 border">
                </div>
                <div>
                    <label for="dailyHours" class="block text-sm font-medium text-gray-700">Horas
                        (Max 6)</label>
                    <input type="number" id="dailyHours" x-model.number="newDailyHours" min="1" max="6" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-superarse-rosa focus:ring-superarse-rosa p-2 border">
                    <p x-show="newDailyHours > 6" class="mt-1 text-xs text-red-600 font-semibold">
                        Máximo 6 horas permitidas por día.</p>
                </div>
                <div class="md:col-span-1">
                    <label for="dailyActivity" class="block text-sm font-medium text-gray-700">Actividad
                        Realizada</label>
                    <input type="text" id="dailyActivity" x-model="newDailyActivity" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-superarse-rosa focus:ring-superarse-rosa p-2 border">
                </div>
                <div class="flex items-end">
                    <button type="submit" :disabled="newDailyHours > 6"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-superarse-rosa disabled:bg-gray-400 hover:bg-superarse-morado-medio transition duration-300">
                        <i class="fas fa-clock mr-2"></i> Registrar Horas
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto mt-4">
                <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Horas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actividad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="item in actividades_diarias" :key="item.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    x-text="item.fecha_actividad"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-superarse-rosa font-bold"
                                    x-text="item.horas_invertidas"></td>
                                <td class="px-6 py-4 text-sm text-gray-500" x-text="item.actividad_realizada"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="console.log('Editando reporte', item.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3 text-xs md:text-sm">Editar</button>
                                    <button @click="deleteDailyActivity(item.id)"
                                        class="text-red-600 hover:text-red-900 text-xs md:text-sm">Eliminar</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contenido: Calificaciones -->
        <div x-show="currentTab === 'calificaciones'" style="display: none;"
            class="bg-white p-6 rounded-lg shadow-inner border border-gray-100 text-center">
            <h4 class="text-xl font-bold mb-4 text-superarse-morado-oscuro">Consulta de
                Calificaciones (Q10)</h4>
            <p class="mb-6 text-gray-700">Para revisar tus calificaciones de prácticas, haz clic en
                el siguiente enlace. Serás redirigido al sistema académico Q10.</p>
            <a href="https://superarse.q10.com" target="_blank"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-superarse-rosa hover:bg-superarse-morado-medio transition duration-300 transform hover:scale-105">
                Ir a Q10 para ver Notas <i class="fas fa-external-link-alt ml-3"></i>
            </a>
        </div>

        <!-- Contenido: Documentación -->
        <div x-show="currentTab === 'manual'" style="display: none;"
            class="bg-white p-6 rounded-lg shadow-inner border border-gray-100">
            <h4 class="text-xl font-bold mb-4 text-superarse-morado-oscuro">Manuales y Video
                Tutoriales</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Manual de Usuario (PDF) -->
                <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                    <h5 class="font-semibold text-superarse-morado-medio mb-2 flex items-center"><i
                            class="fas fa-file-pdf mr-2 text-red-500"></i> Manual de Usuario del
                        Proceso</h5>
                    <p class="text-sm text-gray-600 mb-3">Descarga el manual completo para conocer
                        los lineamientos de las prácticas.</p>
                    <a href="URL_DEL_PDF" target="_blank"
                        class="text-superarse-rosa hover:text-superarse-morado-oscuro font-medium">Abrir
                        Manual (PDF) <i class="fas fa-download ml-1"></i></a>
                </div>

                <!-- Video Tutorial (Botón para abrir Modal) -->
                <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                    <h5 class="font-semibold text-superarse-morado-medio mb-2 flex items-center"><i
                            class="fas fa-video mr-2 text-blue-500"></i> Video Tutorial (Registro de
                        Actividades)</h5>
                    <p class="text-sm text-gray-600 mb-3">Mira el video que explica cómo registrar
                        tus actividades diarias correctamente.</p>
                    <!-- Reemplazo de alert() con Alpine.js Modal -->
                    <button @click="showVideoModal = true"
                        class="text-superarse-rosa hover:text-superarse-morado-oscuro font-medium">Ver
                        Video Ahora <i class="fas fa-circle-play ml-1"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL para el Video Tutorial (Reemplazo de alert()) -->
    <div x-show="showVideoModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Fondo de Opacidad -->
            <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showVideoModal = false"
                aria-hidden="true"></div>

            <!-- Contenido del Modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-video text-blue-600 text-lg"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Reproducción del Video Tutorial
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Aquí se cargaría el video de YouTube/Vimeo para su reproducción
                                    instantánea. Por ahora, es una simulación.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showVideoModal = false"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-superarse-morado-oscuro text-base font-medium text-white hover:bg-superarse-morado-medio focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const $ = id => document.getElementById(id);
            const btnBuscarRuc = $('btn_buscar_ruc');
            const inputRuc = $('entidad_ruc');
            const entidadResultado = $('entidad_resultado');
            const entidadNombreResultado = $('entidad_nombre_resultado');
            const modalidadSelect = $('modalidad');
            const infoTutorEmpresarial = $('informacion-tutor-empresarial');
            const afiliacionIESS = document.getElementById('EntidadAfiliacionIESS');
            const inputAfiliacionIESS = document.getElementById('afiliacion_iees');
            const inputFields = {
                entidad: {
                    nombre_empresa: $('entidad_nombre_empresa'),
                    razon_social: $('entidad_razon_social'),
                    persona_contacto: $('entidad_persona_contacto'),
                    telefono_contacto: $('entidad_telefono_contacto'),
                    email_contacto: $('entidad_email_contacto'),
                    direccion: $('entidad_direccion'),
                    plazas_disponibles: $('plazas_disponibles')
                },
                tutor: {
                    nombre_completo: $('tutor_emp_nombre_completo'),
                    cedula: $('tutor_emp_cedula'),
                    funcion: $('tutor_emp_funcion'),
                    email: $('tutor_emp_email'),
                    telefono: $('tutor_emp_telefono'),
                    departamento: $('tutor_emp_departamento')
                }
            };
            const labelFields = {
                persona_contacto: $('EntidadPersonaContacto'),
                telefono_contacto: $('EntidadTelefonoContacto'),
                email_contacto: $('EntidadEmailContacto'),
                plazas_disponibles: $('EntidadPlazasDisponibles')
            }
            const tablaProyectos = $('TablaProyectos');
            const labelInfoTutor = $('labelInfoTutor');
            const rucContainer = inputRuc.closest('.md\\:col-span-2');
            const idPrograma = <?php echo json_encode($data['infoPrograma']['id'] ?? null); ?>;
            let timeoutBusqueda = null;

            // Notificación
            function notificar(mensaje, tipo = 'info') {
                const colores = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    info: 'bg-blue-500',
                    warning: 'bg-yellow-500'
                };
                const iconos = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    info: 'fa-info-circle',
                    warning: 'fa-exclamation-triangle'
                };
                const n = document.createElement('div');
                n.className = `fixed top-4 right-4 ${colores[tipo]} text-white px-6 py-4 rounded-lg shadow-2xl z-50 flex items-center gap-3 animate-slide-in-right max-w-md`;
                n.innerHTML = `<i class="fas ${iconos[tipo]} text-xl"></i><span class="text-sm font-medium">${mensaje}</span>`;
                document.body.appendChild(n);
                setTimeout(() => {
                    n.classList.add('animate-fade-out');
                    setTimeout(() => n.remove(), 300);
                }, 4000);
            }

            function showLoader(show) {
                let loader = inputRuc.parentElement.querySelector('.ruc-loader');
                if (show && !loader) {
                    loader = document.createElement('div');
                    loader.className = 'ruc-loader absolute right-20 top-1/2 transform -translate-y-1/2';
                    loader.innerHTML = '<i class="fas fa-spinner fa-spin text-superarse-morado-medio"></i>';
                    inputRuc.parentElement.style.position = 'relative';
                    inputRuc.parentElement.appendChild(loader);
                } else if (!show && loader) {
                    loader.remove();
                }
            }

            function showEntidad(nombre, type = 'found') {
                entidadNombreResultado.textContent = nombre || '';
                entidadResultado.classList.remove('hidden', 'bg-blue-50', 'border-blue-200', 'bg-red-50', 'border-red-200', 'bg-green-50', 'border-green-200');
                if (type === 'found') {
                    entidadResultado.classList.add('bg-green-50', 'border-green-200');
                } else if (type === 'nf') {
                    entidadResultado.classList.add('bg-red-50', 'border-red-200');
                } else {
                    entidadResultado.classList.add('bg-blue-50', 'border-blue-200');
                }
            }

            function hideEntidad() {
                entidadResultado.classList.add('hidden');
                entidadNombreResultado.textContent = '';
            }

            function limpiarCampos(fieldsObj) {
                Object.values(fieldsObj).forEach(field => {
                    if (field) field.value = '';
                });
            }

            function animarCampoLlenado(input) {
                if (input && input.value) {
                    input.classList.add('bg-green-50', 'border-green-500');
                    setTimeout(() => input.classList.remove('bg-green-50', 'border-green-500'), 1500);
                }
            }

            function buscarEntidadPorRUC(programa, ruc, esAuto = false) {
                if (!esAuto) {
                    btnBuscarRuc.disabled = true;
                    btnBuscarRuc.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
                } else {
                    showLoader(true);
                }
                fetch('<?php echo $this->basePath; ?>/pasantias/buscarEntidadPorRUC', {
                        method: 'POST',
                        body: new URLSearchParams({
                            ruc: ruc,
                            idPrograma: programa
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.entidad) {
                            const mapEntidad = inputFields.entidad;
                            const mapTutor = inputFields.tutor;
                            for (const dbField in mapEntidad) {
                                if (mapEntidad[dbField] && data.entidad[dbField]) {
                                    mapEntidad[dbField].value = data.entidad[dbField];
                                    animarCampoLlenado(mapEntidad[dbField]);
                                }
                            }
                            for (const dbField in mapTutor) {
                                if (mapTutor[dbField] && data.entidad[dbField]) {
                                    mapTutor[dbField].value = data.entidad[dbField];
                                    animarCampoLlenado(mapTutor[dbField]);
                                }
                            }

                            inputRuc.classList.add('border-green-500', 'bg-green-50');
                            setTimeout(() => inputRuc.classList.remove('bg-green-50'), 1500);
                            showEntidad(data.entidad.nombre_empresa || data.entidad.razon_social || 'Empresa');
                            notificar('Empresa encontrada y datos cargados correctamente', 'success');
                        } else {
                            limpiarCampos(inputFields.entidad);
                            limpiarCampos(inputFields.tutor);
                            showEntidad('No encontrada', 'nf');
                            inputRuc.classList.add('border-yellow-500');
                            setTimeout(() => inputRuc.classList.remove('border-yellow-500'), 1500);
                            notificar(data.message || 'Empresa no encontrada. Puede ingresar los datos manualmente.', 'warning');
                            setTimeout(hideEntidad, 3000);
                        }
                    })
                    .catch(() => {
                        inputRuc.classList.add('border-red-500');
                        setTimeout(() => inputRuc.classList.remove('border-red-500'), 1500);
                        showEntidad('Error en la búsqueda', 'nf');
                        notificar('Error al buscar la empresa. Por favor, intente nuevamente.', 'error');
                        setTimeout(hideEntidad, 3000);
                    })
                    .finally(() => {
                        if (!esAuto) {
                            btnBuscarRuc.disabled = false;
                            btnBuscarRuc.innerHTML = '<i class="fas fa-search"></i> Buscar';
                        } else {
                            showLoader(false);
                        }
                    });
            }

            // Búsqueda por tipeo
            inputRuc.addEventListener('input', function() {
                clearTimeout(timeoutBusqueda);
                this.value = this.value.replace(/[^0-9]/g, '');
                const ruc = this.value.trim();
                if (ruc.length >= 10 && ruc.length <= 13) {
                    showLoader(true);
                    timeoutBusqueda = setTimeout(() => buscarEntidadPorRUC(idPrograma, ruc, true), 800);
                } else if (ruc.length < 10) {
                    limpiarCampos(inputFields.entidad);
                    limpiarCampos(inputFields.tutor);
                    hideEntidad();
                    showLoader(false);
                }
            });
            btnBuscarRuc && btnBuscarRuc.addEventListener('click', function(e) {
                e.preventDefault();
                if (!inputRuc.value.trim() || inputRuc.value.trim().length < 10) {
                    notificar('Por favor, ingrese un RUC válido (mín. 10 dígitos)', 'warning');
                    inputRuc.focus();
                    return;
                }
                buscarEntidadPorRUC(idPrograma, inputRuc.value.trim(), false);
            });
            inputRuc.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btnBuscarRuc && btnBuscarRuc.click();
                }
            });
            inputRuc.addEventListener('focus', function() {
                this.classList.remove('border-green-500', 'border-yellow-500', 'border-red-500');
            });

            function limpiarTodoElFormulario() {
                Object.values(inputFields.entidad).forEach(f => f && (f.value = ''));
                Object.values(inputFields.tutor).forEach(f => f && (f.value = ''));
                if (inputRuc) inputRuc.value = '';
                hideEntidad();
                Object.values(labelFields).forEach(l => l && (l.style.display = ''));
                if (tablaProyectos) tablaProyectos.style.display = 'none';
            }

            function setRequiredProyectoSeleccionado(state) {
                document.querySelectorAll("input[name='proyecto_seleccionado']").forEach(input => {
                    if (state) input.setAttribute('required', 'required');
                    else input.removeAttribute('required');
                });
            }

            function toggleCamposByModalidad(isInitialLoad = false) {
                if (!isInitialLoad) {
                    limpiarTodoElFormulario();
                }
                const modalidadValue = modalidadSelect.value;
                const seccionEmpresa = document.getElementById('seccion-empresa');
                const seccionTutorEmpresa = document.getElementById('seccion-tutor-empresa');

                if (modalidadValue && modalidadValue.trim() !== "") {
                    seccionEmpresa.classList.remove('hidden');
                    seccionTutorEmpresa.classList.remove('hidden');
                } else {
                    seccionEmpresa.classList.add('hidden');
                    seccionTutorEmpresa.classList.add('hidden');
                    return;
                }
                // Resetea el estado por defecto para la tabla de proyectos
                tablaProyectos.style.display = 'none';
                setRequiredProyectoSeleccionado(false);
                inputRuc.removeAttribute('readonly');
                infoTutorEmpresarial.style.display = '';
                if (labelInfoTutor) labelInfoTutor.style.display = '';
                Object.values(inputFields.entidad).forEach(f => f && f.removeAttribute('readonly'));
                Object.values(inputFields.tutor).forEach(f => {
                    if (f) {
                        f.removeAttribute('readonly');
                        f.setAttribute('required', 'required');
                    }
                });
                if (afiliacionIESS) afiliacionIESS.style.display = 'none';
                if (inputAfiliacionIESS) inputAfiliacionIESS.removeAttribute('required');
                if (labelFields.plazas_disponibles) labelFields.plazas_disponibles.style.display = '';


                if (modalidadValue === '3') {
                    if (!isInitialLoad || (isInitialLoad && !inputRuc.value.trim())) {
                        inputRuc.value = '1702051704001';
                        buscarEntidadPorRUC(idPrograma, '1702051704001', true);
                    }

                    inputRuc.setAttribute('readonly', 'readonly');
                    Object.values(inputFields.entidad).forEach(f => f && f.setAttribute('readonly', 'readonly'));
                    Object.values(inputFields.tutor).forEach(f => f && f.setAttribute('readonly', 'readonly'));
                    if (btnBuscarRuc) btnBuscarRuc.style.display = 'none';
                    Object.values(labelFields).forEach(l => l && (l.style.display = 'none'));
                    tablaProyectos.style.display = 'block';
                    setRequiredProyectoSeleccionado(true);
                    if (afiliacionIESS) afiliacionIESS.style.display = 'none';

                } else if (modalidadValue === '4') {
                    if (btnBuscarRuc) btnBuscarRuc.style.display = 'none';
                    infoTutorEmpresarial.style.display = 'none';
                    if (labelInfoTutor) labelInfoTutor.style.display = 'none';
                    Object.values(inputFields.tutor).forEach(f => f && f.removeAttribute('required'));
                    inputRuc.removeAttribute('required');
                    if (labelFields.plazas_disponibles) labelFields.plazas_disponibles.style.display = 'none';
                    if (inputFields.entidad.plazas_disponibles) inputFields.entidad.plazas_disponibles.value = '';
                    if (afiliacionIESS) afiliacionIESS.style.display = '';
                    if (inputAfiliacionIESS) inputAfiliacionIESS.setAttribute('required', 'required');

                } else if (modalidadValue === '2') {
                    if (labelFields.plazas_disponibles) labelFields.plazas_disponibles.style.display = 'none';
                    if (inputFields.entidad.plazas_disponibles) {
                        inputFields.entidad.plazas_disponibles.value = '';
                        inputFields.entidad.plazas_disponibles.removeAttribute('required');
                        inputFields.entidad.plazas_disponibles.removeAttribute('name');
                    }
                    if (btnBuscarRuc) btnBuscarRuc.style.display = 'none';
                    inputRuc.removeAttribute('required');

                } else if (modalidadValue === '1') {
                    if (btnBuscarRuc) btnBuscarRuc.style.display = 'inline-flex';
                    Object.values(inputFields.entidad).forEach(f => f && f.setAttribute('readonly', 'readonly'));
                    Object.values(inputFields.tutor).forEach(f => f && f.setAttribute('readonly', 'readonly'));
                    inputRuc.setAttribute('required', 'required');

                } else {
                    if (btnBuscarRuc) btnBuscarRuc.style.display = 'inline-flex';
                    rucContainer.style.display = 'block';
                    inputRuc.setAttribute('required', 'required');
                }
            }

            if (modalidadSelect && modalidadSelect.value) {
                toggleCamposByModalidad(true);
            }

            modalidadSelect && modalidadSelect.addEventListener('change', () => toggleCamposByModalidad(false));

            window.actualizarInfoTutor = function() {
                const selectTutor = $('tutor_academico');
                const correoInput = $('correo_tutor');
                if (selectTutor && correoInput) {
                    const s = selectTutor.options[selectTutor.selectedIndex];
                    correoInput.value = s.getAttribute('data-email') || 'N/D';
                }
            };
        });
    </script>

    <style>
        @keyframes slide-in-right {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fade-out {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .animate-slide-in-right {
            animation: slide-in-right 0.3s ease-out;
        }

        .animate-fade-out {
            animation: fade-out 0.3s ease-out;
        }

        @keyframes slide-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in-up {
            animation: slide-in-up 0.3s ease-in-out;
        }

        body {
            background-color: #000;
        }

        #TablaProyectos {
            transition: all 0.25s ease;
        }
    </style>
</div>