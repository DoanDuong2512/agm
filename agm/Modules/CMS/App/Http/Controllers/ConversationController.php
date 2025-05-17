<?php
namespace Modules\CMS\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\CMS\App\Services\PageTitleService;

class ConversationController extends Controller
{
    public function index()
    {
        PageTitleService::setTitle('Chat với cổ đông');
        return view('cms::conversation.index');
    }
}
