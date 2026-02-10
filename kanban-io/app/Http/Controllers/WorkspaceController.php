<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WorkspaceController extends Controller
{
    /**
     * Menampilkan daftar workspace milik user.
     */
    public function index()
    {
        // Ambil workspace di mana user terdaftar (via pivot table)
        $workspaces = auth()->user()->workspaces;

        return Inertia::render('Dashboard', [
            'workspaces' => $workspaces
        ]);
    }

    /**
     * Membuat workspace baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        // 2. Buat Data Workspace
        $workspace = Workspace::create([
            'user_id' => auth()->id(), // Owner
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4), // Slug unik
            'description' => $request->description,
        ]);

        // 3. PENTING: Attach User ke Workspace_Members sebagai Admin
        $workspace->members()->attach(auth()->id(), ['role' => 'admin']);

        // 4. Redirect ke halaman detail workspace tersebut
        return to_route('workspaces.show', $workspace->slug);
    }

    /**
     * Menampilkan detail workspace & list project.
     */
    public function show(Workspace $workspace)
    {
        // Pastikan user punya akses ke workspace ini (Security Check)
        abort_unless(auth()->user()->workspaces->contains($workspace), 403);

        // Load projects milik workspace ini
        $workspace->load('projects');

        return Inertia::render('Workspace/Show', [
            'workspace' => $workspace,
            'projects' => $workspace->projects
        ]);
    }
}