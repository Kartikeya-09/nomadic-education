<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pdf\WorksheetRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    public function worksheet(WorksheetRequest $request): Response
    {
        $data = $request->validated();

        $pdf = Pdf::loadView('pdf.worksheet', [
            'title' => $data['title'],
            'class_name' => $data['class_name'] ?? null,
            'teacher_name' => $data['teacher_name'] ?? null,
            'date' => $data['date'] ?? null,
            'instructions' => $data['instructions'] ?? null,
            'questions' => $data['questions'],
        ])->setPaper('a4');

        return $pdf->download('worksheet.pdf');
    }
}
