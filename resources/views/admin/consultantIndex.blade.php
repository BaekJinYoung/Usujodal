<!DOCTYPE html>
<html lang="ko">
@include('admin.components.head')
<body>
<div id="wrap">
    <div class="admin-container">
        <header id="header">
            @include('admin.components.snb')
        </header>
        <div class="admin-wrap admin_photo_gallery">
            <div class="title-wrap col-group">
                <div class="main-title-wrap col-group">
                    <h2 class="main-title">
                        컨설턴트 소개
                    </h2>
                    <div class="top-btn-wrap">
                        <a href="{{route("admin.consultantCreate")}}" class="top-btn">
                            등록
                        </a>
                    </div>
                </div>
            </div>
            <div class="board-wrap col-group">
                @if($items->isEmpty())
                    <div class="null-txt">
                        등록된 게시물이 없습니다.
                    </div>
                @else
                    @foreach($items as $key => $item)
                        <div class="board-item">
                            <div class="img-box">
                                @if($item->main_image)
                                    <img src="{{asset('storage/'.$item->main_image)}}" alt="">
                                @else
                                    <img src="{{asset('images/certificate.png')}}" alt="">
                                @endif
                            </div>
                            <div class="txt-box row-group">
                                <p class="title">{{$item->rank}}</p>
                                <p class="title">{{$item->name}}</p>
                                <p class="title">{{$item->Department}}</p>
                                <p class="title">{{$item->content}}</p>
                                <div class="btn-wrap col-group">
                                    <a href="{{route("admin.consultantEdit", $item->id)}}" class="btn">
                                        수정
                                    </a>
                                    <form action="{{route("admin.consultantDelete", $item->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn del-btn">
                                            삭제
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @include('admin.components.pagination', ['paginator' => $items])
        </div>
    </div>
</div>
<script>
    function updatePageCount() {
        var pageCount = document.getElementById('pageCount').value;
        window.location.href = '?perPage=' + pageCount;
    }
</script>
</body>
</html>
