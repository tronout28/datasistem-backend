<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['marketing', 'produksi', 'paket'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua task',
            'data' => $tasks
        ]);
    }

    public function show($id)
    {
        $task = Task::with(['marketing', 'produksi', 'paket'])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail task ditemukan',
            'data' => $task
        ]);
    }

    public function insertTask(Request $request)
    {
        // Validasi request
        $request->validate([
            'produksi_id' => 'nullable|exists:users,id',
            'paket_id' => 'required|exists:pakets,id',
            'task_name' => 'required',
            'bisnis_name' => 'required',
            'bisnis_domain' => 'required',
            'queue' => 'required|integer',
            'status' => 'nullable|in:open,pending,on-progress,completed',
            'deadline' => 'required|date',
            'join_date' => 'required|date',
            'note' => 'nullable',
        ]);

        // Ambil ID user yang login
        $marketing_id = Auth::id();

        // Insert task baru
        $task = Task::create([
            'marketing_id' => $marketing_id,
            'produksi_id' => $request->produksi_id,
            'paket_id' => $request->paket_id,
            'task_name' => $request->task_name,
            'bisnis_name' => $request->bisnis_name,
            'bisnis_domain' => $request->bisnis_domain,
            'queue' => $request->queue ?? 0,
            'status' => $request->status ?? 'open',
            'deadline' => $request->deadline,
            'join_date' => $request->join_date,
            'note' => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil ditambahkan',
            'data' => Task::with(['marketing', 'produksi', 'paket'])->find($task->id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task tidak ditemukan'
            ], 404);
        }

        $validatedData = $request->validate([
            'produksi_id' => 'nullable|exists:users,id',
            'paket_id' => 'nullable|exists:pakets,id',
            'task_name' => 'nullable|string|max:255',
            'bisnis_name' => 'nullable|string|max:255',
            'bisnis_domain' => 'nullable|string|max:255',
            'queue' => 'nullable|integer|min:1',
            'status' => 'nullable|in:open,pending,on-progress,completed',
            'deadline' => 'nullable|date',
            'join_date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $updateData = array_filter($validatedData, function ($value) {
            return !is_null($value);
        });

        $updateData['queue'] = $updateData['queue'] ?? $task->queue;
        $updateData['status'] = $updateData['status'] ?? $task->status;

        $task->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diperbarui',
            'data' => Task::with(['marketing', 'produksi', 'paket'])->find($task->id)
        ]);
    }


    public function delete($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task tidak ditemukan'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil dihapus'
        ]);
    }

    public function getTaskByMarketing()
    {
        $marketing_id = Auth::id();

        $tasks = Task::with(['marketing', 'produksi', 'paket'])
            ->where('marketing_id', $marketing_id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar task marketing',
            'data' => $tasks
        ]);
    }

    public function getTaskByProduksi()
    {
        $produksi_id = Auth::id();

        $tasks = Task::with(['marketing', 'produksi', 'paket'])
            ->where('produksi_id', $produksi_id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar task produksi',
            'data' => $tasks
        ]);
    }

    public function getTaskByPaket($paket_id)
    {
        $tasks = Task::with(['marketing', 'produksi', 'paket'])
            ->where('paket_id', $paket_id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar task paket',
            'data' => $tasks
        ]);
    }
}
