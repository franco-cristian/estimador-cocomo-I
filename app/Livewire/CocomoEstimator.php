<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class CocomoEstimator extends Component
{
    // Datos de entrada principales del formulario
    #[Rule('required|numeric|min:1', message: 'El KLOC debe ser un número mayor a 0.')]
    public float $kloc = 1000;

    #[Rule('required|numeric|min:1', message: 'El salario debe ser un número mayor a 0.')]
    public float $salario = 500000;

    #[Rule('required|in:organic,semi-detached,embedded', message: 'El modo seleccionado no es válido.')]
    public string $modo = 'organic';

    public array $factores = [];

    public array $costDrivers = [];
    public array $projectModes = [];

    public array $driverGroups = [];

    public ?array $resultados = null;

    /**
     * El método mount se ejecuta una sola vez cuando el componente se inicializa.
     * Cargar la configuración y establecer los valores por defecto.
     */
    public function mount(): void
    {
        $this->costDrivers = config('cocomo.drivers');
        $this->projectModes = config('cocomo.constants');
        $this->resetFactores();

        $this->driverGroups = [
            'Atributos del Producto' => ['RELY', 'DATA', 'CPLX'],
            'Atributos del Hardware' => ['TIME', 'STOR', 'VIRT', 'TURN'],
            'Atributos del Personal' => ['ACAP', 'AEXP', 'PCAP', 'VEXP', 'LEXP'],
            'Atributos del Proyecto' => ['MODP', 'TOOL', 'SCED'],
        ];
    }

    public function resetFactores(): void
    {
        foreach ($this->costDrivers as $key => $driver) {
            $this->factores[$key] = 1.00;
        }
    }

    public function resetForm(): void
    {
        $this->reset();
        $this->mount();
    }

    /**
     * "Computed Property". El resultado se cachea y solo se
     * recalcula si una de sus dependencias cambia.
     */
    public function getEafProperty(): float
    {
        return array_reduce($this->factores, fn($carry, $factor) => $carry * $factor, 1);
    }

    public function calculate(): void
    {
        $this->validate();

        $constants = $this->projectModes[$this->modo];
        // 1. Calcular PM_base (Esfuerzo Base)
        $pmBase = $constants['a'] * pow($this->kloc, $constants['b']);
        // 2. EAF ya está calculado a través de la computed property 'eaf'
        $eaf = $this->eaf;
        // 3. Calcular PM Ajustado (Esfuerzo Ajustado)
        $pm = $pmBase * $eaf;
        // 4. Calcular Duración, Personal Promedio y Costo Total
        $duracion = $constants['c'] * pow($pm, $constants['d']);
        $personal = $duracion > 0 ? $pm / $duracion : 0;
        $costoTotal = $personal * $this->salario;

        // Almacenamos los resultados para mostrarlos en la vista
        $this->resultados = [
            'pm' => $pm,
            'duracion' => $duracion,
            'personal' => $personal,
            'costoTotal' => $costoTotal,
            'eaf' => $eaf,
        ];
    }
    
    public function render()
    {
        return view('livewire.cocomo-estimator');
    }
}