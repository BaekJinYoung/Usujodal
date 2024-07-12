<!DOCTYPE html>
<html lang="ko">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
@include('admin.components.head')
<body>

<div id="wrap">
    <div class="admin-container">
        <header id="header">
            @include('admin.components.snb')
        </header>
        <div class="admin-wrap">
            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="title-wrap col-group">
                <h2 class="main-title">
                    컨설턴트 소개
                </h2>
            </div>
            <form action="{{route("admin.consultantUpdate", $item)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            이름
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="name" class="form-input" id="name" value="{{old('name', $item->name)}}"
                               placeholder="이름을 작성해주세요.">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            부서
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="Department" class="form-input" id="Department" value="{{old('Department', $item->Department)}}"
                               placeholder="부서를 작성해주세요.">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            직급
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="rank" class="form-input" id="rank" value="{{old('rank', $item->rank)}}"
                               placeholder="직급을 작성해주세요.">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            컨설팅분야 소개
                            <span class="red">*</span>
                        </p>
                        <textarea rows="5" name="content" id="content"
                                  placeholder="내용을 작성해주세요.">{{old('content', $item->content)}}</textarea>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <button class="form-prev-btn" type="submit">
                        수정
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
