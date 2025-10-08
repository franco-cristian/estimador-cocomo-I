<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    {{-- ENCABEZADO Y TOGGLE DE MODO OSCURO --}}
    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Estimador COCOMO I</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Una herramienta para la estimación de proyectos de software.</p>
        </div>
        <button @click="darkMode = !darkMode"
                class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                aria-label="Toggle dark mode">
            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
    </header>

    {{-- CONTENEDOR PRINCIPAL: FORMULARIO + RESULTADOS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- COLUMNA 1: FORMULARIO DE ENTRADA --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Datos del Proyecto</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="kloc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">KLOC (Miles de Líneas de Código)</label>
                        <input type="number" id="kloc" wire:model.live="kloc" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                        @error('kloc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="salario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salario Medio Mensual ($)</label>
                        <input type="number" id="salario" wire:model.live="salario" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                        @error('salario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="modo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Modo del Proyecto</label>
                        <select id="modo" wire:model.live="modo" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                            <option value="organic" title="Proyectos pequeños, equipos con experiencia, requisitos flexibles.">Orgánico</option>
                            <option value="semi-detached" title="Complejidad y tamaño intermedios, mezcla de experiencia en el equipo.">Semi-acoplado</option>
                            <option value="embedded" title="Proyectos complejos, hardware/software fuertemente acoplado, regulaciones estrictas.">Empotrado</option>
                        </select>
                         @error('modo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN: FACTORES DE COSTO (COST DRIVERS) --}}
            @foreach($driverGroups as $groupName => $driversInGroup)
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">{{ $groupName }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                    @foreach($driversInGroup as $key)
                        @php($driver = $costDrivers[$key])
                        <div>
                            <label for="factor-{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300" title="{{ $driver['name'] }}">{{ $key }}</label>
                            <select id="factor-{{ $key }}" wire:model.live="factores.{{ $key }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                                @foreach($driver['ratings'] as $ratingKey => $value)
                                    @if($value !== null)
                                        <option value="{{ $value }}">{{ $ratingKey }} ({{ $value }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            
            <div class="flex justify-end space-x-4">
                 <button wire:click="resetForm" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Resetear</button>
                 <button wire:click="calculate" type="button" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Calcular Estimación</button>
            </div>
        </div>

        {{-- COLUMNA 2: RESULTADOS Y RESUMEN --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 sticky top-8">
                <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Resultados de la Estimación</h2>
                @if ($resultados)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Esfuerzo Ajustado (PM)</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($resultados['pm'], 2, '.', ',') }} <span class="text-lg font-normal text-gray-600 dark:text-gray-300">persona-meses</span></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Duración del Proyecto</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($resultados['duracion'], 2, '.', ',') }} <span class="text-lg font-normal text-gray-600 dark:text-gray-300">meses</span></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Personal Promedio</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($resultados['personal'], 2, '.', ',') }} <span class="text-lg font-normal text-gray-600 dark:text-gray-300">personas</span></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Costo Total Estimado</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($resultados['costoTotal'], 2, '.', ',') }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Factor de Ajuste de Esfuerzo (EAF)</p>
                            <p class="text-xl font-mono text-gray-800 dark:text-gray-200">{{ number_format($this->eaf, 4, '.', ',') }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-10">
                        <p class="text-gray-500 dark:text-gray-400">Los resultados aparecerán aquí.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>