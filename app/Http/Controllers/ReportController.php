<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Project;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function create()
    {
        $projects = Project::all();
        $types = ['Financial Report', 'Progress Report'];
        return view('reports.create', compact('projects', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:Financial Report,Progress Report',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('reports', $filename, 'public');
            $filePath = 'reports/' . $filename;
        }

        Report::create([
            'project_id' => $request->project_id,
            'uploaded_by' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'source' => 'manual',
            'file_path' => $filePath,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Report uploaded successfully.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:Financial Report,Progress Report',
        ]);

        $project = Project::with([
            'client',
            'allocations.expenses',
            'phases.activities.evidences',
        ])->findOrFail($request->project_id);

        $type = $request->type;
        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'enable_php' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('reports.generated', compact('project', 'type'));



        $filename = time() . '_' . str_replace(' ', '_', $type) . '_' . $project->id . '.pdf';
        $path = 'reports/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        Report::create([
            'project_id' => $project->id,
            'uploaded_by' => auth()->id(),
            'title' => $type . ' — ' . $project->project_name,
            'type' => $type,
            'source' => 'generated',
            'file_path' => $path,
            'notes' => null,
        ]);

        return redirect()->back()->with('success', 'Report generated successfully.');
    }

    public function index()
    {
        $reports = Report::with('project', 'uploader')
            ->latest()
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function download(Report $report)
    {
        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($report->file_path); // @phpstan-ignore-line
    }

    public function preview(Report $report)
    {
        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            abort(404);
        }

        $fullPath = storage_path('app/public/' . $report->file_path);
        $mimeType = mime_content_type($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($report->file_path) . '"',
        ]);
    }
    public function myReports()
    {
        $reports = Report::with('project', 'uploader')
            ->where('uploaded_by', auth()->id())
            ->latest()
            ->get();

        return view('reports.my', compact('reports'));
    }
}
