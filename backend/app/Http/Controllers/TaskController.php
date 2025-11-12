<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // get
    public function index(Request $request)
    {
        // Obtener las tareas del usuario autenticado
        return $request->user()->tasks()->latest()->get();
    }

    // post / api/tasks -> crear nueva tarea
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pendiente,en_progreso,completada',
        ]);

        // Crear la tarea asociada al usuario autenticado
        $task = $request->user()->tasks()->create($validate);

        return response()->json($task, 201);
    }

    // put / api/tasks/{id}
    public function update(Request $request, Task $task)
    {
        // Verificar que la tarea pertenece al usuario autenticado
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validate = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pendiente,en_progreso,completada',
        ]);

        $task->update($validate);

        return response()->json($task);
    }

    // delete / api/tasks/{id}
    public function destroy(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $task->delete();

        return response()->json(['message' => 'Tarea eliminada']);
    }
}
