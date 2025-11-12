<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Listar
    public function index(Request $request)
    {
        // Quitamos el orderBy('position') por si esa columna se borró al resetear la BD
        return $request->user()->tasks()->latest()->get();
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pendiente,en_progreso,completada',
            'priority' => 'in:low,medium,high'
        ]);

        $task = $request->user()->tasks()->create($validated);
        return response()->json($task, 201);
    }

    // Actualizar
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pendiente,en_progreso,completada',
            'priority' => 'in:low,medium,high'
        ]);

        $task->update($validated);
        return response()->json($task);
    }

    // Eliminar
    public function destroy(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $task->delete();
        return response()->json(['message' => 'Tarea eliminada']);
    }
}
