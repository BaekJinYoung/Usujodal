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
                    메인 배너 수정
                </h2>
            </div>
            <form action="{{route("admin.bannerUpdate", $item)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <textarea rows="2" name="title" placeholder="제목을 작성해주세요.">{{'title', old($item->title)}}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            모바일 제목
                            <span class="red">*</span>
                        </p>
                        <textarea rows="2" name="mobile_title" placeholder="제목을 작성해주세요.">{{'title', old($item->mobile_title)}}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            PC 사진 or 동영상
                            <span class="red">*</span>
                        </p>
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
                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            모바일 사진 or 동영상
                            <span class="red">*</span>
                        </p>
                        <div class="file-preview" id="mobile_image-preview"
                             @if(!$item->mobile_image) style="display: none" @endif>
                            <p class="file-name" id="mobile_image-filename">
                                @if($item->mobile_image)
                                    {{$item->mobile_image_name}}
                                @endif
                            </p>
                            <button type="button" class="file-del-btn" id="mobile_remove-image-btn">
                                <i class="xi-close"></i>
                            </button>
                        </div>
                        <input type="hidden" name="mobile_remove_image" id="mobile_remove_image" value="0">
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.bannerIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-submit-btn" type="submit">
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
            document.getElementById('remove_image').value = 0;
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('image_upload').value = '';
        document.getElementById('remove_image').value = 1;
    });

    document.getElementById('mobile_image_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('mobile_image-preview').style.display = 'block';
            document.getElementById('mobile_image-filename').textContent = file.name;
            document.getElementById('mobile_remove_image').value = 0;
        }
    });

    document.getElementById('mobile_remove-image-btn').addEventListener('click', function () {
        document.getElementById('mobile_image-preview').style.display = 'none';
        document.getElementById('mobile_image_upload').value = '';
        document.getElementById('mobile_remove_image').value = 1;
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
