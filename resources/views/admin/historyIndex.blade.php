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
                        연혁
                    </h2>
                    <div class="top-btn-wrap">
                        <a href="{{route("admin.historyCreate")}}" class="top-btn">
                            등록
                        </a>
                    </div>
                </div>
                <div class="filter_wrap">
                    <div class="filter_input_wrap">
                        <select id="pageCount">
                            <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>1페이지에 8개까지</option>
                            <option value="16" {{ $perPage == 16 ? 'selected' : '' }}>1페이지에 16개까지</option>
                            <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>1페이지에 24개까지</option>
                        </select>
                        <select id="yearFilter">
                            <option value="">전체보기</option>
                            @foreach($years as $year)
                                <option
                                    value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="search-wrap col-group">
                            <button class="search-btn" onclick="filterHistories()">
                                <i class="xi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="board-wrap col-group">
                @if($histories->isEmpty())
                    <div class="null-txt">
                        등록한 게시물이 없습니다.
                    </div>
                @else
                    @foreach($histories as $key => $history)
                        <div class="board-item">
                            <div class="img-box">
                                @if($history->image)
                                    <img src="{{asset('storage/'.$history->image)}}" alt="">
                                @endif
                            </div>
                            <div class="txt-box row-group" data-year="{{ date('Y', strtotime($history->date)) }}">
                                <p class="title">{{date('Y-m', strtotime($history->date))}}</p>
                                <p class="title">{{$history->title}}</p>
                                <div class="btn-wrap col-group">
                                    <a href="{{route("admin.historyEdit", $history->id)}}" class="btn">
                                        수정
                                    </a>
                                    <form action="{{route("admin.historyDelete", $history->id)}}" method="post">
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
            @include('admin.components.pagination', ['paginator' => $histories])
        </div>
    </div>
</div>
<script>
    function filterHistories() {
        var selectedYear = document.getElementById('yearFilter').value;
        var pageCount = document.getElementById('pageCount').value;
        window.location.href = '{{ route("admin.historyIndex") }}?yearFilter=' + selectedYear + '&perPage=' + pageCount;
    }
</script>
</body>
</html>
