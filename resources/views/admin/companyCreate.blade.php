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
                    인증성공업체 등록
                </h2>
            </div>
            <form action="{{route('admin.companyStore')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" id="title" name="title" class="form-input" value="{{old('title')}}" placeholder="제목을 입력하세요">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            내용
                            <span class="red">*</span>
                        </p>
                        <textarea rows="5" name="content" id="details"
                                  placeholder="내용을 작성해주세요.">{{old('content')}}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이미지
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='pc_file_upload' accept="image/*" name="image"
                                   onchange="displayFileName(this, 'fileName')">
                            <label for="pc_file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <span class="guide-txt">
                                800*800px 비율 고해상도 사진 등록
                            </span>
                            <div class="file-preview">
                                <p class="file-name" id="fileName"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            파일 첨부
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='mb_file_upload' accept="image/*" name="file"
                                   onchange="displayFileName(this, 'mobile_fileName')">
                            <label for="mb_file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <span class="guide-txt">
                                800*800px 비율 고해상도 사진 등록
                            </span>
                            <div class="file-preview">
                                <p class="file-name" id="mobile_fileName"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <div class="form-group">
                            <label for="filter">필터 선택</label>
                            <select id="filter" name="filter" class="form-control">
                                <option value="조달인증">조달인증</option>
                                <option value="품질인증">품질인증</option>
                                <option value="기술인증">기술인증</option>
                                <option value="경영인증">경영인증</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.companyIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit">
                        등록
                    </button>
                    <button class="form-submit-btn" name="continue" type="submit" value="1">
                        등록 후 계속
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
