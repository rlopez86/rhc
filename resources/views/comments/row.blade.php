@can('publish', \App\Comment::class)
<span><a href="{{route("comments-publish", $comment->idcomentario)}}"><i class="mdi @if($comment->publicado) mdi-flag @else mdi-flag-outline @endif" ></i></a></span>
@endcan
@can('delete', \App\Comment::class)
<span><a href="{{route("comments-delete", $comment->idcomentario)}}"><i class="mdi mdi-delete"></i></a></span>
@endcan