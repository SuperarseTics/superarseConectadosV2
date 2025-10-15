<div id="pasantias" class="tab-pane">
    <h2 class="text-3xl font-bold text-superarse-morado-oscuro mb-6 border-b pb-2">Gestión de Prácticas
        Pre-Profesionales</h2>

    <!-- ###################################################################### -->
    <!--                                 FASE 1: REGISTRO INICIAL               -->
    <!-- ###################################################################### -->
    <div class="bg-yellow-100 border-l-4 border-superarse-rosa text-gray-800 p-4 mb-6 rounded-md">
        <p class="font-bold">FASE 1: REGISTRO PENDIENTE</p>
        <p class="text-sm">Completa el formulario de registro y asignación para desbloquear la Fase 2.
        </p>
    </div>

    <form action="/pasantias/registrar_fase_uno" method="POST"
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
                <?php echo htmlspecialchars($data['infoPersonal']['programa'] ?? 'N/D'); ?> /
                <?php echo htmlspecialchars($data['infoPersonal']['sede'] ?? 'N/D'); ?></p>
        </div>

        <hr class="border-superarse-morado-medio/20">

        <h3 class="text-xl font-semibold text-superarse-morado-medio">2. Selección de Práctica y
            Asignaciones</h3>

        <!-- Modalidad -->
        <div>
            <label for="modalidad" class="block text-gray-700 font-medium mb-2">Escoja la Modalidad de
                Práctica <span class="text-superarse-rosa">*</span></label>
            <select id="modalidad" name="modalidad" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-superarse-morado-medio">
                <option value="">-- Seleccione una opción --</option>
                <option value="CONVENIOS INSTITUCIONALES">CONVENIOS INSTITUCIONALES</option>
                <option value="AUTOGESTIÓN">AUTOGESTIÓN</option>
                <option value="AYUDANTÍAS EN INVESTIGACIÓN">AYUDANTÍAS EN INVESTIGACIÓN</option>
                <option value="HOMOLOGABLES LABORALES">HOMOLOGABLES LABORALES</option>
            </select>
        </div>

        <!-- Docente Asignado -->
        <div>
            <label for="docente_id" class="block text-gray-700 font-medium mb-2">Su Tutor Académico es
                <span class="text-superarse-rosa">*</span></label>
            <input type="text" id="entidad_ruc" name="entidad_ruc"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg" readonly>
        </div>

        <hr class="border-superarse-morado-medio/20">

        <!-- Registro de Empresa (Entidad) -->
        <h3 class="text-xl font-semibold text-superarse-morado-medio">3. Registro de Empresa /
            Institución</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Se podría usar un buscador aquí, pero por simplicidad se usa un input -->
            <div>
                <label for="entidad_ruc" class="block text-gray-700 font-medium mb-2">RUC de la
                    Empresa</label>
                <input type="text" id="entidad_ruc" name="entidad_ruc"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="RUC de la Entidad">
            </div>
            <div>
                <label for="entidad_nombre" class="block text-gray-700 font-medium mb-2">Nombre de la
                    Empresa</label>
                <input type="text" id="entidad_nombre" name="entidad_nombre"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nombre de la Entidad">
            </div>
            <div>
                <label for="entidad_nombre" class="block text-gray-700 font-medium mb-2">Razon
                    Social</label>
                <input type="text" id="entidad_nombre" name="entidad_nombre"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nombre de la Entidad">
            </div>
            <div>
                <label for="entidad_nombre" class="block text-gray-700 font-medium mb-2">Persona de
                    Contacto</label>
                <input type="text" id="entidad_nombre" name="entidad_nombre"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nombre de la Entidad">
            </div>
            <div>
                <label for="entidad_nombre" class="block text-gray-700 font-medium mb-2">Telefono de
                    Contacto</label>
                <input type="text" id="entidad_nombre" name="entidad_nombre"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nombre de la Entidad">
            </div>
            <div>
                <label for="entidad_nombre" class="block text-gray-700 font-medium mb-2">Email de
                    Contacto</label>
                <input type="text" id="entidad_nombre" name="entidad_nombre"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nombre de la Entidad">
            </div>
            <div>
                <label for="entidad_nombre" class="block text-gray-700 font-medium mb-2">Plazas
                    Disponibles</label>
                <input type="text" id="entidad_nombre" name="entidad_nombre"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nombre de la Entidad">
            </div>
        </div>

        <hr class="border-superarse-morado-medio/20">

        <!-- Registro del Tutor Empresarial -->
        <h3 class="text-xl font-semibold text-superarse-morado-medio">4. Información del Tutor
            Empresarial</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="tutor_nombre" placeholder="Nombre completo del Tutor"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <input type="text" name="tutor_cedula" placeholder="Cédula del Tutor"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <input type="text" name="tutor_funcion" placeholder="Función / Cargo"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <input type="email" name="tutor_email" placeholder="Email del Tutor"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <input type="text" name="tutor_telefono" placeholder="Teléfono"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <input type="text" name="tutor_departamento" placeholder="Departamento/Área"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>

        <button type="submit"
            class="w-full bg-superarse-rosa hover:bg-superarse-morado-medio text-white font-bold py-3 rounded-lg transition duration-300 mt-6">
            Guardar Registro e Iniciar Práctica (Fase 1 Completa)
        </button>
    </form>

    <!-- ###################################################################### -->
    <!--                               FASE 2: SEGUIMIENTO Y GESTIÓN                         -->
    <!-- ###################################################################### -->
    <div class="bg-green-100 border-l-4 border-green-500 text-gray-800 p-4 mb-6 rounded-lg">
        <p class="font-bold">FASE 2: EN EJECUCIÓN</p>
        <p class="text-sm">Tu registro ha sido aprobado. Gestiona tu práctica a continuación.</p>
    </div>
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
</div>