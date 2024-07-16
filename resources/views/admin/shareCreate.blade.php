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
                    정보공유 등록
                </h2>
            </div>
            <form action="{{route("admin.shareStore")}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            상단 공지
                            <span class="red">*</span>
                        </p>
                        <div class="radio-wrap">
                            <div class="label-wrap col-group">
                                <label for="radio_item_1" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_1" value="1" class="form-radio">
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        Y
                                    </div>
                                </label>
                                <label for="radio_item_2" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_2" value="0" class="form-radio">
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        N
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="title" class="form-input" id="title" value="{{old('title')}}"
                               placeholder="제목을 작성해주세요.">
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
                            <div class="file-preview" id="image-preview" style="display: none">
                                <p class="file-name" id="image-filename"></p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <label for="file">파일 첨부</label>
                        <input type="file" id="file" name="file">
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.shareIndex")}}" class="form-prev-btn">
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

</body>
</html>
