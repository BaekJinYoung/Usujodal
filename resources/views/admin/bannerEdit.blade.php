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
                        <div class="file-upload-wrap">
                            <input type='file' id='pc_file_upload' accept="image/*" name="image"
                                   onchange="displayFileName(this, 'fileName')">
                            <label for="pc_file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview">
                                <p class="file-name" id="fileName">{{$item->image_name}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            모바일 사진 or 동영상
                            <span class="red">*</span>
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='mb_file_upload' accept="image/*" name="mobile_image"
                                   onchange="displayFileName(this, 'mobile_fileName')">
                            <label for="mb_file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview">
                                <p class="file-name" id="mobile_fileName">{{$item->mobile_image_name}}</p>
                            </div>
                        </div>
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
    function displayFileName(input, fileNameElementId) {
        var fileName = input.files[0].name;
        document.getElementById(fileNameElementId).textContent = fileName;
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
