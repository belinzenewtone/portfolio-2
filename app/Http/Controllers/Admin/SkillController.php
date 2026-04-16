<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Inertia\Inertia;
class SkillController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Skills/Index', ['skills' => Skill::ordered()->get()]);
    }
    public function create()
    {
        return Inertia::render('Admin/Skills/Form');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'category'   => 'required|in:language,framework,cloud,tool,database,other',
            'icon_url'   => 'nullable|url|max:500',
            'color'      => 'nullable|string|max:100',
            'sort_order' => 'integer|min:0',
            'is_visible' => 'boolean',
        ]);
        Skill::create($data);
        return redirect()->route('admin.skills.index')->with('success', 'Skill added.');
    }
    public function edit(Skill $skill)
    {
        return Inertia::render('Admin/Skills/Form', ['skill' => $skill]);
    }
    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'category'   => 'required|in:language,framework,cloud,tool,database,other',
            'icon_url'   => 'nullable|url|max:500',
            'color'      => 'nullable|string|max:100',
            'sort_order' => 'integer|min:0',
            'is_visible' => 'boolean',
        ]);
        $skill->update($data);
        return redirect()->route('admin.skills.index')->with('success', 'Skill updated.');
    }
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted.');
    }
    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        foreach ($request->order as $position => $id) {
            Skill::where('id', $id)->update(['sort_order' => $position]);
        }
        return response()->json(['ok' => true]);
    }
}
