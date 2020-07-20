<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use App\Helpers\AuthHelper;
use App\Model\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Requests\CommentRequests\PostCommentRequest;


class CommentController extends Controller
{
    protected $_model; 
    public function __construct(Comment $_model) {
        $this->_model = $_model;
    }
    public function create(PostCommentRequest $data, $eventId){
        $data = $data->validated();
        // $data = $data['comment'];
        $data['user_id'] = AuthHelper::getUserID();
        $data['title'] = $data['title'] ?? 'default';
        $data['event_id'] = $eventId;
        
        $comment = $this->_model->create($data);
        $notiControl = new NotificationController(new Notification());
        $notiControl->create([
            'user_id' => $data['user_id'],
            'body' => [ 'event_id' => $data['event_id'] , 'comment_id' => $comment->id],
            'content' => 'bạn có 1 bình luận mới!!',
            'type' => 'NEW_COMMENT',
            'status' => 'active'
        ]);

       return response()->json([
           'comment' => $comment
       ], 200);
    }
    public function get(Request $request, $eventId){
        $comments = $this->_model->orderBy('created_at', 'desc')
        ->where('event_id', $eventId)
        ->paginate((int) $request->input('limit') ?? 10);
        return response()->json([
            'comment' => $comments
        ], 200);
    }
}
