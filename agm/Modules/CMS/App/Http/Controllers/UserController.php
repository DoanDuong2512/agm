<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CMS\App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\CMS\App\Services\PageTitleService;

class UserController extends Controller
{
    public function index()
    {
        PageTitleService::setTitle('Quản lý người dùng');
        $users = User::latest()->paginate(10);
        return view('cms::users.index', compact('users'));
    }

    public function create()
    {
        return view('cms::users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = (int)$validated['is_active'];

        User::create($validated);

        return response()->json([
            'message' => 'Tạo người dùng thành công.',
            'redirect' => route('cms.users.index')
        ]);
    }

    public function edit(User $user)
    {
        return view('cms::users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'is_active' => ['required', 'in:0,1'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = (int)$validated['is_active'];

        $user->update($validated);

        return response()->json([
            'message' => 'Cập nhật người dùng thành công.',
            'redirect' => route('cms.users.index')
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('flash_message', 'Không thể xóa tài khoản của chính mình.')
                ->with('flash_type', 'error');
        }

        $user->delete();

        return redirect()->route('cms.users.index')
            ->with('flash_message', 'Người dùng đã được xóa thành công.')
            ->with('flash_type', 'success');
    }
}
