<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua task',
            'data' => $tasks
        ]);
    }

    public function show($id)
    {
        $task = Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])->find($id);

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
        $request->validate([
            'produksi_id_1' => 'required|exists:users,id',
            'produksi_id_2' => 'nullable|exists:users,id',
            'produksi_id_3' => 'nullable|exists:users,id',
            'paket_id' => 'required|exists:pakets,id',
            'task_name' => 'required',
            'bisnis_name' => 'required',
            'bisnis_domain' => 'required',
            'queue' => 'nullable|integer',
            'status' => 'nullable|in:open,pending,on-progress,completed',
            'deadline' => 'required|date',
            'join_date' => 'required|date',
            'note' => 'nullable',
        ]);

        $marketing_id = Auth::id();

        $task = Task::create([
            'marketing_id' => $marketing_id,
            'produksi_id_1' => $request->produksi_id_1,
            'produksi_id_2' => $request->produksi_id_2,
            'produksi_id_3' => $request->produksi_id_3,
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
            'data' => Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])->find($task->id)
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
            'produksi_id_1' => 'nullable|exists:users,id',
            'produksi_id_2' => 'nullable|exists:users,id',
            'produksi_id_3' => 'nullable|exists:users,id',
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

        $updateData = [
            'produksi_id_1' => $validatedData['produksi_id_1'] ?? $task->produksi_id_1,
            'produksi_id_2' => $validatedData['produksi_id_2'] ?? $task->produksi_id_2,
            'produksi_id_3' => $validatedData['produksi_id_3'] ?? $task->produksi_id_3,
            'paket_id' => $validatedData['paket_id'] ?? $task->paket_id,
            'task_name' => $validatedData['task_name'] ?? $task->task_name,
            'bisnis_name' => $validatedData['bisnis_name'] ?? $task->bisnis_name,
            'bisnis_domain' => $validatedData['bisnis_domain'] ?? $task->bisnis_domain,
            'queue' => $validatedData['queue'] ?? $task->queue,
            'status' => $validatedData['status'] ?? $task->status,
            'deadline' => $validatedData['deadline'] ?? $task->deadline,
            'join_date' => $validatedData['join_date'] ?? $task->join_date,
            'note' => $validatedData['note'] ?? $task->note,
        ];

        $task->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diperbarui',
            'data' => Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])->find($task->id)
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

        $tasks = Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])
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

        $tasks = Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])
            ->where(function ($query) use ($produksi_id) {
                $query->where('produksi_id_1', $produksi_id)
                    ->orWhere('produksi_id_2', $produksi_id)
                    ->orWhere('produksi_id_3', $produksi_id);
            })
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar task produksi',
            'data' => $tasks
        ]);
    }

    public function getTaskByPaket($paket_id)
    {
        $tasks = Task::with(['marketing', 'produksi1', 'produksi2', 'produksi3', 'paket'])
            ->where('paket_id', $paket_id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar task paket',
            'data' => $tasks
        ]);
    }
}
