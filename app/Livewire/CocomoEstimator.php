<?php

namespace App\Livewire;

use App\Models\Estimation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class CocomoEstimator extends Component
{
    #[Rule('required|string|min:3', message: 'El nombre del proyecto es requerido.')]
    public string $projectName = '';
    public Collection $savedEstimations;
    public string $activeTab = 'calculator';
    public array $modeTranslations = [];
    #[Rule('required|numeric|min:1')]
    public float $kloc = 50;
    #[Rule('required|numeric|min:1')]
    public float $salario = 3000;
    #[Rule('required|in:organic,semi-detached,embedded')]
    public string $modo = 'semi-detached';
    public bool $useTwoDecimalRounding = false;
    public array $factores = [];
    public array $costDrivers = [];
    public array $projectModes = [];
    public array $driverGroups = [];
    public ?array $resultados = null;

    public function mount(): void
    {
        $this->costDrivers = config('cocomo.drivers');
        $this->projectModes = config('cocomo.constants');
        $this->resetFactores();
        $this->driverGroups = [
            'Atributos del Producto' => ['RELY', 'DATA', 'CPLX'],
            'Atributos del Hardware' => ['TIME', 'STOR', 'VIRT', 'TURN'],
            'Atributos del Personal' => ['ACAP', 'AEXP', 'PCAP', 'VEXP', 'LTEX'],
            'Atributos del Proyecto' => ['MODP', 'TOOL', 'SCED'],
        ];

        $this->modeTranslations = [
            'organic' => 'Organico',
            'semi-detached' => 'Semi-acoplado',
            'embedded' => 'Empotrado',
        ];
        $this->loadSavedEstimations();
    }

    public function showCalculator(): void { $this->activeTab = 'calculator'; }
    public function showEstimations(): void { $this->activeTab = 'estimations'; }

    public function downloadPdf(Estimation $estimation)
    {
        $pdf = Pdf::loadView('pdf.estimation-details', compact('estimation'));
        $filename = 'COCOMO-Estimacion-' . Str::slug($estimation->project_name) . '.pdf';
        return response()->streamDownload(fn() => print($pdf->output()), $filename);
    }

    public function loadSavedEstimations(): void { $this->savedEstimations = Estimation::latest()->get(); }
    public function resetFactores(): void { foreach ($this->costDrivers as $key => $driver) { $this->factores[$key] = 1.00; } }
    public function resetForm(): void { $this->reset(); $this->mount(); }
    private function truncate(float $number, int $decimals): float { $power = pow(10, $decimals); return floor($number * $power) / $power; }

    public function calculate(): void
    {
        $this->validate();
        $constants = $this->projectModes[$this->modo];
        $eaf = array_reduce($this->factores, fn($carry, $factor) => $carry * $factor, 1);
        $pmBase = $constants['a'] * pow($this->kloc, $constants['b']);
        if ($this->useTwoDecimalRounding) {
            $eaf = $this->truncate($eaf, 2);
            $pm = $this->truncate($pmBase * $eaf, 2);
            $duracion = $this->truncate($constants['c'] * pow($pm, $constants['d']), 2);
            $personal = $duracion > 0 ? $this->truncate($pm / $duracion, 2) : 0;
            $costoTotal = $personal * $this->salario;
        } else {
            $pm = $pmBase * $eaf;
            $duracion = $constants['c'] * pow($pm, $constants['d']);
            $personal = $duracion > 0 ? $pm / $duracion : 0;
            $costoTotal = $personal * $this->salario;
        }
        $this->resultados = ['pm' => $pm, 'duracion' => $duracion, 'personal' => $personal, 'costoTotal' => $costoTotal, 'eaf' => $eaf, 'constants' => $constants];
    }

    public function save(): void
    {
        $this->validate();
        if (is_null($this->resultados)) { return; }
        Estimation::create([
            'project_name' => $this->projectName, 'kloc' => $this->kloc, 'salario_mensual' => $this->salario, 'modo' => $this->modo,
            'factores_costo' => $this->factores, 'eaf' => $this->resultados['eaf'], 'pm' => $this->resultados['pm'],
            'duracion' => $this->resultados['duracion'], 'personal' => $this->resultados['personal'], 'costo_total' => $this->resultados['costoTotal'],
        ]);
        $this->loadSavedEstimations();
        $this->projectName = '';
        $this->activeTab = 'estimations';
    }

    public function delete(Estimation $estimation): void { $estimation->delete(); $this->loadSavedEstimations(); }
    public function render() { return view('livewire.cocomo-estimator'); }
}