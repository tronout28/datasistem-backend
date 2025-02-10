<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class ReportController extends Controller
{
    public function inputReport(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'report_name' => 'required',
            'status' => 'required|in:open,pending,on-progress,completed',
            'report_date' => 'required|date',
            'note' => 'nullable',
            'image_report' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $produksi_id = Auth::id();

        $report = Report::create([
            'task_id' => $request->task_id,
            'produksi_id' => $produksi_id,
            'report_name' => $request->report_name,
            'status' => $request->status,
            'report_date' => $request->report_date,
            'note' => $request->note,
        ]);

        if ($request->hasFile('image_report')) {
            $image_report = $request->file('image_report');
            $imageName = time() . '.' . $image_report->extension();
            $image_report->move(public_path('image_report'), $imageName);

            if ($report->image_report && file_exists(public_path('image_report/' . $report->image_report))) {
                unlink(public_path('image_report/' . $report->image_report));
            }

            $report->image_report = $imageName;
            $report->image_report = url('image_report/' . $imageName);
            $report->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Report berhasil diinput',
            'data' => $report
        ]);
    }

    public function updateReport(Request $request, $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        $validatedData = $request->validate([
            'task_id' => 'nullable|exists:tasks,id',
            'produksi_id' => 'nullable|exists:users,id',
            'report_name' => 'nullable|string|max:255',
            'status' => 'nullable|in:open,pending,on-progress,completed',
            'report_date' => 'nullable|date',
            'note' => 'nullable|string',
            'image_report' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $updateData = [
            'task_id' => $validatedData['task_id'] ?? $report->task_id,
            'produksi_id' => $validatedData['produksi_id'] ?? $report->produksi_id,
            'report_name' => $validatedData['report_name'] ?? $report->report_name,
            'status' => $validatedData['status'] ?? $report->status,
            'report_date' => $validatedData['report_date'] ?? $report->report_date,
            'note' => $validatedData['note'] ?? $report->note,
        ];

        $report->update($updateData);

        if ($request->hasFile('image_report')) {
            $image_report = $request->file('image_report');
            $imageName = time() . '.' . $image_report->extension();
            $image_report->move(public_path('image_report'), $imageName);

            if ($report->image_report && file_exists(public_path('image_report/' . $report->image_report))) {
                unlink(public_path('image_report/' . $report->image_report));
            }

            $report->update([
                'image_report' => url('image_report/' . $imageName),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Report berhasil diperbarui',
            'data' => $report
        ]);
    }

    public function deleteReport($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        if ($report->image_report && file_exists(public_path('image_report/' . $report->image_report))) {
            unlink(public_path('image_report/' . $report->image_report));
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report berhasil dihapus'
        ]);
    }

    public function getReport($id)
    {
        $report = Report::with(['task', 'produksi'])->find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function getReportByTask($task_id)
    {
        $reports = Report::with(['task', 'produksi'])
            ->where('task_id', $task_id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function index()
    {
        $reports = Report::with(['task', 'produksi'])->get();

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }
}
