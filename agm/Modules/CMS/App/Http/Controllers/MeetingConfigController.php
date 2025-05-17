<?php

namespace Modules\CMS\App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMS\App\Models\MeetingConfig;
use Illuminate\Support\Facades\Validator;
use Modules\CMS\App\Services\PageTitleService;

class MeetingConfigController extends Controller
{
    protected $pageTitleService;

    public function __construct(PageTitleService $pageTitleService)
    {
        $this->pageTitleService = $pageTitleService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->pageTitleService->setTitle('Cấu hình hệ thống');
        $configs = MeetingConfig::orderBy('id', 'desc')->paginate(10);
        return view('cms::meeting-configs.index', compact('configs'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => ['required', 'string', 'max:255', 'unique:meeting_configs,key'],
            'value' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        MeetingConfig::create($validator->validated());

        return response()->json(['success' => true, 'message' => 'Tạo cấu hình thành công']);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param MeetingConfig $meetingConfig
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, MeetingConfig $meetingConfig)
    {
        $validator = Validator::make($request->all(), [
            'key' => ['required', 'string', 'max:255', 'unique:meeting_configs,key,' . $meetingConfig->id],
            'value' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $meetingConfig->update($validator->validated());

        return response()->json(['success' => true, 'message' => 'Cập nhật cấu hình thành công']);
    }

    /**
     * Remove the specified resource from storage.
     * @param MeetingConfig $meetingConfig
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(MeetingConfig $meetingConfig)
    {
        $meetingConfig->delete();
        
        return response()->json(['success' => true, 'message' => 'Xóa cấu hình thành công']);
    }
}