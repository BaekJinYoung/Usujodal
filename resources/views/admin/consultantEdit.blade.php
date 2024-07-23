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
                    컨설턴트 상세
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
                        <input type="text" id="name" name="name" class="form-input" value="{{old('name', $item->name)}}" placeholder="이름을 입력하세요">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            직급
                            <span class="red">*</span>
                        </p>
                        <input type="text" id="rank" name="rank" class="form-input" value="{{old('rank', $item->rank)}}" placeholder="직급을 입력하세요">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            컨설팅분야
                        </p>
                        <textarea rows="5" name="department" id="department"
                                  placeholder="내용을 작성해주세요.">{{old('department', $item->department)}}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            약력 소개
                        </p>
                        <textarea rows="5" name="content" id="content"
                                  placeholder="내용을 작성해주세요.">{{old('content', $item->content)}}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            대표사진
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='image_upload' accept="image/*" name="image" style="display: none;">
                            <label for="image_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <span class="guide-txt">
                                320*440px 비율 고해상도 사진 등록
                            </span>
                            <div class="file-preview" id="image-preview"
                                 @if(!$item->image) style="display: none" @endif>
                                <p class="file-name" id="image-filename">
                                    @if($item->image)
                                        {{$item->image_name}}
                                    @endif
                                </p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.consultantIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit">
                        수정
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('image_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('image-preview').style.display = 'block';
            document.getElementById('image-filename').textContent = file.name;
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image_upload').value = '';
        document.getElementById('image-preview').style.display = 'none';
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
