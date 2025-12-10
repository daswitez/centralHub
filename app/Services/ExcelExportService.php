<?php

namespace App\Services;

use Illuminate\Http\Response;

/**
 * Servicio para generar archivos Excel con estilos usando HTML
 * Excel puede abrir archivos .xls con tablas HTML y aplicar estilos básicos
 */
class ExcelExportService
{
    private string $title;
    private array $headers = [];
    private array $data = [];
    private array $summary = [];
    private array $columnWidths = [];
    private string $primaryColor = '#007bff';

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setSummary(array $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function setColumnWidths(array $widths): self
    {
        $this->columnWidths = $widths;
        return $this;
    }

    public function setPrimaryColor(string $color): self
    {
        $this->primaryColor = $color;
        return $this;
    }

    /**
     * Generar respuesta HTTP con archivo Excel
     */
    public function download(string $filename): Response
    {
        $content = $this->generateHtml();

        return response($content)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Generar HTML que Excel puede interpretar
     */
    private function generateHtml(): string
    {
        $colCount = count($this->headers);

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Calibri, Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #dee2e6; padding: 8px 12px; }
        th { 
            background-color: ' . $this->primaryColor . '; 
            color: white; 
            font-weight: bold; 
            text-align: center;
        }
        td { vertical-align: middle; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .title-row { 
            background-color: ' . $this->primaryColor . '; 
            color: white; 
            font-size: 16pt; 
            font-weight: bold; 
            text-align: center;
            padding: 12px;
        }
        .subtitle-row { 
            background-color: #e9ecef; 
            font-size: 10pt; 
            text-align: center;
            color: #6c757d;
        }
        .summary-label { 
            background-color: #f8f9fa; 
            font-weight: bold; 
            text-align: right;
        }
        .summary-value { 
            background-color: #f8f9fa; 
            font-weight: bold;
        }
        .positive { color: #28a745; }
        .negative { color: #dc3545; }
        .badge { 
            padding: 4px 8px; 
            border-radius: 4px; 
            color: white;
            font-size: 9pt;
        }
        .badge-primary { background-color: #007bff; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
        .alt-row { background-color: #f8f9fa; }
        .header-section { 
            background-color: #6c757d; 
            color: white; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table>';

        // Título del reporte
        if (!empty($this->title)) {
            $html .= '<tr><td colspan="' . $colCount . '" class="title-row">' . htmlspecialchars($this->title) . '</td></tr>';
            $html .= '<tr><td colspan="' . $colCount . '" class="subtitle-row">Generado: ' . now()->format('d/m/Y H:i') . '</td></tr>';
            $html .= '<tr><td colspan="' . $colCount . '"></td></tr>'; // Espacio
        }

        // Resumen (KPIs)
        if (!empty($this->summary)) {
            $html .= '<tr><td colspan="' . $colCount . '" class="header-section">📊 Resumen</td></tr>';
            foreach ($this->summary as $label => $value) {
                $html .= '<tr>';
                $html .= '<td class="summary-label" colspan="' . max(1, floor($colCount/2)) . '">' . htmlspecialchars($label) . '</td>';
                $html .= '<td class="summary-value" colspan="' . max(1, ceil($colCount/2)) . '">' . htmlspecialchars($value) . '</td>';
                $html .= '</tr>';
            }
            $html .= '<tr><td colspan="' . $colCount . '"></td></tr>'; // Espacio
        }

        // Encabezados de datos
        $html .= '<tr>';
        foreach ($this->headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        $html .= '</tr>';

        // Datos
        $rowIndex = 0;
        foreach ($this->data as $row) {
            $rowClass = ($rowIndex % 2 === 1) ? ' class="alt-row"' : '';
            $html .= '<tr' . $rowClass . '>';
            
            foreach ($row as $cell) {
                if (is_array($cell)) {
                    // Celda con opciones especiales
                    $value = $cell['value'] ?? '';
                    $class = $cell['class'] ?? '';
                    $badge = $cell['badge'] ?? null;
                    
                    if ($badge) {
                        $html .= '<td class="text-center"><span class="badge badge-' . $badge . '">' . htmlspecialchars($value) . '</span></td>';
                    } else {
                        $html .= '<td class="' . $class . '">' . htmlspecialchars($value) . '</td>';
                    }
                } else {
                    $html .= '<td>' . htmlspecialchars((string)$cell) . '</td>';
                }
            }
            
            $html .= '</tr>';
            $rowIndex++;
        }

        $html .= '</table>
</body>
</html>';

        return $html;
    }
}
