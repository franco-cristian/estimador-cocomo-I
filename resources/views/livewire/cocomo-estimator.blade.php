<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Estimador COCOMO I</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Una herramienta para la estimacion de proyectos de software.</p>
        </div>
        <button @click="darkMode = !darkMode" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" aria-label="Toggle dark mode">
            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
    </header>

    <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="showCalculator" class="{{ $activeTab === 'calculator' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Calculadora</button>
            <button wire:click="showEstimations" class="{{ $activeTab === 'estimations' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Estimaciones Guardadas
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-100">{{ $savedEstimations->count() }}</span>
            </button>
        </nav>
    </div>

    @if ($activeTab === 'calculator')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Datos del Proyecto</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="projectName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Proyecto</label>
                            <input type="text" id="projectName" wire:model="projectName" placeholder="Ej: Sistema de inventario" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                            @error('projectName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label for="kloc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">KLOC</label>
                            <input type="number" id="kloc" wire:model.live="kloc" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                            @error('kloc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="salario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salario Mensual ($)</label>
                            <input type="number" id="salario" wire:model.live="salario" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                            @error('salario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="modo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Modo</label>
                            <select id="modo" wire:model.live="modo" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-700">
                                <option value="organic" title="Proyectos pequenos, equipos con experiencia, requisitos flexibles.">Organico</option>
                                <option value="semi-detached" title="Complejidad y tamano intermedios, mezcla de experiencia en el equipo.">Semi-acoplado</option>
                                <option value="embedded" title="Proyectos complejos, hardware/software fuertemente acoplado, regulaciones estrictas.">Empotrado</option>
                            </select>
                            @error('modo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @foreach($driverGroups as $groupName => $driversInGroup)
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex justify-between items-center">
                        <h2 class="text-xl font-semibold">{{ $groupName }}</h2>
                        @if ($loop->first)
                        <p class="text-xs text-gray-500 dark:text-gray-400">VL: Muy Bajo, L: Bajo, N: Nominal, H: Alto, VH: Muy Alto, XH: Extra Alto</p>
                        @endif
                    </div>
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
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <input id="useTwoDecimalRounding" type="checkbox" wire:model.live="useTwoDecimalRounding" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="useTwoDecimalRounding" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Usar redondeo a 2 decimales</label>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button wire:click="resetForm" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Resetear</button>
                        <button wire:click="calculate" type="button" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Calcular</button>
                        @if($resultados)
                        <button wire:click="save" type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Guardar</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 sticky top-8">
                    <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Resultados</h2>
                    @if ($resultados)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Esfuerzo (PM)</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($resultados['pm'], 2, '.', ',') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Duracion</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($resultados['duracion'], 2, '.', ',') }} m</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Personal</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($resultados['personal'], 2, '.', ',') }} p</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Costo Total</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($resultados['costoTotal'], 2, '.', ',') }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">EAF</p>
                            <p class="text-xl font-mono text-gray-800 dark:text-gray-200">{{ number_format($resultados['eaf'], 4, '.', ',') }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Parametros del Modelo</p>
                            <p class="text-xs font-mono text-gray-800 dark:text-gray-200">(a={{ $resultados['constants']['a'] }}, b={{ $resultados['constants']['b'] }}, c={{ $resultados['constants']['c'] }}, d={{ $resultados['constants']['d'] }})</p>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-10">
                        <p class="text-gray-500 dark:text-gray-400">Resultados aqui.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @elseif ($activeTab === 'estimations')
        <div>
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Proyecto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Modo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KLOC</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">PM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duracion</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Personal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Costo Total</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($savedEstimations as $estimation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $estimation->project_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $modeTranslations[$estimation->modo] ?? 'Desconocido' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $estimation->kloc }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 font-semibold">{{ number_format($estimation->pm, 2, '.', ',') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($estimation->duracion, 2, '.', ',') }} m</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($estimation->personal, 2, '.', ',') }} p</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 font-bold">${{ number_format($estimation->costo_total, 2, '.', ',') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                <button wire:click="downloadPdf({{ $estimation->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Descargar PDF</button>
                                <button wire:click="delete({{ $estimation->id }})" wire:confirm="¿Estas seguro de que quieres eliminar esta estimacion?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Eliminar</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No hay estimaciones guardadas todavia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>