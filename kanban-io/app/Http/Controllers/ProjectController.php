<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Membuat project baru di dalam workspace.
     */
    public function store(Request $request, Workspace $workspace)
    {
        // Security: Cek apakah user adalah member workspace ini
        /** @var \App\Models\User $user */
        $user = auth()->user();
        abort_unless($user->workspaces->contains($workspace), 403);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 1. Buat Project
        $project = $workspace->projects()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
        ]);

        // 2. Automatis Generate 5 Board Default (Kanban Columns)
        $defaultBoards = ['List Task', 'On Progress', 'Review', 'Revisi', 'Done'];

        foreach ($defaultBoards as $index => $boardName) {
            Board::create([
                'project_id' => $project->id,
                'name' => $boardName,
                'position' => $index // 0, 1, 2, 3, 4
            ]);
        }

        return redirect()->back(); // Tetap di halaman workspace
    }

    /**
     * Menampilkan Kanban Board (Detail Project).
     */
    public function show(Workspace $workspace, Project $project)
    {
        // Load Boards beserta Tasks-nya
        $project->load(['boards.tasks.assignee']);

        return Inertia::render('Project/Board', [
            'workspace' => $workspace,
            'project' => $project
        ]);
    }
}