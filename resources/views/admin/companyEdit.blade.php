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
                    인증성공업체 수정
                </h2>
            </div>
            <form action="{{route("admin.companyUpdate", $item)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" id="title" name="title" class="form-input"
                               value="{{old('title', $item->title)}}" placeholder="제목을 입력하세요">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            내용
                            <span class="red">*</span>
                        </p>
                        <textarea rows="5" name="content" id="content"
                                  placeholder="내용을 작성해주세요.">{{old('content', $item->content)}}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이미지 (특허증 or 인증서)
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='image_upload' accept="image/*" name="main_image"
                                   style="display: none;">
                            <label for="image_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <span class="guide-txt">
                                320*440px 비율 고해상도 사진 등록
                            </span>
                            <div class="file-preview" id="image-preview"
                                 @if(!$item->main_image) style="display: none" @endif>
                                <p class="file-name" id="image-filename">
                                    @if($item->main_image)
                                        {{$item->main_image}}
                                    @endif
                                </p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                    <div class="form-item row-group">
                        <div class="form-group">
                            <label for="filter">필터 선택</label>
                            <select id="filter" name="filter" class="form-control">
                                <option value="조달인증" {{ old('filter', $item->filter) == '조달인증' ? 'selected' : '' }}>
                                    조달인증
                                </option>
                                <option value="품질인증" {{ old('filter', $item->filter) == '품질인증' ? 'selected' : '' }}>
                                    품질인증
                                </option>
                                <option value="기술인증" {{ old('filter', $item->filter) == '기술인증' ? 'selected' : '' }}>
                                    기술인증
                                </option>
                                <option value="경영인증" {{ old('filter', $item->filter) == '경영인증' ? 'selected' : '' }}>
                                    경영인증
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.companyIndex")}}" class="form-prev-btn">
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
